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
class Salary implements FilterInterface
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
        if (!isset($data['channels']['XingVendorApi']['xing'])) {
            return true;
        }

        $options = array_merge(
            array('salary' => 0, 'salary_interval' => 'yearly', 'salary_range_start' => 0, 'salary_range_end' => 0),
            $data['channels']['XingVendorApi']['xing']
        );

        $salary = (float) $options['salary'];
        $salaryInterval = (string) $options['salary_interval'];
        $salaryStart = (float) $options['salary_range_start'];
        $salaryEnd = (float) $options['salary_range_end'];

        /*
         * Xing allows either a salary range or a fixed salary. Not both together,
         * so we need to decide what to submit...
         */
        if ($salaryEnd) {
            $xingData->setSalaryRangeStart($salaryStart ?: $salary)
                     ->setSalaryRangeEnd($salaryEnd);
        } else if ($salary || $salaryStart) {
            $xingData->setSalary($salary ?: $salaryStart);
        }

        $xingData->setSalaryInterval($salaryInterval);

        return true;
    }


}