<?php
namespace Blx\User;

use Zend\ServiceManager\ServiceLocatorInterface;
use Administrator\Model\User;

class CurrentUser
{

    protected $serviceManager;

    protected $acl;

    protected $auth;

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

    public function getInfo()
    {
        $userModel = new User();
        return $userModel->cache->getUser(array(
            'email' => $this->auth->getIdentity()
        ));
    }
}