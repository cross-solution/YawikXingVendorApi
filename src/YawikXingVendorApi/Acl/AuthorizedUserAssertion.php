<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2016 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace YawikXingVendorApi\Acl;

use Auth\Entity\UserInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class AuthorizedUserAssertion implements AssertionInterface
{

    protected $authorizedUserLogin;
    protected $authenticationService;

    public function __construct($authorizedUserLogin, $authService)
    {
        $this->authorizedUserLogin = $authorizedUserLogin;
        $this->authenticationService = $authService;
    }

    /**
     * Returns true if and only if the assertion conditions are met
     *
     * This method is passed the ACL, Role, Resource, and privilege to which the authorization query applies. If the
     * $role, $resource, or $privilege parameters are null, it means that the query applies to all Roles, Resources, or
     * privileges, respectively.
     *
     * @param  Acl               $acl
     * @param  RoleInterface     $role
     * @param  ResourceInterface $resource
     * @param  string            $privilege
     *
     * @return bool
     */
    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        if ($role instanceOf UserInterface) {
            return $this->authorizedUserLogin == $role->getLogin();

        } else if ($this->authenticationService->hasIdentity()) {
            $user = $this->authenticationService->getUser();
            return $this->authorizedUserLogin == $user->getLogin();

        } else {
            return false;
        }
    }
}