<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Filter;

use Zend\Filter\Exception;
use Zend\Filter\FilterChain;
use Zend\Stdlib\ArrayUtils;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class XingDataFilterChain extends FilterChain
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->attach(new XingData\Basic())
             ->attach(new XingData\OrderId())
             ->attach(new XingData\City())
             ->attach(new XingData\Contact())
             ->attach(new XingData\Description())
             ->attach(new XingData\JobTypeAndLevel());

    }


    public function filter($value)
    {
        if (!$value instanceof XingFilterData) {
            throw new \InvalidArgumentException('Value must be an instance of XingFilterData');
        }
        /* @var $value XingFilterData */
        if (null === $value->getOptions()) {
            throw new \InvalidArgumentException('ModuleOptions must be set in the XingFilterData object.');
        }

        if (null === $value->getJob()) {
            throw new \InvalidArgumentException('Job entity must be set in the XingFilterData object.');
        }

        if (null === $value->getXingData()) {
            throw new \InvalidArgumentException('Xing data object must be set in the XingFilterData object.');
        }

        if (!is_array($value->getData())) {
            throw new \InvalidArgumentException('Extra data must be an array');
        }

        $chain = clone $this->filters;

        $result = [];
        foreach ($chain as $filter) {
            $key = is_object($filter[0]) ? get_class($filter[0]) : $filter[0];
            $result[$key] = call_user_func($filter, $value);
        }

        return $result;
    }


}