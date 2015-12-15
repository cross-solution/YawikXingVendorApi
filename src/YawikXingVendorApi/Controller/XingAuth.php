<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2015 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class XingAuth extends AbstractActionController
{

    public function indexAction()
    {
        $log = $this->getServiceLocator()->get('Log/YawikXingVendorApi/Publisher');
        $log->info('Muahaha?');

        $do = $this->params()->fromQuery('do');

        if ($do) {
            $method = "do$do";
            if (method_exists($this, $method)) {
                $this->$method();
            }
        }

        // only view script rendering
        return array(
            'do' => $do,
        );
    }

    protected function doEnable()
    {
        $services = $this->getServiceLocator();
        $returnUri = $this->getRequest()->getRequestUri();
        $hybridAuth = $services->get('HybridAuth');

        $hybridAuth->authenticate('XingVendorApi', array('hauth_return_to' => $returnUri));

        $sessionData = $hybridAuth->getSessionData();
        $auth = $services->get('AuthenticationService');
        $user = $auth->getUser();

        $user->updateAuthSession('XingVendorApi', $sessionData);
    }

    protected function doDisable()
    {
        $services = $this->getServiceLocator();
        $auth     = $services->get('AuthenticationService');
        $user     = $auth->getUser();

        $user->removeSessionData('XingVendorApi');
    }
    
}