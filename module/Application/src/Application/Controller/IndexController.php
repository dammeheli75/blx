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

class IndexController extends AbstractActionController
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
                'content' => $translator->translate('Trang chu')
            )
        );
        
        return $viewModel;
    }

    public function restrictionAccessAction()
    {
        $viewModel = new ViewModel();
        
        return $viewModel;
    }

    public function fbchannelAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        $cache_expire = 60 * 60 * 24 * 365;
        
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Expires', gmdate('D, d M Y H:i:s', time() + $cache_expire) . ' GMT')
            ->addHeaderLine('Cache-Control', 'max-age=' . $cache_expire)
            ->addHeaderLine('Pragma', 'public');
        
        return $viewModel;
    }
}
