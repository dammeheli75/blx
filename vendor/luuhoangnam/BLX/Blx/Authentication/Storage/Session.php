<?php
namespace Blx\Authentication\Storage;

class Session extends \Zend\Authentication\Storage\Session
{

    public function getSession()
    {
        return $this->session;
    }
}