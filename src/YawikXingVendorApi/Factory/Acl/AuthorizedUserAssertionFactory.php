<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2015 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Factory\Acl;

use YawikXingVendorApi\Acl\AuthorizedUserAssertion;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class AuthorizedUserAssertionFactory implements FactoryInterface
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
        $services = $serviceLocator->getServiceLocator();
        $options  = $services->get('YawikXingVendorApi/ModuleOptions');
        $authUserLogin = $options->getAuthorizedUser();
        $auth     = $services->get('AuthenticationService');

        $assertion = new AuthorizedUserAssertion($authUserLogin, $auth);

        return $assertion;
    }


}