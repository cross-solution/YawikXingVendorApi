<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2015 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Factory\Listener;

use YawikXingVendorApi\Listener\PublisherWorker;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class PublisherWorkerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('YawikXingVendorApi/ModuleOptions');
        $hybridAuth = $serviceLocator->get('HybridAuth');
        $users = $serviceLocator->get('repositories')->get('Auth/User');
        $user = $users->findByLogin($options->getAuthorizedUser());

        $worker = new PublisherWorker($hybridAuth, $user, $options);

        $logLevel = $options->getLogLevel();

        if (false !== $logLevel) {
            $log = $serviceLocator->get('Log/YawikXingVendorApi/Publisher');

            if (7 != $logLevel) {
                foreach ($log->getWriters() as $writer) {
                    $writer->addFilter($logLevel);
                }
            }

            $worker->setLogger($log);
        }

        return $worker;
    }
}