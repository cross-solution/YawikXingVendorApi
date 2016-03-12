<?php
/**
 * YAWIK
 * 
 * @filesource
 * @copyright (c) 2013 - 2016 Cross Solution (http://cross-solution.de)
 * @license   MIT
 * @author    weitz@cross-solution.de
 */

namespace YawikXingVendorApi;

use Zend\Mvc\MvcEvent;
use Core\ModuleManager\ModuleConfigLoader;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;

/**
 * GeoApi
 *
 */
class Module implements DependencyIndicatorInterface
{
    /**
     * Loads module specific configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        return ModuleConfigLoader::load(__DIR__ . '/config');
    }

    /**
     * Loads module specific autoloader configuration.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getModuleDependencies()
    {
        return array('Jobs', 'Auth');
    }

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $services     = $e->getApplication()->getServiceManager();
        $jobEvents = $services->get('Jobs/Events');
        $publishListener = $services->get('YawikXingVendorApi/Listener');

        $publishListener->attach($jobEvents);
    }
}