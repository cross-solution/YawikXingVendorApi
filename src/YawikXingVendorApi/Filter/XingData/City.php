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
        $logger = $value->getLogger();
        $job = $value->getJob();

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
                $tags = $xingData->getKeywords();
                $tags = substr(implode(', ', $cities) . ', ' . $tags, 0, 250);
                $xingData->setKeywords($tags);
                $logger && $logger->info('---> set Tags: ' . $tags);
            }

            if (count($regions)) {
                $regionsStr = substr(implode(', ', $regions), 0, 250);
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