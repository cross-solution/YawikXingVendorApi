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

        $filter   = function($url) {
            list ($url, $trash) = explode('?', $url, 2);
            if (0 !== strpos($url, 'https://')) {
                $url = 'https://' . $url;
            }
            $url = str_replace(
                ['https://xing',     '/profiles/'],
                ['https://www.xing', '/profile/'],
                $url
            );
            return trim($url);
        };
        $validate = function($url) {
            return preg_match('~^https://www\.xing\.com/(?:profile|companies)/[^\? ]+$~', $url);
        };

        $logger = $value->getLogger();
        $logger && $logger->info('---> Determine contact type ...');
        $logger && $logger->debug('profileData: ' . var_export($profileData, true));

        // Determine the "contact type"
        $contactType = false; $return=[];
        if (isset($profileData['personal'])) {
            $logger && $logger->debug('Personal-Url: ' . $profileData['personal']);
            $url = $filter($profileData['personal']);
            $logger && $logger->debug('Filtered Personal-Url: ' . $url);
            if ($validate($url)) {
                $logger && $logger->info('----> Valid user profile found.');

                $contactType = XingData::POINT_OF_CONTACT_TYPE_USER;
                $xingData->setPosterUrl($url);

                $return[] = 'User profile found. Use as contact type.';
                $xingData->setTellMeMore(true);
            }
        }

        if (isset($profileData['company'])) {
            $logger && $logger->debug('Company-Url: ' . $profileData['company']);
            $url = $filter($profileData['company']);
            $logger && $logger->debug('Filtered Company-Url: ' . $url);
            if ($validate($url)) {
                $logger && $logger->info('----> Valid company profile found.');

                $return[] = 'Company profile found.' . ($contactType ? '' : ' Use as contact type.');
                if ('' != $xingData->getReplyEmail()) {
                    $contactType = XingData::POINT_OF_CONTACT_TYPE_COMPANY;
                } else {
                    $contactType = XingData::POINT_OF_CONTACT_TYPE_USER;
                }

                $xingData->setCompanyProfileUrl($url);
                $xingData->setPublishToCompany(true);
                $xingData->setTellMeMore(false);
            }
        }

        if (!$contactType) {
            $logger && $logger->info('----> No valid profile urls found. Set contact type to NONE.');
            $return = 'No valid profiles found. Use contactType NONE.';
            $contactType = XingData::POINT_OF_CONTACT_TYPE_NONE;
            $xingData->setTellMeMore(false);
        }

        $xingData->setPointOfContactType($contactType);

        if (XingData::REPLY_SETTINGS_EMAIL == $xingData->getReplySetting()
            && !$xingData->getReplyEmail()
        ) {
            if (XingData::POINT_OF_CONTACT_TYPE_USER == $contactType) {
                $xingData->setReplySetting(XingData::REPLY_SETTINGS_PRIVATE_MESSAGE);
            } else {
                $xingData->setReplySetting(XingData::REPLY_SETTINGS_PRIVATE_MESSAGE);
            }
        }

        return $return;
    }
}