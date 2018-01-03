<?php
/**
 * YAWIK
 * 
 * @filesource
 * @copyright (c) 2013-2015 Cross Solution (http://cross-solution.de)
 * @license   MIT
 * @author    weitz@cross-solution.de
 */

namespace YawikXingVendorApi\Filter\XingData;

use YawikXingVendorApi\Entity\XingData;
use Zend\Filter\FilterInterface;


/**
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 */
class JobTypeAndLevel implements FilterInterface
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

        $logger = $value->getLogger();
        $logger && $logger->info('---> Determine job type, level, and category ...');

        $xingData = $value->getXingData();
        $data = $value->getData();

        $return = [];
        $jobType = isset($data['channels']['XingVendorApi']['jobType'])
            ? $data['channels']['XingVendorApi']['jobType']
            : 'FULL_TIME'
        ;
        $logger && $logger->info('----> job type: ' . $jobType);

        $xingData->setJobType($jobType)
                 ->setLevel(isset($data['channels']['XingVendorApi']['xing']['job_level'])
                            ? $data['channels']['XingVendorApi']['xing']['job_level']
                            : 'JOBLEVEL_3'
                 );


        return empty($return) ? true : $return;

    }
}
