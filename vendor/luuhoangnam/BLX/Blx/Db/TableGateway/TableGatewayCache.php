<?php
namespace Blx\Db\TableGateway;

use Zend\Cache\PatternFactory;
use Blx\Cache\StorageFactory;

class TableGatewayCache
{

    protected $cache;

    protected $cacheTtl = 86400;

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
        
        $this->cache = PatternFactory::factory('object', array(
            'object' => $this->model,
            'storage' => StorageFactory::factory(array(
                'ttl' => $this->cacheTtl
            )),
            'cache_output' => false
        ));
    }

    public function __call($method, $args)
    {
        $class = get_class($this->model);
        $class_methods = get_class_methods($class);
        
        if (in_array($method, $class_methods)) {
            $caller = Array(
                $this->cache,
                $method
            );
            return call_user_func_array($caller, $args);
        }
        
        throw new \Exception(" Method " . $method . " does not exist in this class " . get_class($class) . ".");
    }
}