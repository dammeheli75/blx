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
use Zend\Json\Encoder;
use Administrator\Model\Profile;
use Administrator\Model\Collaborator;
use Administrator\Model\Venue;

class FixtureController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function readAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $response = array();
        
        $profileModel = new Profile($serviceManager);
        $collaboratorModel = new Collaborator($serviceManager);
        $venueModel = new Venue($serviceManager);
        
        $filterOption = $this->getRequest()->getQuery('filter');
        $skip = $this->getRequest()->getQuery('skip', 0);
        $pageSize = $this->getRequest()->getQuery('pageSize', 50);
        
        $pageable = array(
            'offset' => $skip,
            'limit' => $pageSize
        );
        
        $filterable = array();
        if ($filterOption['filters'][0]['field'] === 'fullName' && $filterOption['filters'][0]['operator'] === 'contains' && isset($filterOption['filters'][0]['value'])) {
            $filterable['full_name'] = $filterOption['filters'][0]['value'];
        }
        
        $profiles = $profileModel->cache->getProfilesForFixture($filterable, $pageable);
        
        $response = array(
            'success' => true,
            'total' => $profileModel->cache->countProfilesForFixture($filterable)
        );
        
        foreach ($profiles as $profile) {
            $collaborator = $collaboratorModel->cache->getCollaborator(array(
                'collaborator_id' => $profile['collaborator_id']
            ));
            $venue = $venueModel->cache->getVenue(array(
                'venue_id' => $profile['test_venue_id']
            ));
            
            $response['students'][] = array(
                'fullName' => $profile['full_name'],
                'birthday' => $profile['birthday'],
                'address' => $profile['address'],
                'collaborator' => $collaborator['title'],
                'testDate' => $profile['test_date'],
                'venueAddress' => $venue['title']
            );
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }
}
