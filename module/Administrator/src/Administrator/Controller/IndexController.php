<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Administrator for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administrator\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return array();
    }

    public function fooAction()
    {
        // 8a89b27a092603ba5abdfc4dbb1aa617
        
        $cache = \Blx\Cache\StorageFactory::factory(array(
            'ttl' => 3600
        ));
        
        echo '<pre>';
        print_r($cache->clearByNamespace('Model.Profile'));
        echo '</pre>';
        
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /index/index/foo
        return array();
    }
}
