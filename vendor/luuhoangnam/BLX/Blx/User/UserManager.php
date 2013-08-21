<?php
namespace Blx\User;

use Zend\ServiceManager\ServiceLocatorInterface;

class UserManager
{

    const ADMINISTRATOR_GROUP = 2;

    const MANAGER_GROUP = 5;

    const COLLABORATOR_GROUP = 6;

    public static $serviceManager;

    public static function setStaticServiceManager(ServiceLocatorInterface $sm)
    {
        self::$serviceManager = $sm;
    }

    public static function getStaticServiceManager()
    {
        if (! (self::$serviceManager instanceof ServiceLocatorInterface)) {
            throw new \Exception('Must set static serviceManager for userManager');
        }
        return self::$serviceManager;
    }

    public function getCurrentUser()
    {
        return new CurrentUser();
    }
}