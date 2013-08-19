<?php
namespace Application\View\Helper\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\View\Helper\Acl;

class AclFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();
        $helper = new Acl();
        if ($services->has('acl')) {
            $helper->setServiceLocator($serviceLocator);
        }
        return $helper;
    }
}