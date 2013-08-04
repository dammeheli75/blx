<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Administrator\Controller\Index' => 'Administrator\Controller\IndexController',
            'Administrator\Controller\Profile' => 'Administrator\Controller\ProfileController',
            'Administrator\Controller\User' => 'Administrator\Controller\UserController',
            'Administrator\Controller\UserGroup' => 'Administrator\Controller\UserGroupController',
            'Administrator\Controller\System' => 'Administrator\Controller\SystemController',
            'Administrator\Controller\Authentication' => 'Administrator\Controller\AuthenticationController',
            'Administrator\Controller\Collaborator' => 'Administrator\Controller\CollaboratorController',
            'Administrator\Controller\Venue' => 'Administrator\Controller\VenueController',
            'Administrator\Controller\Category' => 'Administrator\Controller\CategoryController',
            'Administrator\Controller\Post' => 'Administrator\Controller\PostController'
        )
    ),
    'router' => array(
        'routes' => array(
            'administrator' => array(
                'type' => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route' => '/administrator',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Administrator\Controller',
                        'controller' => 'Index',
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
                    ),
                    'profiles' => array(
                        'type' => 'Literal',
                        'options' => array(
                            // Change this to something specific to your module
                            'route' => '/profiles',
                            'defaults' => array(
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Administrator\Controller',
                                'controller' => 'Profile',
                                'action' => 'index'
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            // This route is a sane default when developing a module;
                            // as you solidify the routes for your module, however,
                            // you may want to remove it and replace it with more
                            // specific routes.
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
                    'users' => array(
                        'type' => 'Literal',
                        'options' => array(
                            // Change this to something specific to your module
                            'route' => '/users',
                            'defaults' => array(
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Administrator\Controller',
                                'controller' => 'User',
                                'action' => 'index'
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            // This route is a sane default when developing a module;
                            // as you solidify the routes for your module, however,
                            // you may want to remove it and replace it with more
                            // specific routes.
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
                    'user-groups' => array(
                        'type' => 'Literal',
                        'options' => array(
                            // Change this to something specific to your module
                            'route' => '/user-groups',
                            'defaults' => array(
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Administrator\Controller',
                                'controller' => 'UserGroup',
                                'action' => 'index'
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            // This route is a sane default when developing a module;
                            // as you solidify the routes for your module, however,
                            // you may want to remove it and replace it with more
                            // specific routes.
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
                    'system' => array(
                        'type' => 'Literal',
                        'options' => array(
                            // Change this to something specific to your module
                            'route' => '/system',
                            'defaults' => array(
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Administrator\Controller',
                                'controller' => 'System',
                                'action' => 'index'
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            // This route is a sane default when developing a module;
                            // as you solidify the routes for your module, however,
                            // you may want to remove it and replace it with more
                            // specific routes.
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
                    'categories' => array(
                        'type' => 'Literal',
                        'options' => array(
                            // Change this to something specific to your module
                            'route' => '/categories',
                            'defaults' => array(
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Administrator\Controller',
                                'controller' => 'Category',
                                'action' => 'index'
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            // This route is a sane default when developing a module;
                            // as you solidify the routes for your module, however,
                            // you may want to remove it and replace it with more
                            // specific routes.
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
                    'posts' => array(
                        'type' => 'Literal',
                        'options' => array(
                            // Change this to something specific to your module
                            'route' => '/posts',
                            'defaults' => array(
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Administrator\Controller',
                                'controller' => 'Post',
                                'action' => 'index'
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            // This route is a sane default when developing a module;
                            // as you solidify the routes for your module, however,
                            // you may want to remove it and replace it with more
                            // specific routes.
                            'default' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '[/:action]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                                    ),
                                    'defaults' => array()
                                )
                            ),
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit[/:post_id]',
                                    'constraints' => array(
                                        'post_id' => '\d*'
                                    ),
                                    'defaults' => array(
                                        'action' => 'edit'
                                    )
                                )
                            )
                        )
                    ),
                    'authentication' => array(
                        'type' => 'Literal',
                        'options' => array(
                            // Change this to something specific to your module
                            'route' => '/authentication',
                            'defaults' => array(
                                // Change this value to reflect the namespace in which
                                // the controllers for your module are found
                                '__NAMESPACE__' => 'Administrator\Controller',
                                'controller' => 'Authentication',
                                'action' => 'index'
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'login' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    // Change this to something specific to your module
                                    'route' => '/login',
                                    'defaults' => array(
                                        // Change this value to reflect the namespace in which
                                        // the controllers for your module are found
                                        '__NAMESPACE__' => 'Administrator\Controller',
                                        'controller' => 'Authentication',
                                        'action' => 'login'
                                    )
                                )
                            ),
                            'logout' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    // Change this to something specific to your module
                                    'route' => '/logout',
                                    'defaults' => array(
                                        // Change this value to reflect the namespace in which
                                        // the controllers for your module are found
                                        '__NAMESPACE__' => 'Administrator\Controller',
                                        'controller' => 'Authentication',
                                        'action' => 'logout'
                                    )
                                )
                            ),
                            'authenticate' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    // Change this to something specific to your module
                                    'route' => '/authenticate',
                                    'defaults' => array(
                                        // Change this value to reflect the namespace in which
                                        // the controllers for your module are found
                                        '__NAMESPACE__' => 'Administrator\Controller',
                                        'controller' => 'Authentication',
                                        'action' => 'authenticate'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Administrator' => __DIR__ . '/../view'
        )
    )
);
