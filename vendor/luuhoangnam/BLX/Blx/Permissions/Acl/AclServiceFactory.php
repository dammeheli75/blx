<?php
namespace Blx\Permissions\Acl;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Permissions\Acl\Acl;

class AclServiceFactory implements FactoryInterface
{

    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Get Acl config from database
        
        
        $acl = new Acl();
    }
}