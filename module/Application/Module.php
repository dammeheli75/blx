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
use Blx\Cache\StorageFactory;
use Blx\User\UserManager;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $translator = $serviceManager->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        // Set base Url
        $router = $serviceManager->get('router');
        $router->setBaseUrl(BASE_URL);
        
        // Set cache for translator
        $translator->setCache(StorageFactory::factory(array(
            'namespace' => 'Translator',
            'ttl' => 1
        )));
        
        // Set Global Adapter for TableGateway
        GlobalAdapterFeature::setStaticAdapter($serviceManager->get('db'));
        UserManager::setStaticServiceManager($serviceManager);
        
        // Set default timezone
        // @date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        // Check authentication
        $e->getApplication()
            ->getEventManager()
            ->getSharedManager()
            ->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, function ($e)
        {
            $serviceManager = $e->getApplication()
                ->getServiceManager();
            $auth = $serviceManager->get('auth');
            $userManager = $serviceManager->get('userManager');
            $controller = $e->getTarget();
            $controllerClass = get_class($controller);
            $controllerClassPart = explode('\\', get_class($controller));
            $module = $controllerClassPart[0];
            $config = $serviceManager->get('config');
            $config = $config['layout_settings'];
            
            // set controller, layout variables
            $controller->auth = $controller->layout()->auth = $auth;
            // $controller->acl = $controller->layout()->acl = $serviceManager->get('acl');
            $controller->layout()->controllerClass = $controllerClass;
            
            // Check access
            if ($module == 'Administrator' && $controllerClassPart[2] !== 'AuthenticationController') {
                $currentUser = $userManager->getCurrentUser();
                if (! $currentUser->isLogged()) {
                    $controller->redirect()
                        ->toRoute('administrator/authentication/login');
                } else {
                    $controller->layout()->currentUser = $controller->currentUser = $currentUser->getInfo();
                }
            }
            
            // Set layout for Modules
            if (isset($config['modules'][$module])) {
                $controller->layout($config['modules'][$module]);
            }
            // Set layout for controllers
            if (isset($config['controllers'][$module . '\\' . $controllerClassPart[1]])) {
                $controller->layout($config['controllers'][$module . '\\' . $controllerClassPart[1]]);
            }
        }, 200);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
}
