<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
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
 * @todo write test 
 */
class Contact implements FilterInterface
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
        $data = $value->getData();
        $profileData = isset($data['channels']['XingVendorApi']['xing'])
            ? $data['channels']['XingVendorApi']['xing']
            : array('company' => '', 'personal' => '');

        $validate = function($url) {
            return preg_match('~^https://www.xing.com/(?:profiles|company)/[^\? ]+$~', $url);
        };

        $logger = $value->getLogger();
        $logger && $logger->info('---> Determine contact type ...');

        // Determine the "contact type"
        $contactType = false; $return=[];
        if (isset($profileData['personal']) && $validate($profileData['personal'])) {
            $logger && $logger->info('----> Valid user profile found.');

            $contactType = XingData::POINT_OF_CONTACT_TYPE_USER;
            $xingData->setPosterUrl($profileData['personal']);

            $return[] = 'User profile found. Use as contact type.';
        }

        if (isset($profileData['company']) && $validate($profileData['company'])) {
            $logger && $logger->info('----> Valid company profile found.');

            $return[] = 'Company profile found.' . ($contactType ? '' : ' Use as contact type.');
            $contactType = $contactType ?: XingData::POINT_OF_CONTACT_TYPE_COMPANY;

            $xingData->setCompanyProfileUrl($profileData['company']);

        }

        if (!$contactType) {
            $logger && $logger->info('----> No valid profile urls found. Set contact type to NONE.');

            $return = 'No valid profiles found. Use contactType NONE.';
            $contactType = XingData::POINT_OF_CONTACT_TYPE_NONE;
        }

        $xingData->setPointOfContactType($contactType);

        return $return;
    }
}