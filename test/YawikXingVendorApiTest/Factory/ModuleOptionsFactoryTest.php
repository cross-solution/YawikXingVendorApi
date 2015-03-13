<?php
/**
 * YAWIK
 * 
 * @filesource
 * @copyright (c) 2013-2015 Cross Solution (http://cross-solution.de)
 * @license   MIT
 * @author    weitz@cross-solution.de
 */

namespace YawikXingVendorApi\Factory;

use Zend\ServiceManager\ServiceManager;
use YawikXingVendorApi\Factory\Listener\PublisherFactory;
use YawikXingVendorApi\Options\ListenerPublisherOptions;

class ModuleOptionsFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function providerTestFactory()
    {
        return array(
            array()
        );
    }

    /**
     * Test One
     *
     * @dataProvider providerTestFactory
     */
    public function testFactory($config)
    {
        $serviceManager = new ServiceManager;
        $serviceManager->setService('Config', $config);

        $factory = new PublisherFactory;
        $object = $factory->createService($serviceManager);

        $this->assertInstanceOf('YawikXingVendorApi\Options\ListenerPublisherOptions', $object);
    }

}

