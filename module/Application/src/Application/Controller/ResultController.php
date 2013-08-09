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
use Administrator\Model\Profile;
use Zend\Json\Encoder;
use Administrator\Model\User;

class ResultController extends AbstractActionController
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
                'content' => $translator->translate('Ket qua thi cap bang lai xe hang A1')
            )
        );
        
        return $viewModel;
    }

    public function readAction()
    {
        // echo '<pre>';
        // print_r($this->auth->getIdentity());
        // echo '</pre>';
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $translator = $serviceManager->get('translator');
        $response = array();
        
        $profileModel = new Profile();
        $userModel = new User();
        $testStatus = array(
            'fail_ theoretical' => $translator->translate('Truot ly thuyet'),
            'pass' => $translator->translate('Dat'),
            'absence' => $translator->translate('Vang mat'),
            'fail_practice' => $translator->translate('Truot thuc hanh')
        );
        
        $filterOption = $this->getRequest()->getQuery('filter');
        $skip = $this->getRequest()->getQuery('skip', 0);
        $pageSize = $this->getRequest()->getQuery('pageSize', 50);
        
        $pageable = array(
            'offset' => $skip,
            'limit' => $pageSize
        );
        
        $filterable = array();
        if ($filterOption['filters'][0]['field'] === 'fullName' && $filterOption['filters'][0]['operator'] === 'contains' && isset($filterOption['filters'][0]['value'])) {
            $filterable['full_name'] = urldecode($filterOption['filters'][0]['value']);
        }
        
        $profiles = $profileModel->cache->getProfilesForFixture($filterable, $pageable);
        
        $response = array(
            'success' => true,
            'total' => $profileModel->cache->countProfilesForFixture($filterable)
        );
        
        foreach ($profiles as $profile) {
            $collaborator = $userModel->cache->getUser(array(
                'user_id' => $profile['collaborator_id']
            ));
            
            $response['students'][] = array(
                'fullName' => $profile['full_name'],
                'birthday' => $profile['birthday'],
                'address' => $profile['address'],
                'collaborator' => $collaborator['full_name'],
                'result' => $profile['test_status'] ? $profile['test_status'] : NULL,
                'licenseFront' => $profile['license_front'],
                'licenseBack' => $profile['license_back']
            );
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }
}
