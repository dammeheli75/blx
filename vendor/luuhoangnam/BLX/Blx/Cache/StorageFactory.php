<?php
namespace Blx\Cache;

class StorageFactory
{

    public static function factory($config)
    {
        $cache = \Zend\Cache\StorageFactory::factory(array(
            'adapter' => array(
                'name' => 'filesystem',
                'options' => array(
                    'ttl' => 3600,
                    'cache_dir' => DATA_PATH . DS . 'cache'
                )
            ),
            'plugins' => array(
                'exception_handler' => array(
                    'throw_exceptions' => false
                ),
                'serializer'
            )
        ));
        
        return $cache;
    }
}