<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManager;

class Acl extends AbstractHelper
{

    /**
     * Service Locator
     *
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * Setter for $serviceLocator
     *
     * @param ServiceManager $serviceLocator            
     */
    public function setServiceLocator(ServiceManager $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
    }

    public function __invoke()
    {
        return $this;
    }

    public function isAllowed($resource, $privilege)
    {
        $view = $this->getView();
        echo '<pre>';
        print_r($this->serviceManager);
        echo '</pre>';
        
        $acl = $this->serviceManager->get('acl');
        
        $currentUser = (isset($view->currentUser)) ? $view->currentUser : 'guest';
        return ($acl->hasRole("user_group_{$currentUser['group_id']}") && $acl->hasResource($resource) && $acl->isAllowed("user_group_{$currentUser['group_id']}", $resource, $privilege));
    }
}