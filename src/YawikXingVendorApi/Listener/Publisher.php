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
                    //$sessionKeys = unserialize();
                    if (!empty($sessionDataStored)) {
                        $hybridAuth->restoreSessionData($sessionDataStored);
                    }
                    $hauthAdapter = $hybridAuth->authenticate($providerKey);
                    if (isset($hauthAdapter)) {
                        // we don't need the api, it is for more common approaches like userprofiles, letters, groups
                        // at this point we can already use it, without an accesstoken

                        // instanceOf OAuth1Client
                        $api            = $hauthAdapter->api();


                        $api->sha1_method = new \OAuthSignatureMethod_PLAINTEXT();
                        $api->request_token_method = 'POST';

                        //$test1          = $hauthAdapter->getUserProfile();
                        //$test2          = $api->get('https://api.xing.com/v1/users/me');

                        // but we need for sure the accesstoken
                        $accessTokenResponse = $hauthAdapter->getAccessToken();
                        //$accessTokenResponse2 = $hauthAdapter->getAccessToken();



                        $mt = microtime();
                        $rand = mt_rand();
                        $nonce = md5($mt . $rand);

                        $timestamp = time();

                        $ci = curl_init();

                        //curl_setopt( $ci, CURLOPT_USERAGENT     , 'OAuth/1 Simple PHP Client v0.1; HybridAuth http://hybridauth.sourceforge.net/' );
                        curl_setopt( $ci, CURLOPT_CONNECTTIMEOUT, 30 );
                        curl_setopt( $ci, CURLOPT_TIMEOUT       , 30 );
                        curl_setopt( $ci, CURLOPT_RETURNTRANSFER, TRUE );
                        //curl_setopt( $ci, CURLOPT_HTTPHEADER    , array('Expect:', 'Accept: application/vnd.xing.jobs.v1+json') );
                        //curl_setopt( $ci, CURLOPT_HTTPHEADER    , array('Accept: application/vnd.xing.jobs.v1+json') );
                        //curl_setopt( $ci, CURLOPT_HTTPHEADER    , array('Expect:') );
                        //curl_setopt( $ci, CURLOPT_SSL_VERIFYPEER, false);
                        //curl_setopt( $ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader') );
                        //curl_setopt( $ci, CURLOPT_HEADER        , FALSE );

                        // curl_setopt( $ci, CURLOPT_HTTPHEADER, array( 'Expect:', $auth_header ));
                        // curl_setopt( $ci, CURLOPT_HTTPHEADER, array('Expect:', "Content-Type: $content_type"));

                        // null
                        // curl_setopt( $ci, CURLOPT_PROXY        , $this->curl_proxy);

                        curl_setopt( $ci, CURLOPT_POST, TRUE );

                        //
                        //
                        $postfields = ""
                        //    . "oauth_callback=http%3A%2F%2Fmw2.yawik.org%2Flogin%2Fhauth"
                            . "&oauth_consumer_key=778dc7c725b90120beca"
                            . "&oauth_nonce=" . $nonce
                            . "&oauth_signature=526f4b3e84532d98fbb8c1c26899f6bb02dbd113%265d02ccec1f1263db36e9"
                            . "&oauth_signature_method=PLAINTEXT"
                            . "&oauth_timestamp=" . $timestamp
                            . "&oauth_token=fe508f8615c3eaf955f3"
                            . "&oauth_version=1.0"
                        ;

                        $test = '
                            curl -X POST "https://api.xing.com/vendor/jobs/postings" -d "oauth_token=fe508f8615c3eaf955f3" -d "oauth_consumer_key=778dc7c725b90120beca" -d "oauth_signature_method=PLAINTEXT" -d "oauth_signature=526f4b3e84532d98fbb8c1c26899f6bb02dbd113%265d02ccec1f1263db36e9”
                            ';


                        $paramsQuery =  http_build_query($parameter);

                        $postfields .= '&' . $paramsQuery;

                        curl_setopt( $ci, CURLOPT_POSTFIELDS, $postfields );


                        //curl_setopt( $ci, CURLOPT_HTTPHEADER, array( 'Content-Type: application/atom+xml', $auth_header ) );

                        //curl_setopt($ci, CURLOPT_URL, 'https://api.xing.com/v1/request_token');
                        //curl_setopt($ci, CURLOPT_URL, 'https://api.xing.com/v1/access_token');
                        curl_setopt($ci, CURLOPT_URL, 'https://api.xing.com/vendor/jobs/postings');

                        $response = curl_exec($ci);
                        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
                        curl_close ($ci);


                        //$url = $services->get('url');
                        $router = $services->get('router');

                        $tCallBack = $router->assemble(array(), array('name' => 'auth-hauth', 'force_canonical' => True));

                        //oauth-callback
                        //auth-hauth

                        //$requestToken = $api->requestToken();


                        //if (array_key_exists('oauth_token', $requestToken) && array_key_exists('oauth_token_secret', $requestToken)) {
                        //    $accessToken = $api->accessToken();
                        //}


                        // alles mal genau aufgegliedert, mehr haben wir zu diesem Zeitpunkt nicht
                        $consumerKey       = $config['keys']['key'];
                        $consumerSecret    = $config['keys']['secret'];
                        $accessToken       = $accessTokenResponse['access_token'];
                        $accessTokenSecret = $accessTokenResponse['access_token_secret'];

                        if (array_key_exists('access_token', $accessTokenResponse) && array_key_exists('access_token_secret', $accessTokenResponse)) {
                            //$parameter['oauth_timestamp'] = $accessToken;
                            //$hauthAdapter->setTimestamp('aaa');
                            //$hauthAdapter->setNonce();

                            //$parameter['oauth_timestamp'] = time();
                            //$parameter['oauth_nonce'] = md5(uniqid(mt_rand(), true));


                            //$parameter['oauth_token'] = $accessToken;
                            //$parameter['oauth_consumer_key']= $consumerKey;
                            //$parameter['oauth_signature_method']='PLAINTEXT';



                            // %26

                            // **************************

                            //$parameter['oauth_signature']= $consumerSecret . '&' . $accessTokenSecret;

                            //$parameter['oauth_nonce'] = md5(uniqid(mt_rand(), true));
                            //$responseVendor1 = $api->post('https://api.xing.com/vendor/jobs/postings', $parameter);

                            // **************************

                            // %26
                            //$parameter['oauth_signature']= $consumerSecret . '%26' . $accessTokenSecret;

                            //$parameter['oauth_nonce'] = md5(uniqid(mt_rand(), true));
                            //$responseVendor2 = $api->post('https://api.xing.com/vendor/jobs/postings', $parameter);

                            // **************************

                            //$parameter['oauth_signature']= $consumerSecret . '%3D' . $accessTokenSecret;

                            //$parameter['oauth_nonce'] = md5(uniqid(mt_rand(), true));
                            //$responseVendor3 = $api->post('https://api.xing.com/vendor/jobs/postings', $parameter);

                            // **************************

                            //$parameter['oauth_signature']= base64_encode($consumerSecret . '&' . $accessTokenSecret);

                            //$parameter['oauth_nonce'] = md5(uniqid(mt_rand(), true));
                            //$responseVendor4 = $api->post('https://api.xing.com/vendor/jobs/postings', $parameter);

                            // **************************

                            //$parameter['oauth_signature']= base64_encode($consumerSecret . '%26' . $accessTokenSecret);

                            //$parameter['oauth_nonce'] = md5(uniqid(mt_rand(), true));
                            //$responseVendor5 = $api->post('https://api.xing.com/vendor/jobs/postings', $parameter);


                            // **************************

                            //$parameter['oauth_signature']= $consumerSecret . '&';

                            //$parameter['oauth_nonce'] = md5(uniqid(mt_rand(), true));
                            //$responseVendor6 = $api->post('https://api.xing.com/vendor/jobs/postings', $parameter);


                            // **************************

                            //$parameter['oauth_signature']= $consumerSecret . '%26';

                            //$parameter['oauth_nonce'] = md5(uniqid(mt_rand(), true));
                            //$responseVendor7 = $api->post('https://api.xing.com/vendor/jobs/postings', $parameter);



                            if ($api->http_code == 201) {
                                // Request was complete
                                $response = new JobResponse($this->name, JobResponse::RESPONSE_OK);
                            }
                            else {
                                $response = new JobResponse($this->name, JobResponse::RESPONSE_ERROR);
                            }
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

    public static function url_encode_rfc3986($input)
    {
        return str_replace(array('+', '%7E'), array(' ', '~'), rawurlencode($input));
    }

    /**
     * @param $params
     * @param string $request_method
     * @param $url
     * @return string
     */
    protected function _generateSignature($params, $request_method = 'GET', $url )
    {
        uksort($params, 'strcmp');
        $params = self::url_encode_rfc3986($params);

        // Make the base string
        $base_parts = array(
            strtoupper($request_method),
            $url,
            urldecode(http_build_query($params, '', '&'))
        );
        $base_parts = self::url_encode_rfc3986($base_parts);
        $base_string = implode('&', $base_parts);

        // Make the key
        $key_parts = array(
            $this->_consumer_secret,
            ($this->_token_secret) ? $this->_token_secret : ''
        );
        $key_parts = self::url_encode_rfc3986($key_parts);
        $key = implode('&', $key_parts);

        // Generate signature
        return base64_encode(hash_hmac('sha1', $base_string, $key, true));
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

    /**
     * Get the header info to store.
     */
    public function getHeader($ch, $header) {
        $i = strpos($header, ':');

        if ( !empty($i) ){
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            $this->http_header[$key] = $value;
        }

        return strlen($header);
    }

}
