<?php
namespace Blx\User;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserManagerServiceFactory implements FactoryInterface
{
    /*
     * (non-PHPdoc) @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	return new UserManager();
    }
}