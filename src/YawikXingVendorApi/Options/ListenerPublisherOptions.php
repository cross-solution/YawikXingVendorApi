<?php
/**
 * YAWIK
 * 
 * @filesource
 * @copyright (c) 2013-2015 Cross Solution (http://cross-solution.de)
 * @license   MIT
 * @author    weitz@cross-solution.de
 */

namespace YawikXingVendorApi\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class ListenerPublisherOptions
 * @package YawikXingVendorApi\Options
 */
class ListenerPublisherOptions extends AbstractOptions {

    /**
     * the preview indicates, that a job is not shown
     * @var bool
     */
    protected $apiPreview = True;

    /**
     * if no organizationId was set, return the id of the Sandbox
     * Jobs in the sandbox can not be inspected in the backend, their
     * only function is to get a negative or positive answer from the transmit
     * @var string
     */
    protected $orderId = 968180;

    /**
     * if no orderId was set, return the orderId of the sandbox
     * @var string
     */
    protected $organizationId = 5160;

    /**
     * name of the User, that provides the authority to XING
     * this has has a stored session and maintain this session
     * @var string
     */
    protected $authority = '';

    /**
     * @param $preview bool
     * @return $this
     */
    public function setApiPreview($preview)
    {
        $this->apiPreview = $preview;
        return $this;
    }

    /**
     * @return bool
     */
    public function getApiPreview()
    {
        return $this->apiPreview;
    }

    /**
     * @param $orderid
     * @return $this
     */
    public function setOrderId($orderid)
    {
        $this->orderId = $orderid;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param $organizationId
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;
    }

    /**
     * @return string
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @param $authority string
     */
    public function setAuthority($authority)
    {
        $this->authority = $authority;
        return $this;
    }

    public function getAuthority()
    {
        return $this->authority;
    }

}