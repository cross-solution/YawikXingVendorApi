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
        $job = $value->getJob();

        /*
         * city (required)
         *
         * Part of the address of the Job-Posting - city.
         */
        /* @var \Jobs\Entity\LocationInterface $location */
        $locations = $job->getLocations();
        if (count($locations)) {
            $location  = $locations->first();
            $city = $location->getCity();
            if ($city) {
                $xingData->setCity($city);

                return true;
            }
        }

        $logger = $value->getLogger();

        $logger && $logger->notice('---> No job locations found. Use fallback "location" field.');
        list ($city, $trash) = explode(',', $job->getLocation(), 2);


        $xingData->setCity($city);

        return 'Used fallback location.';

    }
}