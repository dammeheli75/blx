<?php
namespace Blx\InputFilter;

use Zend\InputFilter\InputFilter as ZendInputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractInputFilter extends ZendInputFilter
{

    protected $serviceManager;

    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        
        return $this;
    }
}