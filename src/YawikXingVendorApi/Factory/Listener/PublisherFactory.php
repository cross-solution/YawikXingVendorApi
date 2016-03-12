<?php
/**
 * YAWIK
 * 
 * @filesource
 * @copyright (c) 2013 - 2016 Cross Solution (http://cross-solution.de)
 * @license   MIT
 * @author    weitz@cross-solution.de
 */

namespace YawikXingVendorApi\Factory\Listener;

use YawikXingVendorApi\Listener\Publisher;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class RegisterFactory
 * @package Registration\Factory\Controller
 */
class PublisherFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RegistrationController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options \YawikXingVendorApi\Options\ModuleOptions */
        $options = $serviceLocator->get('YawikXingVendorApi/ModuleOptions');

        $createWorkercallback = function() use ($serviceLocator) {
            $worker = $serviceLocator->get('YawikXingVendorApi/Listener/PublisherWorker');

            return $worker;
        };

        $publisher = new Publisher($createWorkercallback);

        $logLevel = $options->getLogLevel();

        if (false !== $logLevel) {
            $log = $serviceLocator->get('Log/YawikXingVendorApi/Publisher');

            if (7 != $logLevel) {
                foreach ($log->getWriters() as $writer) {
                    $writer->addFilter($logLevel);
                }
            }

            $publisher->setLogger($log);
        }

        return $publisher;
    }
}