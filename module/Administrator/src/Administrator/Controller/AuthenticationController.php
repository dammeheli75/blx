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
use Zend\Json\Encoder;

class AuthenticationController extends AbstractActionController
{

    public function loginAction()
    {
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        
        $auth = $serviceManager->get('auth');
        
        if ($auth->hasIdentity()) {
            return $this->redirect()->toRoute('administrator/profiles');
        }
        // If not
        $viewModel = new ViewModel();
        $this->layout('layout/administrator/login');
        
        $redirectUrl = $this->getRequest()->getQuery('redirect_url');
        $viewModel->setVariable('redirectUrl', $redirectUrl);
        return $viewModel;
    }

    public function authenticateAction()
    {
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        
        $auth = $serviceManager->get('auth');
        
        if ($auth->hasIdentity()) {
            return $this->redirect()->toRoute('administrator/profiles');
        } else {
            
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            
            $translator = $serviceManager->get('translator');
            
            $response = array();
            
            if ($this->getRequest()->isPost()) {
                
                $auth = $serviceManager->get('auth');
                
                // Form Data
                $email = $this->getRequest()->getPost('email');
                $password = $this->getRequest()->getPost('password');
                $redirectUrl = $this->getRequest()->getPost('redirect_url');
                
                // Authenticate
                if ($email && $password) {
                    $auth->getAdapter()
                        ->setIdentity($email)
                        ->setCredential($password);
                    $authResult = $auth->authenticate();
                    
                    if (! $authResult->isValid()) {
                        $response['success'] = false;
                        $response['errorMessage'] = $translator->translate('Sai email hoac mat khau');
                    } else {
                        // Save results
                        $auth->getStorage()
                            ->getSession()
                            ->getManager()
                            ->rememberMe(86400); // 1 day
                        
                        $response['success'] = true;
                        if ($redirectUrl) {
                            $response['redirectUrl'] = urldecode($redirectUrl);
                        } else {
                            $response['redirectUrl'] = $this->url()->fromRoute('administrator/profiles');
                        }
                    }
                }
            } else {
                $response['success'] = false;
                $response['errorMessage'] = $translator->translate('Giao thuc khong duoc cho phep');
            }
            
            $viewModel->setVariable('response', Encoder::encode($response));
            $this->getResponse()
                ->getHeaders()
                ->addHeaderLine('Content-Type', 'application/json');
            return $viewModel;
        }
    }

    public function logoutAction()
    {
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        
        $auth = $serviceManager->get('auth');
        
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
        }
        
        return $this->redirect()->toRoute('administrator/authentication/login');
    }
}
