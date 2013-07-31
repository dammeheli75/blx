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
use Administrator\Model\User;
use Zend\Json\Encoder;
use Administrator\Model\UserGroup;
use Kendo\UI\Grid;

class UserController extends AbstractActionController
{

    public function indexAction()
    {
        $viewModel = new ViewModel();
        
        $grid = new Grid('grid');
        
        $viewModel->setVariable('grid', $grid);
        return $viewModel;
    }

    public function readAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $response = array();
        
        $userModel = new User($serviceManager);
        $userGroupModel = new UserGroup($serviceManager);
        
        $users = $userModel->getUsers();
        
        $response = array(
            'succes' => true,
            'total' => count($users)
        );
        
        foreach ($users as $user) {
            $userGroup = $userGroupModel->getGroup(array(
                'group_id' => $user->group_id
            ));
            
            $response['users'][] = array(
                'ID' => $user->user_id,
                'group' => array(
                    'ID' => $user->group_id,
                    'title' => $userGroup->title
                ),
                'fullName' => $user->full_name,
                'email' => $user->email,
                'password' => $user->password,
                'birthday' => $user->birthday,
                'address' => $user->address,
                'phoneNumber' => $user->phone_number,
                'timeCreated' => $user->time_created,
                'lastUpdate' => $user->last_updated
            );
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }

    /**
     *
     * @todo Kiem duyet thong tin
     * @return \Zend\View\Model\ViewModel
     */
    public function createAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $response = array();
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            
            $userModel = new User($serviceManager);
            $birthday = new \DateTime($postData['birthday']);
            
            $user = array(
                'group_id' => $postData['group'],
                'full_name' => $postData['fullName'],
                'birthday' => $birthday->format('Y-m-d'),
                'email' => $postData['email'],
                'phone_number' => $postData['phone_number'],
                'address' => $postData['address'],
                'phone_number' => $postData['phoneNumber']
            );
            
            if ($userModel->createUser($user)) {
                $response['success'] = true;
                $response['insert_id'] = $userModel->getLastInsertValue();
            }
        } else {
            $response['success'] = false;
            $response['errors'] = $serviceManager->get('translator')->translate('Truy cap bi han che');
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }

    public function updateAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $response = array();
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            
            $userModel = new User($serviceManager);
            
            $birthday = new \DateTime($postData['birthday']);
            
            $user = array(
                'group_id' => $postData['group']['ID'],
                'full_name' => $postData['fullName'],
                'birthday' => $birthday->format('Y-m-d'),
                'email' => $postData['email'],
                'phone_number' => $postData['phone_number'],
                'address' => $postData['address'],
                'phone_number' => $postData['phoneNumber']
            );
            
            if ($userModel->updateUser(array(
                'user_id' => $postData['ID']
            ), $user)) {
                $response['success'] = true;
            }
        } else {
            $response['success'] = false;
            $response['errors'] = $serviceManager->get('translator')->translate('Truy cap bi han che');
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }

    public function destroyAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $response = array();
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            
            $userModel = new User($serviceManager);
            
            if ($userModel->removeUser(array(
                'user_id' => $postData['ID']
            ))) {
                $response['success'] = true;
            }
        } else {
            $response['success'] = false;
            $response['errors'] = $serviceManager->get('translator')->translate('Truy cap bi han che');
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }
}
