<?php
namespace Blx\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Acl extends AbstractPlugin
{

    public function isAllowed($resource, $privilege)
    {
        $controller = $this->getController();
        $serviceManager = $controller->getEvent()
            ->getApplication()
            ->getServiceManager();
        $acl = $serviceManager->get('acl');
        
        $currentUser = (isset($controller->currentUser)) ? $controller->currentUser : 'guest';
        return ($acl->hasRole("user_group_{$currentUser['group_id']}") && $acl->hasResource($resource) && $acl->isAllowed("user_group_{$currentUser['group_id']}", $resource, $privilege));
    }
}