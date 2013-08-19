<?php
namespace Blx\User;

use Zend\ServiceManager\ServiceLocatorInterface;

class UserManager
{

    public static $serviceManager;

    public static function setStaticServiceManager(ServiceLocatorInterface $sm)
    {
        self::$serviceManager = $sm;
    }

    public static function getStaticServiceManager()
    {
        return self::$serviceManager;
    }
    
    public function getCurrentUser()
    {
    	return new CurrentUser();
    }
}