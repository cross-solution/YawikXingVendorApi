<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Factory\Listener;

use Core\Listener\DeferredListenerAggregate;
use Jobs\Listener\Events\JobEvent;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class DeferredPublisherFactory implements FactoryInterface
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
        $listener = $serviceLocator->get('Core/Listener/DeferredListenerAggregate');
        $listener->setHook(
             JobEvent::EVENT_JOB_ACCEPTED,
             'YawikXingVendorApi/Listener/Publisher',
             'postJob'
        );

        return $listener;
    }


}