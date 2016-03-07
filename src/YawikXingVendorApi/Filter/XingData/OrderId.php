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
class OrderId implements FilterInterface
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
        $options = $value->getOptions();
        $data = $value->getData();
        $logger = $value->getLogger();
        $dataKey = $options->getOrderIdKey();
        $orderIdKey = isset($data['YawikXingVendorApi'][$dataKey])
                    ? $data['YawikXingVendorApi'][$dataKey] : 'DEFAULT';
        $orderId = $options->getOrderId($orderIdKey);

        $logger && $logger->info('---> ' . sprintf(
                                     'Found orderId %d with dataKey "%s" and orderIdKey "%s"',
                                     $orderId, $dataKey, $orderIdKey
                                 ));

        $xingData->setOrderId($orderId);

        return "Use orderId $orderIdKey => $orderId";
    }


}