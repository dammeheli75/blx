<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $translator = $serviceManager->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        // Set Global Adapter for TableGateway
        GlobalAdapterFeature::setStaticAdapter($serviceManager->get('db'));
        
        // Check authentication
        $e->getApplication()
            ->getEventManager()
            ->getSharedManager()
            ->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, function ($e)
        {
            $serviceManager = $e->getApplication()
                ->getServiceManager();
            $auth = $serviceManager->get('auth');
            $controller = $e->getTarget();
            $controllerClass = get_class($controller);
            $controllerClassPart = explode('\\', get_class($controller));
            $module = $controllerClassPart[0];
            
            // set controller, layout variables
            $controller->auth = $controller->layout()->auth = $auth;
            $controller->layout()->controllerClass = $controllerClass;
            
            // Check access
            if ($module == 'Administrator' && $controllerClassPart[2] !== 'AuthenticationController') {
                if (! $auth->hasIdentity()) {
                    $controller->redirect()
                        ->toRoute('administrator/authentication/login');
                }
            }
        }, 200);
        
        // Set layouts
        $e->getApplication()
            ->getEventManager()
            ->getSharedManager()
            ->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, function ($e)
        {
            $serviceManager = $e->getApplication()
                ->getServiceManager();
            
            $controller = $e->getTarget();
            $controllerClass = explode('\\', get_class($controller));
            $config = $serviceManager->get('config');
            $config = $config['layout_settings'];
            
            // Set layout for Modules
            $moduleNamespace = $controllerClass[0];
            
            if (isset($config['modules'][$moduleNamespace])) {
                $controller->layout($config['modules'][$moduleNamespace]);
            }
            // Set layout for controllers
            if (isset($config['controllers'][$moduleNamespace . '\\' . $controllerClass[1]])) {
                $controller->layout($config['controllers'][$moduleNamespace . '\\' . $controllerClass[1]]);
            }
        }, 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
}
