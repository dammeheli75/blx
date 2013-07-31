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

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $translator = $serviceManager->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        // Set layouts
        $e->getApplication()
            ->getEventManager()
            ->getSharedManager()
            ->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function ($e)
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
