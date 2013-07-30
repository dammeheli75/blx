<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

define('PS', PATH_SEPARATOR);
define('DS', DIRECTORY_SEPARATOR);

define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . DS . 'config');
define('DATA_PATH', ROOT_PATH . DS . 'data');
define('MODULE_PATH', ROOT_PATH . DS . 'module');
define('PUBLIC_PATH', ROOT_PATH . DS . 'public');
define('VENDOR_PATH', ROOT_PATH . DS . 'vendor');

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
