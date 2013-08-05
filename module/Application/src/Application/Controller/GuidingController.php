<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class GuidingController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $translator = $serviceManager->get('translator');
        
        $this->layout()->breadcrumb = array(
            array(
                'url' => $this->url()->fromRoute('home'),
                'class' => 'home',
                'content' => '<i class="logo"></i>'
            ),
            array(
                'content' => $translator->translate('Huong dan thi bang lai xe hang A1')
            )
        );
        
        return $viewModel;
    }
}
