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

class UserController extends AbstractActionController
{

    public function indexAction()
    {
        $viewModel = new ViewModel();
        
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

    public function destroyAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }

    public function createAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }

    public function updateAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }
}
