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

        $jobData = $this->repository->findOrCreate($job->getId());
        $logger && $logger->info('--> Sending ...');
        $consumerKeys = $adapter->config['keys'];
        $tokens      = $adapter->getAccessToken();
        $response = $this->sendJob($data, $consumerKeys, $tokens);
        //$client = new XingClient();
        //$response = $client->sendJob($data, $consumerKeys, $tokens);

        $jobData->addResponse($response['code'], $response['body']);
        $this->repository->store($jobData);

        switch($response['code']){
            case 200:
            case 201:
                $logger->info('==> Success!');
                $logger->debug(var_export($response['body'], true));
                break;
            default:
                $logger->err(sprintf(
                                 '==> Sending failed: [%s] %s',
                                 $response['code'], $response['body']
                             ));
                return new JobResponse($portalName, JobResponse::RESPONSE_FAIL, false);

        }

        return new JobResponse($portalName, JobResponse::RESPONSE_OK, 'Response: ' . var_export($response['body'], true));
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
        $categoryJob = new CategoryJob();
        if ($logger) {
            $categoryJob->setLogger($this->getLogger());
            $logger->info('--> Collating data ...');
        }

        $xingData = isset($extra['channels']['XingVendorApi']['xing'])
                  ? $extra['channels']['XingVendorApi']['xing']
                  : array('company' => '', 'personal' => '');

        $parameter = array();
        // check for category_id (required) and subcategory_id (optional)
