<?php
namespace Blx\Db\TableGateway;

use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractTableGateway extends \Zend\Db\TableGateway\AbstractTableGateway
{

    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->adapter = $serviceManager->get('db');
        
        return $this;
    }
}