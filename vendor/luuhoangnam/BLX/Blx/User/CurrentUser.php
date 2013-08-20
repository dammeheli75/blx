<?php
namespace Blx\User;

use Zend\ServiceManager\ServiceLocatorInterface;
use Administrator\Model\User;

class CurrentUser
{

    protected $serviceManager;

    protected $acl;

    protected $auth;

    protected $userInfo;

    public function __construct(ServiceLocatorInterface $sm = null)
    {
        if ($sm) {
            $this->serviceManager = $sm;
        } else {
            $this->serviceManager = UserManager::getStaticServiceManager();
        }
        
        $this->acl = $this->serviceManager->get('acl');
        $this->auth = $this->serviceManager->get('auth');
    }

    public function isLogged()
    {
        if ($this->auth->hasIdentity())
            return true;
        return false;
    }

    public function isGuest()
    {
        if ($this->isLogged())
            return false;
        return true;
    }

    public function getInfo()
    {
        if (! $this->userInfo) {
            $userModel = new User();
            $this->userInfo = $userModel->cache->getUser(array(
                'email' => $this->auth->getIdentity()
            ));
        }
        return $this->userInfo;
    }

    public function isAllowed($resource, $privilege)
    {
        if ($this->isLogged()) {
            $userInfo = $this->getInfo();
        } else {
            $userInfo = array(
                'group_id' => 'guest'
            );
        }
        
        return ($this->acl->hasRole("user_group_{$userInfo['group_id']}") && $this->acl->hasResource($resource) && $this->acl->isAllowed("user_group_{$userInfo['group_id']}", $resource, $privilege));
    }
}