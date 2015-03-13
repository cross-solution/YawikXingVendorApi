<?php
/**
 * YAWIK
 * 
 * @filesource
 * @copyright (c) 2013-2015 Cross Solution (http://cross-solution.de)
 * @license   MIT
 * @author    weitz@cross-solution.de
 */

namespace YawikXingVendorApi\Listener;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\SharedListenerAggregateInterface;
use Jobs\Listener\Events\JobEvent;
use Zend\EventManager\SharedEventManagerInterface;
use Jobs\Listener\Response\JobResponse;
use Zend\Http\Client;
use Zend\Http\Request;
use YawikXingVendorApi\Service\CategoryJob;
use YawikXingVendorApi\Options\ListenerPublisherOptions;
// use

/**
 * Job listener for triggering actions like sending mail notification
 *
 * @package CamMediaintown\Listener
 */

class Publisher implements SharedListenerAggregateInterface, ServiceManagerAwareInterface
{
    protected $serviceManager;
    protected $name = 'XING';
    protected $options;

    public function __construct(ListenerPublisherOptions $options)
    {
        $this->options = $options;
        return $this;
    }


    /**
     * @param ServiceManager $serviceManager
     * @return $this
     */
    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager() {
        return $this->serviceManager;
    }

    /**
     * @param SharedEventManagerInterface $events
     */
    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach('Jobs', JobEvent::EVENT_JOB_ACCEPTED, array($this, 'postJob'), 20);
        $events->attach('Jobs', JobEvent::PORTAL_AVAIL_NAME, array($this, 'getName'));
        return;
    }

    /**
     * @param SharedEventManagerInterface $events
     * @return $this
     */
    public function detachShared(SharedEventManagerInterface $events) {
        return $this;
    }

    /**
     * @return ListenerPublisherOptions
     */
    protected function getOptions()
    {
        return $this->options;
    }



    /**
     * @return array|object
     */
    protected function getLog()
    {
        return $this->getServiceManager()->get('Core/Log');
    }

    protected function info($message)
    {
        return $this->getLog()->info($message);
    }

    /**
     * name of the publisher
     */
    public function getName() {
        return $this->name;
    }

    /**
     * transfer the Job to XING
     */
    public function postJob(JobEvent $jobEvent) {
        $providerKey = $this->getName();
        if ($jobEvent->hasPortal($providerKey))
        {
            $job         = $jobEvent->getJobEntity();
            $jobOptions     = $jobEvent->getOptions();
            $jobIsValid  = True;
            $categoryJob = new CategoryJob();
            $categoryJob->setLogger($this->getLog());
            $parameter = array();
            // check for category_id (required) and subcategory_id (optional)
            $parameter['category_id'] = $categoryJob->getCategory($jobOptions->branches);
            // check for city (required)
            $parameter['city'] = $job->location;
            // check for company (required)
            $parameter['company_name'] = $job->company;
            // country (required)
            $parameter['country'] = 'de';
            // description (required)
            $parameter['description'] = $jobOptions->description;
            // function (required)
            $parameter['function'] = $job->title;
            // industry (required)
            $parameter['industry'] =  $categoryJob->getIndustry('');
            // job_type (required)
            $parameter['job_type'] = $categoryJob->getJobType($jobOptions->position);
            // language (required)
            $parameter['language'] = 'de';
            // level (required)
            $parameter['level'] =  $categoryJob->getJobLevel($job->title);
            // order_id (required)
            $parameter['order_id'] = $this->getOptions()->getOrderId();
            // organization_id (required)
            $parameter['organization_id'] = $this->getOptions()->getOrganizationId();
            // point_ofcontact_type (required)
            $parameter['point_ofcontact_type'] = $job->getContactEmail();
            // tags (required)
            $parameter['tags'] = $jobOptions->keywords;
            // user_role (required)
            $parameter['user_role'] = 'EMPLOYEE';

            // testing all parameter,
            // since a lot of that is the same, i 'parametrisized' it
            // @TODO this should be more common - outsource it into another class
            $tests = array(
                'category_id' => 'not_empty',
                'company_name' => 'not_empty',
                'job_type' => 'not_empty',
                'description' => 'not_empty',
            );
            foreach ($tests as $key => $test) {
                if (!is_array($test)) {
                    $test = array($test => True);
                }
                foreach ($test as $testValue => $testOption) {
                    if (is_numeric($testValue)) {
                        $testValue = $testOption;
                        $testOption = True;
                    }
                    if ($testValue == 'not_empty') {
                        if (!array_key_exists($key, $parameter) || empty($parameter[$key])) {
                            $jobIsValid = False;
                        }
                    }
                }
            }

            if ($jobIsValid) {
                $services = $this->getServiceManager();
                $config = array();
                $configKeys = array();
                $configGlobal = $services->get('Config');

                if  (array_key_exists('hybridauth', $configGlobal) && array_key_exists($providerKey, $configGlobal['hybridauth'])) {
                    $config = $configGlobal['hybridauth'][$providerKey];
                }
                if  (!empty($config)) {
                    // get the Session from a User
                    $authorizedUser = $services->get('AuthenticationService')->getUser();
                    if (array_key_exists('authorizedUser', $config)) {
                        $authorizedUserLogin = $config['authorizedUser'];
                        $repositoryUsers     = $services->get('repositories')->get('Auth/User');
                        $user                = $repositoryUsers->findByLogin($authorizedUserLogin);
                        if (isset($user)) {
                            $authorizedUser = $user;
                        }
                    }

                    $hybridAuth     = $services->get('HybridAuth');

                    $sessionDataStored = $authorizedUser->getAuthSession($providerKey);
                    if (!empty($sessionDataStored)) {
                        $hybridAuth->restoreSessionData($sessionDataStored);
                    }
                    $hauthAdapter = $hybridAuth->authenticate($providerKey);
                    if (isset($hauthAdapter)) {
                        // we don't need the api, it is for more common approaches like userprofiles, letters, groups
                        // at this point we can already use it, without an accesstoken
                        $api            = $hauthAdapter->api();
                        // but we need for sure the accesstoken
                        $accessTokenResponse = $hauthAdapter->getAccessToken();
                        if (array_key_exists('access_token', $accessTokenResponse) && array_key_exists('access_token_secret', $accessTokenResponse)) {
                            //$parameter[]
                            //$response1a = (array) $api->get('https://api.xing.com/v1/users/me', array());
                            // return False if the transfer has failed
                            $response = new JobResponse($this->name, JobResponse::RESPONSE_ERROR);
                        }
                        else {
                            // there has been no access-token
                            $response = new JobResponse($this->name, JobResponse::RESPONSE_ERROR);
                        }
                    }
                    else {
                        // no stored session was found for the authorized user
                        // or the session is not valid anymore
                        $response = new JobResponse($this->name, JobResponse::RESPONSE_ERROR);
                    }
                }
                else {
                    // no multipost or configuration found
                    $response = new JobResponse($this->name, JobResponse::RESPONSE_NOTIMPLEMENTED);
                }
            }
            else {
                // the Job contains some errors
                $response = new JobResponse($this->name, JobResponse::RESPONSE_FAIL);
            }
        }
        else
        {
            // an export to XING was not requested or denied for other reasons
            $response = new JobResponse($this->name, JobResponse::RESPONSE_DENIED);
        }
        return $response;
    }


    /**
     * allows an event attachment just by class
     * @param JobEvent $e
     */
    public function restPost(JobEvent $e)
    {
        $serviceManager = $this->getServiceManager();
        if (False || $serviceManager->has('Jobs/RestClient')) {
            try {
                $restClient = $serviceManager->get('Jobs/RestClient');

                $entity = $e->getJobEntity();
                $hydrator = $serviceManager->get('Jobs/JsonJobsEntityHydrator');
                $json = $hydrator->extract($entity);
                $restClient->setRawBody($json);
                $response = $restClient->send();
                // @TODO: statusCode is not stored, there is simply no mechanism to track external communication.
                $StatusCode = $response->getStatusCode();

                $e->stopPropagation(true);
            }
            catch (\Exception $e) {
            }
        }
        return;
    }
}
