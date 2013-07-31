<?php
namespace Blx\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractForm extends Form
{

    protected $form;
    protected $created = false;
    protected $translator;
    protected $serviceManager;

    public function setForm(Form $form)
    {
        $this->form = $form;
        
        return $this;
    }

    public function getForm()
    {
        if (! $this->form) {
            $this->form = new Form();
        }
        
        if (!$this->created) {
        	$this->create();
        }
        
        return $this->form;
    }

    public function create() {
        return $this->form;
    }

    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        $this->translator = $serviceManager->get('translator');
        
        return $this;
    }
}