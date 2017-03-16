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
        $orderIdKey = isset($data['channels']['XingVendorApi'][$dataKey])
                    ? $data['channels']['XingVendorApi'][$dataKey] : 'DEFAULT';
        $orderId = $options->getOrderId($orderIdKey);

        $logger && $logger->info('---> ' . sprintf(
                                     'Found orderId %d with dataKey "%s" and orderIdKey "%s"',
                                     $orderId, $dataKey, $orderIdKey
                                 ));

        $xingData->setOrderId($orderId);

        $dataOrgIdKey = $options->getOrganizationIdKey();
        $orgIdKey = isset($data['channels']['XingVendorApi'][$dataOrgIdKey])
            ? $data['channels']['XingVendorApi'][$dataOrgIdKey] : 'DEFAULT';
        $orgId = $options->getOrganizationId($orgIdKey);

        $logger && $logger->info('---> ' . sprintf(
                'Found organizationId %d with dataKey "%s" and orgIdKey "%s"',
                $orgId, $dataOrgIdKey, $orgIdKey
            ));

        $xingData->setOrganizationId($$orgId);

        return "Use organizationId $orgIdKey => $orgId and orderId $orderIdKey => $orderId";
    }

}