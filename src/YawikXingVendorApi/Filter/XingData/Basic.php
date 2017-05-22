<?php
/**
 * YAWIK
 *
 * @filesource
 * @license    MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */

/** */
namespace YawikXingVendorApi\Filter\XingData;


use YawikXingVendorApi\Entity\XingData;
use Zend\Filter\FilterInterface;

/**
 * ${CARET}
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo   write test
 */
class Basic implements FilterInterface
{

    /**
     *
     *
     * @param \YawikXingVendorApi\Filter\XingFilterData $value
     *
     * @return bool|string|array
     */
    public function filter($value)
    {
        $xingData = $value->getXingData();
        $job = $value->getJob();
        $options = $value->getOptions();
        $data = $value->getData();
        $logger = $value->getLogger();
        $return = [];
        $companyName = $job->getOrganization()->getOrganizationName()->getName();
        if (!$companyName) {
            $logger && $logger->notice('---> Organization name not found. Using deprecated company field from job entity.');
            $companyName = $job->getCompany();
            $return['companyName'] = 'Used company field from job entity.';
        }
        if (100 < strlen($companyName)) {
            $oldCompanyName = $companyName;
            $companyName    = substr($companyName, 0, 100);
            $logger && $logger->notice('---> Organization name is more then 100 characters long. Truncating "'
                                       . $oldCompanyName . '" to "' . $companyName . '"');
        }

        $disciplineId = isset($data['channels']['XingVendorApi']['disciplineId'])
                      ? $data['channels']['XingVendorApi']['disciplineId']
                      : 1022;

       $xingOpts = isset($data['channels']['XingVendorApi']['xing'])
            ? $data['channels']['XingVendorApi']['xing']
            : array('company' => '', 'personal' => '', 'industry' => 230000, 'job_level' => '');

        if (isset($data['channels']['XingVendorApi']['link'])) {
            $jobLink = $data['channels']['XingVendorApi']['link'];
        } else {
            $logger && $logger->notice('---> Xing specific job link was not supplied. Using link from the job entity.');
            $jobLink = $job->getLink();
            $return['jobLink'] = 'Used link from the job entity.';
        }

        $xingData->setPostingUrl($jobLink)
                 ->setCompanyName($companyName)
                 ->setDisciplineId($disciplineId)
                 ->setCountry('DE')
                 ->setFunction($job->getTitle())
                 ->setIndustryId(isset($xingOpts['industry']) ? $xingOpts['industry'] : 230000)
                 ->setLanguage('de')
                 ->setReplySetting(XingData::REPLY_SETTINGS_EMAIL)
                 ->setReplyEmail($job->getContactEmail())
                 ->setKeywords(isset($data['keywords']) ? substr(implode(',', $data['keywords']), 0, 250) : '')
                 ->setUserRole(isset($xingOpts['user_role']) ? $xingOpts['user_role'] : XingData::USER_ROLE_EMPLOYEE)
                 ->setBa(false)
                 ->setCreateStoryOnActivation(false)
                 ->setCurrency('EUR')
                 ->setJobCode('123')
                 ->setTellMeMore(true);

        return empty($return) ? true : $return;
    }


}