<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2015 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Module options.
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class ModuleOptions extends AbstractOptions
{
    /**
     * Verbosity level of the log.
     *
     * Each higher level includes all of the lower levels.
     *
     * FALSE: disabled
     *
     * 0; EMERG (very rarely used)
     * 1: ALERT (very rarely used)
     * 2: CRIT
     * 3: ERR
     * 4: WARN
     * 5: NOTICE
     * 6: INFO
     * 7: DEBUG
     *
     * @var bool|int
     */
    protected $logLevel = false;

    /**
     * Login name of the user, who is used for XING authentication.
     *
     * @var String
     */
    protected $authorizedUser;

    /**
     * the preview indicates, that a job is not shown
     * @var bool
     */
    protected $apiPreview = True;

    /**
     * if no orderId was set, return the orderId of the sandbox
     *
     * @var string
     */
    protected $orderId = 968180;

    /**
     * Sets the API Preview flag.
     *
     * @param boolean $apiPreview
     *
     * @return self
     */
    public function setApiPreview($apiPreview)
    {
        $this->apiPreview = $apiPreview;

        return $this;
    }

    /**
     * Gets the api preview flag.
     *
     * @return boolean
     */
    public function getApiPreview()
    {
        return $this->apiPreview;
    }

    /**
     * Sets the login name of the authorized user.
     *
     * @param String $authorizedUser
     *
     * @return self
     */
    public function setAuthorizedUser($authorizedUser)
    {
        $this->authorizedUser = $authorizedUser;

        return $this;
    }

    /**
     * Gets the login name of the authorized user.
     *
     * @return String
     */
    public function getAuthorizedUser()
    {
        return $this->authorizedUser;
    }

    /**
     * @param bool|int $level
     *
     * @return self
     */
    public function setLogLevel($level)
    {
        if (is_numeric($level)) {
            /*
             * Assures value is between 0 and 7,
             * the max handles values lower than zero, the min handles values higher than 7.
             */
            $level = min(max($level, 0), 7);
        } else {
            $level = false;
        }

        $this->logLevel = $level;

        return $this;
    }

    /**
     * Gets the log level.
     *
     * @return bool|int
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * Sets the XING order id.
     *
     * @param string $orderId
     *
     * @return self
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Gets the XING order id.
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }
}