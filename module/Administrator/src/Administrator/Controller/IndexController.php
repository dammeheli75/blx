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
//         $filter       = new \Zend\Filter\RealPath();
//         $cachedFilter = \Zend\Cache\PatternFactory::factory('object', array(
//         		'object'     => $filter,
//         		'object_key' => 'RealpathFilter',
//         		'storage'    => \Blx\Cache\StorageFactory::factory(),
        
//         		// The realpath filter doesn't output anything
//         		// so the output don't need to be caught and cached
//         		'cache_output' => false,
//         ));
        
//         $path = $cachedFilter->filter('/www/var/path/../../mypath');
        
//         echo '<pre>';
//         print_r($path);
//         echo '</pre>';

        echo '<pre>';
        print_r(__CLASS__ . '::' . __FUNCTION__);
        echo '</pre>';
        
        $profileModel = new \Administrator\Model\Profile();
        
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /index/index/foo
        return array();
    }
}