#        $parameter['category_id'] = $categoryJob->getCategory(isset($extra['branches']) ? $extra['branches'] : []);
        // check for city (required)


        /*
         * city (required)
         *
         * Part of the address of the Job-Posting - city.
         */
        $parameter['city'] = $job->location;

        /*
         * company_name (required)
         *
         * The name of the company for this posting. MAX 100 characters.
         */
        $parameter['company_name'] = $job->company;

        /*
         * discipline_id (required)
         *
         * Discipline of the posting - just the ID is necessary.
         */
        $parameter['discipline_id'] = 1003;
        /*
         * country (required)
         *
         * Part of the address of the Job-Posting - country. String with the Country ID.
         */
        $parameter['country'] = 'DE';

        /*
         * description (required)
         *
         * The description of the posting. Should be text only but allows html for certain orders.
         * MAX 10000 characters.
         */

        $logger->info('---> Fetch description from ' . $job->getLink());
        $description = @file_get_contents($job->getLink());

        if (!$description) {
            $description = $extra['description'];

            $logger->notice('----> No description recieved. Fall back to transmitted description.');
        }

        $parameter['description'] = $description ?: $extra['description'];

        /*
         * function (required)
         *
         * The title/function of the posting. MAX 255 characters.
         */
        $parameter['function'] = $job->title;

        /*
         * industry (required)
         *
         * The industry id of the company for this posting.
         */
        $parameter['industry_id'] = '120200'; # $categoryJob->getIndustry('');

        /*
         * job_type (required)
         *
         * The job type for this posting
         *
         * A note on job type: The job type can be set to one of the following
         * PART_TIME, FULL_TIME, CONTRACTOR, INTERN, SEASONAL, TEMPORARY, VOLUNTARY
         */
        $parameter['job_type'] = $categoryJob->getJobType(isset($extra['position']) ? $extra['position'] : '');

        /*
         * language (required)
         *
         * Here you define the language of this posting. Should be an ISO code like defined
         * in ISO_639. The language can be set to one of the following abbreviations:
         *
         * de, en, es, zh, pt, fr, it, ru, nl, pl, ro, no, cs, el, tr, da, ar, he,
         * ja, ko, sv, fi, hu
         */
        $parameter['language'] = 'de';

        /*
         * level (required)
         *
         * The career level for the posting (Set per default to INTERN for student postings)
         *
         * JOBLEVEL_1	Student/Intern
         * JOBLEVEL_2	Entry Level
         * JOBLEVEL_3	Professional/Experienced
         * JOBLEVEL_4	Manager (Manager/Supervisor)
         * JOBLEVEL_5	Executive (VP, SVP, etc.)
         * JOBLEVEL_6	Senior Executive (CEO, CFO, President)
         */
        $parameter['level'] =  $categoryJob->getJobLevel($job->title);

        /*
         * order_id (required)
         *
         * The order_id belongs to your purchased order. You get this ID from XING. Depending
         * on this order is the type of posting that you can post i.e. Text, Logo or Design
         * posting.
         */
        $parameter['order_id'] = $options->getOrderId();

        /*
         * organization_id (required)
         *
         * This is a unique ID for your organization that is provided to you by XING. All
         * orders belong to that organization.
         */
        $parameter['organization_id'] = $options->getOrganizationId();

        /*
         * point_of_contact_type (required)
         *
         * This field determines who can be contacted for more information about this job
         * posting. Can be a String ‘user’ or ‘company’, accordingly the poster_url or
         * company_profile_url must be set. Default is ‘user’. Set to ‘none’ if neither
         * is available.
         */
        $parameter['point_of_contact_type'] = 'user';# $job->getContactEmail();

        /*
         * reply_settings (required)
         *
         * Here you can set the how candidates can apply for this job offer. This is a
         * String and can be email, url or private_message. Default is ‘private_message’,
         * accordingly ‘poster_url’ must be set.
         */
        $parameter['reply_settings'] = 'email'; #$job->getContactEmail();

        /*
         * tags (required)
         *
         * Coma separated list of keywords you think are important or a candidate would use
         * to find this posting. MAX 255 characters.
         */
        $parameter['tags'] = 'software,linux,php';# isset($extra['keywords']) ? $extra['keywords'] : $job->keywords;

        /*
         * user_role (required)
         *
         * The user role of the Job Poster. A note on user roles:
         * The user role can be set to one of the following possibilities:
         *
         * EXTERNAL_RECRUITER, HR_DEPARTMENT, MANAGER, EMPLOYEE, HR_CONSULTANT
         */
        $parameter['user_role'] = 'EMPLOYEE';

        /*
         * ba (optional)
         *
         * Share posting to Bundesagentur für Arbeit. Default is true. Boolean
         */
        $parameter['ba'] = false;

        /*
         * company_profile_url (optional)
         *
         * When publish_to_company and point_of_contact_type set to company this
         * should contain the url to the company profile.
         *
         * A note on company profile url: To be able to assign a company as the point of
         * contact for this posting the field should contain the url to the Company
         * profile on XING. For example https://www.xing.com/company/xing
         */
        $parameter['company_profile_url'] = $xingData['company'];

        /*
         * create_story_on_activation (optional)
         *
         * Publish a story on the point of contact profile with the job posting.
         * Needs poster_url. Boolean.
         */
        $parameter['create_story_on_activation'] = false;

        /*
         * currency (optional)
         *
         * The currency in which the candidate would be paid. Default is EUR.
         * A note on currency: The currency can be set to one of the following abbreviations:
         *
         * BRL, CHF, CNY, DKK, EUR, GBP, HUF, JPY, KPW, KRW, PLN, RON, RUB, SEK, TRY, USD
         */
        $parameter['currency'] = 'EUR';

        /*
         * job_code (optional)
         *
         * A free text job code. MAX 100 characters.
         */
        $parameter['job_code'] = '123';

        /*
         * poster_url (optional)
         *
         * When point_of_contact_type set to user this should contain the url to the users
         * profile.
         *
         * A note on poster url: To be able to assign a user who is the point of contact for
         * this posting the field should contain the url to the profile on XING.
         * For example https://www.xing.com/profiles/Max_Mustermann
         */
        $parameter['poster_url'] = $xingData['personal'];

        /*
         * posting_logo_content (optional)
         *
         * Base64 encoded logo image for orders which allow logos. Image Maximum size is 300 KB.
         */
        //$parameter['posting_logo_content'] = 'http://example.de/job.html';

        /*
         * posting_pdf_content (optional)
         *
         * Base64 encoded pdf file for orders which allow pdfs. Mandatory for certain
         * posting types. PDF maximum size of 2 MB in DIN A4 format.
         */
        //$parameter['posting_pdf_content'] = 'http://example.de/job.html';


        /*
         * posting_url (optional)
         *
         * If your order allows to have your posting hosted on an external site, you can
         * set here the url. It should be a http address.
         */
        $parameter['posting_url'] = $job->getLink();

        /*
         * publish_to_company (optional)
         *
         * When a company_profile_url is given the posting can be displayed on the company
         * profile. Boolean.
         */
        //$parameter['publish_to_company'] = false;

        /*
         * region (optional)
         *
         * Part of the address of the Job-Posting - region.
         */
        //$parameter['region'] = 'Hessen';


        /*
         * reply_email (optional)
         *
         * Url to some kind of application form if the reply_setting is url.
         */
        //$parameter['reply_email'] = 'http://example.de/apply';


        /*
         * reply_url (optional)
         *
         * Url to some kind of application form if the reply_setting is url.
         */
        //$parameter['reply_url'] = 'http://example.de/apply';

        /*
         * salary (optional)
         *
         * The offered salary for a posting. Number.
         */
        //$parameter['salary'] = 100000;

        /*
         * skills (optional)
         *
         * Coma separated list of skills a candidate should have. MAX 255 characters.
         */
        //$parameter['skills'] = 'PHP,Linux,OOP';

        /*
         * street (optional)
         *
         * Part of the address of the Job-Posting - street.
         */
        //$parameter['street'] = 'EMPLOYEE';

        /*
         * student_classification (optional)
         *
         * Student classification for student postings
         *
         *  THESIS     = 1
         *  INTERNSHIP = 2
         *  PARTTIME   = 3
         */
         //$parameter['student_classification'] = true;

        /*
         * tell_me_more (optional)
         *
         * Enables the I’m Interested function on Job Postings. Boolean.
         */
        $parameter['tell_me_more'] = true;

        /*
         * video_link (optional)
         *
         * Video link from Vimeo or YouTube. Mandatory and available only for Professional
         * Plus.
         */
        //$parameter['video_link'] = '';

        /*
         * zipcode (optional)
         *
         * Part of the address of the Job-Posting - zipcode.*
         */
        //$parameter['zip_code'] = '60486';

        return $parameter;
    }

    protected function validateXingData($data)
    {
        $valid = true;
        $logger = $this->getLogger();
        $logger && $logger->info('--> Validating data ...');
        $requiredFields = [ 'company_name', 'job_type' ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $logger && $logger->warn(sprintf('----> Required field "%s" is missing', $field));
                $valid = false;
            }
        }

        return $valid;
    }

    /**
     * @param $data
     * @param $consumerKeys
     * @param $tokens
     *
     * @return array
     */
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

        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return ['code' => $code, 'body' => $body];

    }
}