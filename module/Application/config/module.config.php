<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action,
            'fixture' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/lich-thi',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Fixture',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/:action]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            ),
            'result' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/ket-qua-thi',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Result',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/:action]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            ),
            'document' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/tai-lieu-on-thi',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Document',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/:action]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            ),
            'guiding' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/huong-dan-di-thi-bang-lai-xe',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Guiding',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/:action]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            ),
            'news' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/tin-tuc',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Post',
                        'action' => 'index',
                        'page' => 1
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'paginator' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/trang-:page',
                            'constraints' => array(
                                'page' => '\d+'
                            ),
                            'defaults' => array(
                                'action' => 'index',
                                'page' => 1
                            )
                        )
                    ),
                    'category' => array(
                        'type' => 'Regex',
                        'options' => array(
                            'regex' => '/(?<category_slug>(?!trang-\d+$)[a-zA-Z0-9_-]+)',
                            'defaults' => array(
                                'action' => 'index',
                                'page' => 1
                            ),
                            'spec' => '/%category_slug%'
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'paginator' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/trang-:page',
                                    'constraints' => array(
                                        'page' => '\d+'
                                    ),
                                    'defaults' => array(
                                        'page' => 1
                                    )
                                )
                            )
                        )
                    ),
                    'detail' => array(
                        'type' => 'Regex',
                        'options' => array(
                            'regex' => '/(?<category_slug>[a-zA-Z0-9_-]+)/(?<post_slug>[a-zA-Z0-9_-]+)\.(?<post_id>\d+)\.html',
                            'defaults' => array(
                                'action' => 'detail'
                            ),
                            'spec' => '/%category_slug%/%post_slug%.%post_id%.html'
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'db' => 'Blx\Db\DbServiceFactory',
            'auth' => 'Blx\Authentication\AuthenticationServiceFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
        )
    ),
    'translator' => array(
        'locale' => 'vi_VN',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo'
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Fixture' => 'Application\Controller\FixtureController',
            'Application\Controller\Result' => 'Application\Controller\ResultController',
            'Application\Controller\Document' => 'Application\Controller\DocumentController',
            'Application\Controller\Guiding' => 'Application\Controller\GuidingController',
            'Application\Controller\Post' => 'Application\Controller\PostController',
            'Application\Controller\Sitemap' => 'Application\Controller\SitemapController'
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => MODULE_PATH . DS . 'Application' . DS . 'view' . DS . 'layout' . DS . 'layout.phtml',
            'layout/administrator' => MODULE_PATH . DS . 'Application' . DS . 'view' . DS . 'layout' . DS . 'administrator.phtml',
            'layout/administrator/login' => MODULE_PATH . DS . 'Application' . DS . 'view' . DS . 'layout' . DS . 'login.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'layout_settings' => array(
        'modules' => array(
            'Administrator' => 'layout/administrator',
            'Application' => 'layout/layout'
        ),
        'controllers' => array()
    )
);
