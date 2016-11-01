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
        $options = isset($data['channels']['XingVendorApi']['xing'])
            ? $data['channels']['XingVendorApi']['xing']
            : array('salary' => 0, 'salary_interval' => 'yearly', 'salary_range_start' => 0, 'salary_range_end' => 0);

        $salary = (float) $options['salary'];
        $salaryInterval = (string) $options['salary_interval'];
        $salaryStart = (float) $options['salary_range_start'];
        $salaryEnd = (float) $options['salary_range_end'];

        $xingData->setSalary($salary)
                 ->setSalaryInterval($salaryInterval)
                 ->setSalaryRangeStart($salaryStart)
                 ->setSalaryRangeEnd($salaryEnd)
        ;

        return true;
    }


}