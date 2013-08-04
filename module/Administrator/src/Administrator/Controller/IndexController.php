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
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $viewModel = new ViewModel();
        
        return $viewModel;
    }

    public function fooAction()
    {
        $viewModel = new ViewModel();
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $translator = $serviceManager->get('translator');
        
        $string = \Blx\Utility\String::seoUrl($translator->translate('thanh-nien-nghiem-tuc-2013---------'));
        
        echo '<pre>Before: ';
        print_r($string);
        echo '</pre>';
        
        if ($string[strlen($string)-1] === '-') {
        	$string = substr($string, 0, strlen($string) - 1);
        }
        
        echo '<pre>After: ';
        print_r($string);
        echo '</pre>';
        
        
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /index/index/foo
        return $viewModel;
    }
}
