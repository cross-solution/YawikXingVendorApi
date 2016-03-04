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

use Jobs\Entity\JobInterface;
use YawikXingVendorApi\Entity\XingData;
use YawikXingVendorApi\Options\ModuleOptions;
use Zend\Log\Logger;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class XingFilterData 
{

    /**
     * The Xing data object
     *
     * @var XingData
     */
    protected $xingData;

    /**
     * Module options.
     *
     * @var ModuleOptions
     */
    protected $options;

    /**
     * Additional data from the ACCEPT event
     *
     * @var array
     */
    protected $data = [];

    /**
     * The job entity.
     *
     * @var JobInterface
     */
    protected $job;

    /**
     * The logger
     *
     * @var Logger
     */
    protected $logger;


    public function __construct(
        XingData $xingData = null,
        ModuleOptions $options = null,
        array $data = [],
        JobInterface $job = null,
        Logger $logger = null)
    {
        if (null !== $xingData) {
            $this->setXingData($xingData);
        }

        if (null !== $options) {
            $this->setOptions($options);
        }

        if (!empty($data)) {
            $this->setData($data);
        }

        if (null !== $job) {
            $this->setJob($job);
        }

        if (null !== $logger) {
            $this->setLogger($logger);
        }
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \Jobs\Entity\JobInterface $job
     *
     * @return self
     */
    public function setJob(JobInterface $job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return \Jobs\Entity\JobInterface
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param \Zend\Log\Logger $logger
     *
     * @return self
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return \Zend\Log\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param \YawikXingVendorApi\Options\ModuleOptions $options
     *
     * @return self
     */
    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return \YawikXingVendorApi\Options\ModuleOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param \YawikXingVendorApi\Entity\XingData $xingData
     *
     * @return self
     */
    public function setXingData(XingData $xingData)
    {
        $this->xingData = $xingData;

        return $this;
    }

    /**
     * @return \YawikXingVendorApi\Entity\XingData
     */
    public function getXingData()
    {
        return $this->xingData;
    }


}