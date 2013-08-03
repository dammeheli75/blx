<?php
namespace Blx\Cache;

use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Cache\Storage\Adapter\FilesystemOptions;
use Zend\Cache\Storage\Plugin\ExceptionHandler;
use Zend\Cache\Storage\Plugin\PluginOptions;
use Zend\Cache\Storage\Plugin\Serializer;

class StorageFactory
{

    public static function factory($config = array('ttl' => 3600))
    {
        $filesystemAdapter = new Filesystem();
        
        // Options
        $filesystemOptions = new FilesystemOptions();
        $filesystemOptions->setCacheDir(DATA_PATH . DS . 'cache');
        
        if (isset($config['ttl'])) {
            $filesystemOptions->setTtl((int) $config['ttl']);
        }
        
        if (isset($config['namespace'])) {
            $filesystemOptions->setNamespace($config['namespace']);
        }
        
        // Plugins
        $exceptionHandler = new ExceptionHandler();
        $exceptionHandlerOptions = new PluginOptions();
        $exceptionHandlerOptions->setThrowExceptions(false);
        $exceptionHandler->setOptions($exceptionHandlerOptions);
        
        $filesystemAdapter->setOptions($filesystemOptions);
        $filesystemAdapter->addPlugin($exceptionHandler)->addPlugin(new Serializer());
        
        return $filesystemAdapter;
    }
}