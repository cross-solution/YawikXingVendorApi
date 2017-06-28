<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Entity;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class XingData 
{
    
    const JOB_TYPE_PART_TIME = 'PART_TIME';
    const JOB_TYPE_FULL_TIME = 'FULL_TIME';
    const JOB_TYPE_CONTRACTOR = 'CONTRACTOR';
    const JOB_TYPE_INTERN = 'INTERN';
    const JOB_TYPE_SEASONAL = 'SEASONAL';
    const JOB_TYPE_TEMPORARY = 'TEMPORARY';
    const JOB_TYPE_VOLUNTARY = 'VOLUNTARY';

    const POINT_OF_CONTACT_TYPE_USER = 'user';
    const POINT_OF_CONTACT_TYPE_COMPANY = 'company';
    const POINT_OF_CONTACT_TYPE_NONE = 'none';

    const REPLY_SETTINGS_EMAIL = 'email';
    const REPLY_SETTINGS_URL = 'url';
    const REPLY_SETTINGS_PRIVATE_MESSAGE = 'private_message';

    const USER_ROLE_EXTERNAL_RECRUITER = 'EXTERNAL_RECRUITER';
    const USER_ROLE_HR_DEPARTMENT = 'HR_DEPARTMENT';
    const USER_ROLE_MANAGER = 'MANAGER';
    const USER_ROLE_EMPLOYEE = 'EMPLOYEE';
    const USER_ROLE_HR_CONSULTANT = 'HR_CONSULTANT';

    const JOB_LEVEL_STUDENT_INTERNSHIP = 'JOBLEVEL_1';
    const JOB_LEVEL_ENTRY_LEVEL = 'JOBLEVEL_2';
    const JOB_LEVEL_PROFESSIONAL_EXPERIENCED = 'JOBLEVEL_3';
    const JOB_LEVEL_MANAGER_SUPERVISOR = 'JOBLEVEL_4';
    const JOB_LEVEL_EXECUTIVE = 'JOBLEVEL_5_VP_SVP';
    const JOB_LEVEL_CEO_CFO_PRESIDENT = 'JOBLEVEL_6';

    const SALARY_INTERVAL_YEARLY = 'yearly';
    const SALARY_INTERVAL_MONTHLY = 'monthly';
    const SALARY_INTERVAL_WEEKLY = 'weekly';
    const SALARY_INTERVAL_HOURLY = 'hourly';

    /**
     * @var string
     */
    protected $city;
    protected $companyName;
    protected $companyProfileUrl;
    protected $country;
    protected $description;
    protected $disciplineId;
    protected $function;
    protected $industryId;
    protected $jobType;
    protected $language;
    protected $level;
    protected $orderId;
    protected $replySetting;
    protected $organizationId;
    protected $pointOfContactType;
    protected $tags;
    protected $userRole;
    protected $ba;
    protected $createStoryOnActivation;
    protected $currency;
    protected $jobCode;
    protected $posterUrl;
    protected $postingLogoContent;
    protected $postingPdfContent;
    protected $postingUrl;
    protected $publishToCompany;
    protected $region;
    protected $replyEmail;
    protected $replyUrl;
    protected $salary;
    protected $salaryInterval;
    protected $salaryRangeStart;
    protected $salaryRangeEnd;
    protected $skills;
    protected $street;
    protected $studentClassification;
    protected $tellMeMore;
    protected $videoLink;
    protected $zipcode;
    protected $keywords;

    /**
     * @param mixed $ba
     *
     * @return self
     */
    public function setBa($ba)
    {
        $this->ba = (bool) $ba;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBa()
    {
        return $this->ba;
    }

    /**
     * @param string $city
     *
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $companyName
     *
     * @return self
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyProfileUrl
     *
     * @return self
     */
    public function setCompanyProfileUrl($companyProfileUrl)
    {
        $this->companyProfileUrl = $companyProfileUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompanyProfileUrl()
    {
        return $this->companyProfileUrl;
    }

    /**
     * @param mixed $country
     *
     * @return self
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $createStoryOnActivation
     *
     * @return self
     */
    public function setCreateStoryOnActivation($createStoryOnActivation)
    {
        $this->createStoryOnActivation = (bool) $createStoryOnActivation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreateStoryOnActivation()
    {
        return $this->createStoryOnActivation;
    }

    /**
     * @param mixed $currency
     *
     * @return self
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $disciplineId
     *
     * @return self
     */
    public function setDisciplineId($disciplineId)
    {
        $this->disciplineId = $disciplineId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisciplineId()
    {
        return $this->disciplineId;
    }

    /**
     * @param mixed $function
     *
     * @return self
     */
    public function setFunction($function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param mixed $industryId
     *
     * @return self
     */
    public function setIndustryId($industryId)
    {
        $this->industryId = $industryId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndustryId()
    {
        return $this->industryId;
    }

    /**
     * @param mixed $jobCode
     *
     * @return self
     */
    public function setJobCode($jobCode)
    {
        $this->jobCode = $jobCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJobCode()
    {
        return $this->jobCode;
    }

    /**
     * @param mixed $jobType
     *
     * @return self
     */
    public function setJobType($jobType)
    {
        $this->jobType = $jobType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJobType()
    {
        return $this->jobType;
    }

    /**
     * @param mixed $keywords
     *
     * @return self
     */
    public function setKeywords($keywords, $mode='replace')
    {
        if (is_array($keywords)) {
            $keywords = implode(',', $keywords);
        }

        switch ($mode) {
            default:
                break;

            case "prepend":
                $keywords .= ',' . $this->keywords;
                break;

            case "append":
                $keywords = $this->keywords . ',' . $keywords;
                break;

        }

        $keywords       = trim($keywords, ',');
        $keywords       = mb_substr($keywords, 0, 250, 'utf8');
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param mixed $language
     *
     * @return self
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $level
     *
     * @return self
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $orderId
     *
     * @return self
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $organizationId
     *
     * @return self
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @param mixed $pointOfContactType
     *
     * @return self
     */
    public function setPointOfContactType($pointOfContactType)
    {
        $this->pointOfContactType = $pointOfContactType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPointOfContactType()
    {
        return $this->pointOfContactType;
    }

    /**
     * @param mixed $posterUrl
     *
     * @return self
     */
    public function setPosterUrl($posterUrl)
    {
        $this->posterUrl = $posterUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosterUrl()
    {
        return $this->posterUrl;
    }

    /**
     * @param mixed $postingLogoContent
     *
     * @return self
     */
    public function setPostingLogoContent($postingLogoContent)
    {
        $this->postingLogoContent = $postingLogoContent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostingLogoContent()
    {
        return $this->postingLogoContent;
    }

    /**
     * @param mixed $postingPdfContent
     *
     * @return self
     */
    public function setPostingPdfContent($postingPdfContent)
    {
        $this->postingPdfContent = $postingPdfContent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostingPdfContent()
    {
        return $this->postingPdfContent;
    }

    /**
     * @param mixed $postingUrl
     *
     * @return self
     */
    public function setPostingUrl($postingUrl)
    {
        $this->postingUrl = $postingUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostingUrl()
    {
        return $this->postingUrl;
    }

    /**
     * @param mixed $publishToCompany
     *
     * @return self
     */
    public function setPublishToCompany($publishToCompany)
    {
        $this->publishToCompany = (bool) $publishToCompany;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublishToCompany()
    {
        return $this->publishToCompany;
    }

    /**
     * @param mixed $region
     *
     * @return self
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $replyEmail
     *
     * @return self
     */
    public function setReplyEmail($replyEmail)
    {
        $this->replyEmail = $replyEmail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReplyEmail()
    {
        return $this->replyEmail;
    }

    /**
     * @param mixed $replySetting
     *
     * @return self
     */
    public function setReplySetting($replySetting)
    {
        $this->replySetting = $replySetting;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReplySetting()
    {
        return $this->replySetting;
    }

    /**
     * @param mixed $replyUrl
     *
     * @return self
     */
    public function setReplyUrl($replyUrl)
    {
        $this->replyUrl = $replyUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReplyUrl()
    {
        return $this->replyUrl;
    }

    /**
     * @param mixed $salary
     *
     * @return self
     */
    public function setSalary($salary)
    {
        $this->salary = $this->filterSalary($salary);;

        return $this;
    }

    protected function filterSalary($salary)
    {
        $salary = str_replace(',', '.', $salary);
        $salary = (float) $salary;

        return 0 >= $salary ? null : $salary;
    }

    /**
     * @return mixed
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * @param mixed $salaryInterval
     *
     * @return self
     */
    public function setSalaryInterval($salaryInterval)
    {
        if (!in_array($salaryInterval, [self::SALARY_INTERVAL_HOURLY, self::SALARY_INTERVAL_MONTHLY, self::SALARY_INTERVAL_WEEKLY, self::SALARY_INTERVAL_YEARLY])
            || self::SALARY_INTERVAL_YEARLY == $salaryInterval
        ) {
            $salaryInterval = null;

        }

        $this->salaryInterval = $salaryInterval;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSalaryInterval()
    {
        return $this->salaryInterval ?: self::SALARY_INTERVAL_YEARLY;
    }

    /**
     * @param mixed $salaryRangeEnd
     *
     * @return self
     */
    public function setSalaryRangeEnd($salaryRangeEnd)
    {
        $this->salaryRangeEnd = $this->filterSalary($salaryRangeEnd);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSalaryRangeEnd()
    {
        return $this->salaryRangeEnd;
    }

    /**
     * @param mixed $salaryRangeStart
     *
     * @return self
     */
    public function setSalaryRangeStart($salaryRangeStart)
    {
        $this->salaryRangeStart = $this->filterSalary($salaryRangeStart);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSalaryRangeStart()
    {
        return $this->salaryRangeStart;
    }




    /**
     * @param mixed $skills
     *
     * @return self
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param mixed $street
     *
     * @return self
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $studentClassification
     *
     * @return self
     */
    public function setStudentClassification($studentClassification)
    {
        $this->studentClassification = $studentClassification;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStudentClassification()
    {
        return $this->studentClassification;
    }

    /**
     * @param mixed $tags
     *
     * @return self
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tellMeMore
     *
     * @return self
     */
    public function setTellMeMore($tellMeMore)
    {
        $this->tellMeMore = (bool) $tellMeMore;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTellMeMore()
    {
        return $this->tellMeMore;
    }

    /**
     * @param mixed $userRole
     *
     * @return self
     */
    public function setUserRole($userRole)
    {
        $this->userRole = $userRole;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserRole()
    {
        return $this->userRole;
    }

    /**
     * @param mixed $videoLink
     *
     * @return self
     */
    public function setVideoLink($videoLink)
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVideoLink()
    {
        return $this->videoLink;
    }

    /**
     * @param mixed $zipcode
     *
     * @return self
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }



    public function toArray()
    {
        $return = [];
        $transform = function($match) { return strtolower('_' . $match[0]); };

        foreach (get_object_vars($this) as $name => $value) {
            if (null === $value) { continue; }
            $name = preg_replace_callback('~[A-Z]~', $transform, $name);
            $return[$name] = $value;
        }

        return $return;
    }

    public function toJson()
    {
        $data = $this->toArray();
        $json = \Zend\Json\Json::encode($data);

        return $json;
    }


}