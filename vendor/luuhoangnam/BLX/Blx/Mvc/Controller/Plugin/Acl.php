<?php
namespace Blx\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Acl extends AbstractPlugin
{

    public function isAllowed($resource, $privilege)
    {
        $serviceManager = $this->getController()
            ->getEvent()
            ->getApplication()
            ->getServiceManager();
        
        $currentUser = $serviceManager->get('userManager')->getCurrentUser();
        
        return $currentUser->isAllowed($resource, $privilege);
    }
}