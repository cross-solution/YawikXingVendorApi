<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Listener;

use Jobs\Entity\StatusInterface;
use Jobs\Listener\Events\JobEvent;
use Jobs\Listener\Response\JobResponse;
use YawikXingVendorApi\Entity\XingData;
use YawikXingVendorApi\Filter\XingDataFilterChain;
use YawikXingVendorApi\Filter\XingFilterData;
use YawikXingVendorApi\Http\XingClient;
use YawikXingVendorApi\Service\CategoryJob;
use Zend\Json\Json;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;
use Jobs\Entity\Job;
use YawikXingVendorApi\Options\ModuleOptions;
use Auth\Entity\User;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class PublisherWorker implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var
     */
    protected $hybridAuth;

    /**
     * @var User
     */
    protected $authorizedUser;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     *
     *
     * @var \YawikXingVendorApi\Repository\JobData
     */
    protected $repository;

    public function __construct($hybridAuth, $authorizedUser, $options, $repository)
    {
        $this->hybridAuth = $hybridAuth;
        $this->authorizedUser = $authorizedUser;
        $this->options = $options;
        $this->repository = $repository;
    }


    public function run(JobEvent $event, $portalName='')
    {
        $logger = $this->getLogger();


        if (!$this->authorizedUser) {
            $logger && $logger->err('==> No authorized user configured.');

            return new JobResponse($portalName, JobResponse::RESPONSE_ERROR, 'No authorized user');
        }

        $authUserSessionData = $this->authorizedUser->getAuthSession('XingVendorApi');

        if (empty($authUserSessionData)) {
            $logger && $logger->err('==> No auth session data for authorized user ' . $this->authorizedUser->getLogin());

            return new JobResponse($portalName, JobResponse::RESPONSE_ERROR, 'No session data');
        }

        $this->hybridAuth->restoreSessionData($authUserSessionData);
        $adapter = $this->hybridAuth->authenticate('XingVendorApi');

        if (!$adapter) {
            $logger && $logger->err('==> Authentication with XING failed.');

            return new JobResponse($portalName, JobResponse::RESPONSE_ERROR, 'Authentication failed');
        }

        $job = $event->getJobEntity();
        $jobStatus = $job->getStatus()->getName();

        $jobData = $this->repository->findOrCreate($job->getId());
        $action = 'INSERT';
        $postingId = $jobData->getPostingId();

        if (!$postingId && StatusInterface::INACTIVE == $jobStatus) {
            $logger && $logger->notice('==> Job was never transmitted to XING. but is INACTIVE. Skipping...');
            return new JobResponse($portalName, JobResponse::RESPONSE_OK, 'Nothing do to, job is not active.');
        }

        if (!$postingId || StatusInterface::INACTIVE != $jobStatus) {

            $data = $this->collateXingData($job, $this->options, $event->getParam('extraData', []));

            if (!$this->validateXingData($data)) {
                $logger && $logger->err('==> Collated xing data is invalid.');

                return new JobResponse($portalName, JobResponse::RESPONSE_ERROR, 'Invalid data.');
            }

            //$data = \Zend\Json\Json::encode($data);

        } else {
            $data = null;
        }


        if ($postingId) {
            if (StatusInterface::INACTIVE == $jobStatus) {
                $action = 'DELETE';
            } else {
                $action = 'UPDATE';
            }
        }


        $consumerKeys = $adapter->config['keys'];
        $tokens      = $adapter->getAccessToken();
        $client = new XingClient($consumerKeys, $tokens, $logger);


        if ('INSERT' == $action) {
            $logger && $logger->info('--> Sending INSERT request ...');
            $response = $client->sendJob($data);

            if (!$response['success']) {
                return $this->createResponse($response, $jobData, $portalName);
            }

            $jobData->addResponse($response['code'], $response['data']);

            $logger && $logger->info('--> Sending ACTIVATE request ...');
            $response = $client->activateJob($response['data']['posting_id']);

            if ($response['success']) {
                $jobData->isActivated(true);
            }

            return $this->createResponse($response, $jobData, $portalName);
        }

        if ('UPDATE' == $action) {
            $logger && $logger->info('--> Sending UPDATE request ...');
            $response = $client->sendJob($data, $postingId);

            if (!$response['success'] || $jobData->isActivated()) {
                return $this->createResponse($response, $jobData, $portalName);
            }

            $jobData->addResponse($response['code'], $response['data']);

            $logger && $logger->info('--> Sending ACTIVATE request ...');
            $response = $client->activateJob($postingId);


            if ($response['success']) {
                $jobData->isActivated(true);
            }

            return $this->createResponse($response, $jobData, $portalName);
        }

        if ('DELETE' == $action) {
            if ($jobData->isActivated()) {
                $logger && $logger->info('--> Sending DEACTIVATE request ...');
                $response = $client->deactivateJob($postingId);

                if (!$response['success']) {
                    return $this->createResponse($response, $jobData, $portalName);
                }

                $jobData->addResponse($response['code'], $response['data']);
                $jobData->isActivated(false);
            }
            $logger && $logger->info('--> Sending DELETE request ...');
            $response = $client->deleteJob($postingId);

            return $this->createResponse($response, $jobData, $portalName);
        }
    }

    protected function createResponse($xingResponse, $jobData, $portalName)
    {
        $logger = $this->getLogger();
        $jobData->addResponse($xingResponse['code'], $xingResponse['data']);
        $this->repository->store($jobData);

        if ($xingResponse['success']) {
            $logger && $logger->info('==> Success');
            return new JobResponse($portalName, JobResponse::RESPONSE_OK, 'Xing-Api call was successfull');

        } else {
            $logger && $logger->err(sprintf('==> Sending failed: Response: %s', var_export($xingResponse, true)));
            return new JobResponse($portalName, JobResponse::RESPONSE_FAIL, false);
        }
    }

    /**
     * @param $job Job
     * @param $options
     * @param $extra
     *
     * @return array
     */
    protected function collateXingData($job, $options, $extra)
    {
        $logger = $this->getLogger();

        if ($logger) {
            $logger->info('--> Collating data ...');
        }

        $xingData = new XingData();
        $xingFilterData = new XingFilterData($xingData, $options, $extra, $job, $logger);
        $filterChain = new XingDataFilterChain();

        $result = $filterChain->filter($xingFilterData);

        $logger && $logger->info('==> Result: ' . var_export($result, true));

        return $xingData;
    }

    protected function validateXingData($data)
    {
        $valid = true;
        $logger = $this->getLogger();
        $logger && $logger->info('--> Validating data ...');
        $requiredFields = [ 'CompanyName', 'JobType' ];

        foreach ($requiredFields as $field) {
            $value = $data->{"get$field"}();
            if (empty($value)) {
                $logger && $logger->warn(sprintf('---> Required field "%s" is missing', $field));
                $valid = false;
            }
        }

        return $valid;
    }
}