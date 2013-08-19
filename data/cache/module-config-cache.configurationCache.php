<?php
return array (
  'router' => 
  array (
    'routes' => 
    array (
      'home' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/',
          'defaults' => 
          array (
            'controller' => 'Application\\Controller\\Index',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/[:controller[/:action]]',
              'constraints' => 
              array (
                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
              ),
              'defaults' => 
              array (
              ),
            ),
          ),
        ),
      ),
      'fixture' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/lich-thi',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Fixture',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '[/:action]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
              ),
              'defaults' => 
              array (
              ),
            ),
          ),
        ),
      ),
      'result' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/ket-qua-thi',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Result',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '[/:action]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
              ),
              'defaults' => 
              array (
              ),
            ),
          ),
        ),
      ),
      'document' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/tai-lieu-on-thi',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Document',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '[/:action]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
              ),
              'defaults' => 
              array (
              ),
            ),
          ),
        ),
      ),
      'guiding' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/huong-dan-di-thi-bang-lai-xe',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Guiding',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '[/:action]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
              ),
              'defaults' => 
              array (
              ),
            ),
          ),
        ),
      ),
      'news' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/tin-tuc',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Post',
            'action' => 'index',
            'page' => 1,
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'paginator' => 
          array (
            'type' => 'segment',
            'options' => 
            array (
              'route' => '/trang-:page',
              'constraints' => 
              array (
                'page' => '\\d+',
              ),
              'defaults' => 
              array (
                'action' => 'index',
                'page' => 1,
              ),
            ),
          ),
          'category' => 
          array (
            'type' => 'Regex',
            'options' => 
            array (
              'regex' => '/(?<category_slug>(?!trang-\\d+$)[a-zA-Z0-9_-]+)',
              'defaults' => 
              array (
                'action' => 'index',
                'page' => 1,
              ),
              'spec' => '/%category_slug%',
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'paginator' => 
              array (
                'type' => 'segment',
                'options' => 
                array (
                  'route' => '/trang-:page',
                  'constraints' => 
                  array (
                    'page' => '\\d+',
                  ),
                  'defaults' => 
                  array (
                    'page' => 1,
                  ),
                ),
              ),
            ),
          ),
          'detail' => 
          array (
            'type' => 'Regex',
            'options' => 
            array (
              'regex' => '/(?<category_slug>[a-zA-Z0-9_-]+)/(?<post_slug>[a-zA-Z0-9_-]+)\\.(?<post_id>\\d+)\\.html',
              'defaults' => 
              array (
                'action' => 'detail',
              ),
              'spec' => '/%category_slug%/%post_slug%.%post_id%.html',
            ),
          ),
        ),
      ),
      'administrator' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/administrator',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Administrator\\Controller',
            'controller' => 'Index',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/[:controller[/:action]]',
              'constraints' => 
              array (
                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
              ),
              'defaults' => 
              array (
              ),
            ),
          ),
          'profiles' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/profiles',
              'defaults' => 
              array (
                '__NAMESPACE__' => 'Administrator\\Controller',
                'controller' => 'Profile',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'default' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '[/:action]',
                  'constraints' => 
                  array (
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  ),
                  'defaults' => 
                  array (
                  ),
                ),
              ),
            ),
          ),
          'users' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/users',
              'defaults' => 
              array (
                '__NAMESPACE__' => 'Administrator\\Controller',
                'controller' => 'User',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'default' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '[/:action]',
                  'constraints' => 
                  array (
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  ),
                  'defaults' => 
                  array (
                  ),
                ),
              ),
            ),
          ),
          'user-groups' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/user-groups',
              'defaults' => 
              array (
                '__NAMESPACE__' => 'Administrator\\Controller',
                'controller' => 'UserGroup',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'default' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '[/:action]',
                  'constraints' => 
                  array (
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  ),
                  'defaults' => 
                  array (
                  ),
                ),
              ),
            ),
          ),
          'system' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/system',
              'defaults' => 
              array (
                '__NAMESPACE__' => 'Administrator\\Controller',
                'controller' => 'System',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'default' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '[/:action]',
                  'constraints' => 
                  array (
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  ),
                  'defaults' => 
                  array (
                  ),
                ),
              ),
            ),
          ),
          'categories' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/categories',
              'defaults' => 
              array (
                '__NAMESPACE__' => 'Administrator\\Controller',
                'controller' => 'Category',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'default' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '[/:action]',
                  'constraints' => 
                  array (
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  ),
                  'defaults' => 
                  array (
                  ),
                ),
              ),
            ),
          ),
          'posts' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/posts',
              'defaults' => 
              array (
                '__NAMESPACE__' => 'Administrator\\Controller',
                'controller' => 'Post',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'default' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '[/:action]',
                  'constraints' => 
                  array (
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  ),
                  'defaults' => 
                  array (
                  ),
                ),
              ),
              'edit' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/edit[/:post_id]',
                  'constraints' => 
                  array (
                    'post_id' => '\\d*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'edit',
                  ),
                ),
              ),
            ),
          ),
          'venues' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/venues',
              'defaults' => 
              array (
                '__NAMESPACE__' => 'Administrator\\Controller',
                'controller' => 'Venue',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'default' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '[/:action]',
                  'constraints' => 
                  array (
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                  ),
                  'defaults' => 
                  array (
                  ),
                ),
              ),
            ),
          ),
          'authentication' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/authentication',
              'defaults' => 
              array (
                '__NAMESPACE__' => 'Administrator\\Controller',
                'controller' => 'Authentication',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'login' => 
              array (
                'type' => 'Literal',
                'options' => 
                array (
                  'route' => '/login',
                  'defaults' => 
                  array (
                    '__NAMESPACE__' => 'Administrator\\Controller',
                    'controller' => 'Authentication',
                    'action' => 'login',
                  ),
                ),
              ),
              'logout' => 
              array (
                'type' => 'Literal',
                'options' => 
                array (
                  'route' => '/logout',
                  'defaults' => 
                  array (
                    '__NAMESPACE__' => 'Administrator\\Controller',
                    'controller' => 'Authentication',
                    'action' => 'logout',
                  ),
                ),
              ),
              'authenticate' => 
              array (
                'type' => 'Literal',
                'options' => 
                array (
                  'route' => '/authenticate',
                  'defaults' => 
                  array (
                    '__NAMESPACE__' => 'Administrator\\Controller',
                    'controller' => 'Authentication',
                    'action' => 'authenticate',
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    ),
  ),
  'service_manager' => 
  array (
    'factories' => 
    array (
      'translator' => 'Zend\\I18n\\Translator\\TranslatorServiceFactory',
      'db' => 'Blx\\Db\\DbServiceFactory',
      'auth' => 'Blx\\Authentication\\AuthenticationServiceFactory',
      'navigation' => 'Zend\\Navigation\\Service\\DefaultNavigationFactory',
    ),
  ),
  'translator' => 
  array (
    'locale' => 'vi_VN',
    'translation_file_patterns' => 
    array (
      0 => 
      array (
        'type' => 'gettext',
        'base_dir' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/config/../language',
        'pattern' => '%s.mo',
      ),
    ),
  ),
  'controllers' => 
  array (
    'invokables' => 
    array (
      'Application\\Controller\\Index' => 'Application\\Controller\\IndexController',
      'Application\\Controller\\Fixture' => 'Application\\Controller\\FixtureController',
      'Application\\Controller\\Result' => 'Application\\Controller\\ResultController',
      'Application\\Controller\\Document' => 'Application\\Controller\\DocumentController',
      'Application\\Controller\\Guiding' => 'Application\\Controller\\GuidingController',
      'Application\\Controller\\Post' => 'Application\\Controller\\PostController',
      'Application\\Controller\\Sitemap' => 'Application\\Controller\\SitemapController',
      'Administrator\\Controller\\Index' => 'Administrator\\Controller\\IndexController',
      'Administrator\\Controller\\Profile' => 'Administrator\\Controller\\ProfileController',
      'Administrator\\Controller\\User' => 'Administrator\\Controller\\UserController',
      'Administrator\\Controller\\UserGroup' => 'Administrator\\Controller\\UserGroupController',
      'Administrator\\Controller\\System' => 'Administrator\\Controller\\SystemController',
      'Administrator\\Controller\\Authentication' => 'Administrator\\Controller\\AuthenticationController',
      'Administrator\\Controller\\Collaborator' => 'Administrator\\Controller\\CollaboratorController',
      'Administrator\\Controller\\Venue' => 'Administrator\\Controller\\VenueController',
      'Administrator\\Controller\\Category' => 'Administrator\\Controller\\CategoryController',
      'Administrator\\Controller\\Post' => 'Administrator\\Controller\\PostController',
    ),
  ),
  'view_manager' => 
  array (
    'display_not_found_reason' => true,
    'display_exceptions' => true,
    'doctype' => 'HTML5',
    'not_found_template' => 'error/404',
    'exception_template' => 'error/index',
    'template_map' => 
    array (
      'administrator/authentication/authenticate' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/authentication/authenticate.phtml',
      'administrator/authentication/login' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/authentication/login.phtml',
      'administrator/authentication/logout' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/authentication/logout.phtml',
      'administrator/category/create' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/category/create.phtml',
      'administrator/category/destroy' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/category/destroy.phtml',
      'administrator/category/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/category/index.phtml',
      'administrator/category/read' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/category/read.phtml',
      'administrator/category/update' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/category/update.phtml',
      'administrator/collaborator/create' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/collaborator/create.phtml',
      'administrator/collaborator/destroy' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/collaborator/destroy.phtml',
      'administrator/collaborator/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/collaborator/index.phtml',
      'administrator/collaborator/read' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/collaborator/read.phtml',
      'administrator/collaborator/update' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/collaborator/update.phtml',
      'administrator/index/foo' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/index/foo.phtml',
      'administrator/index/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/index/index.phtml',
      'administrator/post/add' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/post/add.phtml',
      'administrator/post/destroy' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/post/destroy.phtml',
      'administrator/post/edit' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/post/edit.phtml',
      'administrator/post/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/post/index.phtml',
      'administrator/post/read' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/post/read.phtml',
      'administrator/post/save-add' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/post/save-add.phtml',
      'administrator/post/save-edit' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/post/save-edit.phtml',
      'administrator/profile/create' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/profile/create.phtml',
      'administrator/profile/destroy' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/profile/destroy.phtml',
      'administrator/profile/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/profile/index.phtml',
      'administrator/profile/read' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/profile/read.phtml',
      'administrator/profile/update' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/profile/update.phtml',
      'administrator/system/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/system/index.phtml',
      'administrator/user/create' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/user/create.phtml',
      'administrator/user/destroy' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/user/destroy.phtml',
      'administrator/user/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/user/index.phtml',
      'administrator/user/read' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/user/read.phtml',
      'administrator/user/update' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/user/update.phtml',
      'administrator/user-group/create' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/user-group/create.phtml',
      'administrator/user-group/destroy' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/user-group/destroy.phtml',
      'administrator/user-group/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/user-group/index.phtml',
      'administrator/user-group/read' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/user-group/read.phtml',
      'administrator/user-group/update' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/user-group/update.phtml',
      'administrator/venue/create' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/venue/create.phtml',
      'administrator/venue/destroy' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/venue/destroy.phtml',
      'administrator/venue/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/venue/index.phtml',
      'administrator/venue/read' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/venue/read.phtml',
      'administrator/venue/update' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/view/administrator/venue/update.phtml',
      'application/document/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/application/document/index.phtml',
      'application/fixture/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/application/fixture/index.phtml',
      'application/fixture/read' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/application/fixture/read.phtml',
      'application/guiding/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/application/guiding/index.phtml',
      'application/index/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/application/index/index.phtml',
      'application/post/detail' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/application/post/detail.phtml',
      'application/post/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/application/post/index.phtml',
      'application/result/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/application/result/index.phtml',
      'application/result/read' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/application/result/read.phtml',
      'error/404' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/error/404.phtml',
      'error/index' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/error/index.phtml',
      'layout/administrator' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/layout/administrator.phtml',
      'layout/layout' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/layout/layout.phtml',
      'layout/login' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/layout/login.phtml',
      'partial/paginator' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/view/partial/paginator.phtml',
    ),
    'template_path_stack' => 
    array (
      0 => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Application/config/../view',
      'Administrator' => '/Applications/XAMPP/xamppfiles/htdocs/blx/module/Administrator/config/../view',
    ),
  ),
  'layout_settings' => 
  array (
    'modules' => 
    array (
      'Administrator' => 'layout/administrator',
      'Application' => 'layout/layout',
    ),
    'controllers' => 
    array (
    ),
  ),
  'db' => 
  array (
    'driver' => 'Pdo_Mysql',
    'database' => 'blx',
    'username' => 'root',
    'password' => '',
    'hostname' => 'localhost',
    'port' => '3306',
    'charset' => 'UTF-8',
  ),
);