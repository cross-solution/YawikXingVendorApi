<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2015 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Listener;

use Jobs\Listener\Events\JobEvent;
use Jobs\Listener\Response\JobResponse;
use YawikXingVendorApi\Http\XingClient;
use YawikXingVendorApi\Service\CategoryJob;
use Zend\Json\Json;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class PublisherWorker implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected $hybridAuth;
    protected $authorizedUser;
    protected $options;

    public function __construct($hybridAuth, $authorizedUser, $options)
    {
        $this->hybridAuth = $hybridAuth;
        $this->authorizedUser = $authorizedUser;
        $this->options = $options;
    }

    public function run(JobEvent $event, $portalName='')
    {
        $logger = $this->getLogger();

        $job = $event->getJobEntity();
        $data = $this->collateXingData($job, $this->options, $event->getParam('extraData', []));


        if (!$this->validateXingData($data)) {
            $logger && $logger->err('==> Collated xing data is invalid.');

            return new JobResponse($portalName, JobResponse::RESPONSE_ERROR, 'Invalid data.');
        }

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

        //$api = $adapter->api();
        //$baseUrl = str_replace('/v1', '', $api->api_base_url);
        //$body = Json::encode($data);
        //$response = $api->post($baseUrl . 'vendor/jobs/postings', $data, null, null, /*multipart*/ true);


        $logger && $logger->info('--> Sending ...');
        $consumerKeys = $adapter->config['keys'];
        $tokens      = $adapter->getAccessToken();
        $response = $this->sendJob($data, $consumerKeys, $tokens);
        //$client = new XingClient();
        //$response = $client->sendJob($data, $consumerKeys, $tokens);

        if (200 != $response->getStatusCode()) {
            if ($logger) {
                $body = $response->getBody();
                $err = $response->getReasonPhrase();
                $code = $response->getStatusCode();

                $logger->err(sprintf(
                                 '==> Sending failed: [%s] %s',
                                 $code, $err
                             ));
                $logger->debug(var_export($body, true));
            }

            return new JobResponse($portalName, JobResponse::RESPONSE_FAIL, $err);
        }
        if ($logger) {
            $logger->info('==> Success!');
            $logger->debug(var_export($response->getBody(), true));
        }

        return new JobResponse($portalName, JobResponse::RESPONSE_OK, 'Response: ' . var_export($response->getBody(), true));
    }

    protected function collateXingData($job, $options, $extra)
    {
        $logger = $this->getLogger();
        $categoryJob = new CategoryJob();
        if ($logger) {
            $categoryJob->setLogger($this->getLogger());
            $logger->info('--> Collating data ...');
        }
        $parameter = array();
        // check for category_id (required) and subcategory_id (optional)
        $parameter['category_id'] = $categoryJob->getCategory(isset($extra['branches']) ? $extra['branches'] : []);
        // check for city (required)
        $parameter['city'] = $job->location;
        // check for company (required)
        $parameter['company_name'] = $job->company;
        // country (required)
        $parameter['country'] = 'de';
        // description (required)
        $parameter['description'] = isset($extra['description']) ? $extra['description'] : $job->description;
        // function (required)
        $parameter['function'] = $job->title;
        // industry (required)
        $parameter['industry'] =  $categoryJob->getIndustry('');
        // job_type (required)
        $parameter['job_type'] = $categoryJob->getJobType(isset($extra['position']) ? $extra['position'] : '');
        // language (required)
        $parameter['language'] = 'de';
        // level (required)
        $parameter['level'] =  $categoryJob->getJobLevel($job->title);
        // order_id (required)
        $parameter['order_id'] = $options->getOrderId();
        // organization_id (required)
        $parameter['organization_id'] = 5160;//$job->getOrganization()->getId();
        // point_ofcontact_type (required)
        $parameter['point_of_contact_type'] = $job->getContactEmail();
        // tags (required)
        $parameter['tags'] = isset($extra['keywords']) ? $extra['keywords'] : $job->keywords;
        // user_role (required)
        $parameter['user_role'] = 'EMPLOYEE';

        return $parameter;
    }

    protected function validateXingData($data)
    {
        $valid = true;
        $logger = $this->getLogger();
        $logger && $logger->info('--> Validating data ...');
        $requiredFields = ['category_id', 'company_name', 'job_type', 'description' ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $logger && $logger->warn(sprintf('----> Required field "%s" is missing', $field));
                $valid = false;
            }
        }

        return $valid;
    }

    protected function sendJob($data, $consumerKeys, $tokens)
    {
        $ch = curl_init();
        //$data = array_map('urlencode', $data);
        $dataJson = \Zend\Json\Json::encode($data);

        $options = [
            'oauth_token=' . $tokens['access_token'],
            'oauth_consumer_key=' . $consumerKeys['key'],
            'oauth_signature_method=PLAINTEXT',
            'oauth_signature=' . $consumerKeys['secret'] . '%26' . $tokens['access_token_secret'],
            $dataJson
        ];
        $options = implode('&', $options);
        $api_base='https://api.xing.com/vendor/jobs/postings';

        curl_setopt($ch, CURLOPT_URL, $api_base);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $options);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


    }
}