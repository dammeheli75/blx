<?php
namespace Blx\Authentication;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable;
use Blx\Authentication\Storage\Session;

class AuthenticationServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Configure the translator
        $config = $serviceLocator->get('Config');
        
        // Authentication Service
        $auth = new AuthenticationService();
        
        // Authentication Storage
        $storage = new Session('Authentication');
        $auth->setStorage($storage);
        
        // Authentication Adapter
        $authAdapter = new DbTable($serviceLocator->get('db'));
        $authAdapter->setTableName('users')
            ->setIdentityColumn('email')
            ->setCredentialColumn('password')
            ->setCredentialTreatment('MD5(?)');
        $auth->setAdapter($authAdapter);
        
        return $auth;
    }
}