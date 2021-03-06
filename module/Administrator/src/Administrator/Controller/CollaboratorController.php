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
use Administrator\Model\User;
use Blx\User\UserManager;

class CollaboratorController extends AbstractActionController
{

    public function indexAction()
    {
        return array();
    }

    public function readAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        
        $userModel = new User();
        
        $collaborators = $userModel->cache->getUsers(array(
            'group_id' => UserManager::COLLABORATOR_GROUP
        ));
        
        $response = array(
            'success' => true,
            'total' => count($collaborators)
        );
        
        foreach ($collaborators as $collaborator) {
            $response['collaborators'][] = array(
                'ID' => $collaborator['user_id'],
                'title' => $collaborator['full_name'],
                'address' => $collaborator['address'],
                'joinDate' => $collaborator['time_created']
            );
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }
}
