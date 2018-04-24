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

use Zend\Filter\FilterInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class City implements FilterInterface
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
        $data = isset($data['channels']['XingVendorApi']) ? $data['channels']['XingVendorApi'] : [];
        $logger = $value->getLogger();
        $job = $value->getJob();

        if (isset($data['xingLocation'])) {
            $xingData->setCity($data['xingLocation']['city']);
            $xingData->setTags($data['xingLocation']['city'], 'prepend');
            $logger && $logger->info('----> Set City via "xingLocation": ' . $data['xingLocation']['city']);
            if (isset($data['xingLocation']['postcode'])) {
                $xingData->setZipcode($data['xingLocation']['postcode']);
                $logger && $logger->info('----> Set ZipCode via "xingLocation": ' . $data['xingLocation']['postcode']);
            }
            if (isset($data['xingLocation']['country_code'])) {
                $xingData->setCountry($data['xingLocation']['country_code']);
                $logger && $logger->info('----> Set Country via "xingLocation": ' . $data['xingLocation']['country_code']);
            }

            return true;
        }

        if (isset($data['xingCity'])) {
            $xingData->setCity($data['xingCity']);

            if (isset($data['xingZipCode'])) {
                $zip = trim(array_pop(explode(',', $data['xingZipCode'], 2)));
                $xingData->setZipcode($zip);
            }

            $xingData->setTags($data['xingCity'], 'prepend');

            if ($logger) {
                $logger->info('----> Use provided data...');
                $logger->info('----> Set City: ' . $data['xingCity']);
                $logger->info('----> Set ZipCode: ' . $xingData->getZipcode());
                $logger->info('----> Prepend Tags: ' . $data['xingCity']);
                $logger->info('----> New tags: ' . $xingData->getKeywords());
            }

            return true;
        }

        /*
         * city (required)
         *
         * Part of the address of the Job-Posting - city.
         */
        /* @var \Jobs\Entity\LocationInterface $location */
        $locations = $job->getLocations();
        $locationFound = false;
        if (count($locations)) {

            $cities = [];
            $regions = [];

            foreach ($locations as $loc) {
                /* @var \Jobs\Entity\Location $loc */
                $city = $loc->getCity();
                if ($city) {
                    if (!$locationFound) {
                        //$xingData->setCity($city);
                        $xingData->setZipcode($loc->getPostalcode());
                        $locationFound = true;
                    } else {
                        $cities[] = $city;
                    }

                    $regions[] =  $city;
                }
            }

            if (count($cities)) {
                $xingData->setTags($cities, 'prepend');
                $logger && $logger->info('---> prepend Tags: ' . implode(',', $cities));
                $logger && $logger->info('---> new tags:' . $xingData->getKeywords());
            }

            if (count($regions)) {
                $regionsStr = mb_substr(implode(', ', $regions), 0, 250, 'utf8');
                $region = $xingData->getRegion();
                $region .= ($region ? "$region, " : '')
                         . $regionsStr;
                $xingData->setRegion($region);
                $xingData->setCity($regionsStr);
                $locationFound = true;
                $logger && $logger->info('---> set Region: ' . $region);
            }
        }

        if (!$locationFound) {
            $logger && $logger->notice('---> No job locations found. Use fallback "location" field.');
            list ($city, $trash) = explode(',', $job->getLocation(), 2);


            $xingData->setCity($city);
            $locationFound = 'Used fallback location';
        }

        return $locationFound;

    }
}
