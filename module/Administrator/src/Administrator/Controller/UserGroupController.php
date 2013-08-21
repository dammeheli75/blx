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
use Administrator\Model\UserGroup;

class UserGroupController extends AbstractActionController
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
        
        $userGroupModel = new UserGroup($serviceManager);
        
        $groups = $userGroupModel->getGroups();
        
        $response = array(
            'succes' => true,
            'total' => count($groups)
        );
        
        foreach ($groups as $group) {
            $response['groups'][] = array(
                'ID' => $group['group_id'],
                'title' => $group['title'],
//                 'timeCreated' => $group['time_created'],
//                 'lastUpdate' => $group['last_updated']
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
