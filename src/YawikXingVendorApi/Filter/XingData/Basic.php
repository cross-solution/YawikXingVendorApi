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

        if (isset($data['channels']['XingVendorApi']['link'])) {
            $jobLink = $data['channels']['XingVendorApi']['link'];
        } else {
            $logger && $logger->notice('---> Xing specific job link was not supplied. Using link from the job entity.');
            $jobLink = $job->getLink();
            $return['jobLink'] = 'Used link from the job entity.';
        }

        $xingData->setPostingUrl($jobLink)
                 ->setCompanyName($companyName)
                 ->setDisciplineId(1003)
                 ->setCountry('DE')
                 ->setFunction($job->getTitle())
                 ->setIndustryId(120200)
                 ->setLanguage('de')
                 ->setOrganizationId($options->getOrganizationId())
                 ->setReplySetting(XingData::REPLY_SETTINGS_EMAIL)
                 ->setReplyEmail($job->getContactEmail())
                 ->setKeywords(isset($data['keywords']) ? $data['keywords'] : '')
                 ->setUserRole(XingData::USER_ROLE_EMPLOYEE)
                 ->setBa(false)
                 ->setCreateStoryOnActivation(false)
                 ->setCurrency('EUR')
                 ->setJobCode('123')
                 ->setTellMeMore(true);

        return empty($return) ? true : $return;
    }


}