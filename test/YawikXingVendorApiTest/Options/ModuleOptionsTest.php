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

use YawikXingVendorApi\Options\ModuleOptions as Options;

class YawikXingVendorApi extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Options $options
     */
    protected $options;

    public function setUp()
    {
        $options = new Options;
        $this->options = $options;
    }

    /**
     * @covers YawikXingVendorApi\Options\ModuleOptions::getApiPreview
     * @covers YawikXingVendorApi\Options\ModuleOptions::setApiPreview
     */
    public function testSetGetApiPreview()
    {
        $this->assertTrue($this->options->getApiPreview());
        $this->options->setApiPreview(False);
        $this->assertEquals(False, $this->options->getApiPreview());
    }

    /**
     * @covers YawikXingVendorApi\Options\ModuleOptions::getOrderId
     * @covers YawikXingVendorApi\Options\ModuleOptions::setOrderId
     */
    public function testSetGetOrderId()
    {
        $this->assertEquals(968180, $this->options->getOrderId());
        $this->options->setOrderId(968181);
        $this->assertEquals(968181, $this->options->getOrderId());
    }

    /**
     * @covers YawikXingVendorApi\Options\ModuleOptions::getOrganizationId
     * @covers YawikXingVendorApi\Options\ModuleOptions::setOrganizationId
     */
    public function testSetGetOrganizationId()
    {
        $this->assertEquals(null, $this->options->getOrganizationId());
        $this->options->setOrganizationId(7);
        $this->assertEquals(7, $this->options->getOrganizationId());
    }

    public function testSetGetLoglevel()
    {
        $this->assertFalse($this->options->getLogLevel());
        $this->options->setLogLevel(2);
        $this->assertEquals(2, $this->options->getLogLevel());
        $this->options->setLogLevel('2');
        $this->assertEquals(2, $this->options->getLogLevel());
        $this->options->setLogLevel(18);
        $this->assertEquals(7, $this->options->getLogLevel());
        $this->options->setLogLevel('not2Num');
        $this->assertFalse($this->options->getLogLevel());
    }
}
