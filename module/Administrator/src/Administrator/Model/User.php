<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;

class User extends AbstractTableGateway
{

    protected $table = 'users';

    public function getUsers()
    {
    	return $this->select();
    }
}