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
use Administrator\Model\Venue;

class VenueController extends AbstractActionController
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
        
        $venueModel = new Venue($serviceManager);
        
        $venues = $venueModel->cache->getVenues();
        
        $response = array(
            'success' => true,
            'total' => count($venues)
        );
        
        foreach ($venues as $venue) {
            $response['venues'][] = array(
                'ID' => $venue['venue_id'],
                'title' => $venue['title'],
                'longitude' => $venue['longitude'],
                'laitude' => $venue['laitude'],
                'joinDate' => $venue['time_created']
            );
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }
}
