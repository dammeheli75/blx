<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Where;

class User extends AbstractTableGateway
{

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    public function getUsers()
    {
        return $this->select()->toArray();
    }

    public function getUser($term = null)
    {
        $where = new Where();
        
        if (isset($term['user_id'])) {
            $where->equalTo('user_id', $term['user_id']);
        }
        if (isset($term['email'])) {
            $where->equalTo('email', $term['email']);
        }
        if (isset($term['phone_number'])) {
            $where->equalTo('phone_number', $term['phone_number']);
        }
        
        $result = $this->select($where)->toArray();
        
        return $result[0];
    }

    public function createUser(array $user)
    {
        $result = $this->insert($user);
        
        if ($result) {
            $this->clearCache();
        }
        return $result;
    }

    public function updateUser(array $conditions, array $user)
    {
        $where = new Where();
        
        if (isset($conditions['user_id'])) {
            $where->equalTo('user_id', $conditions['user_id']);
        }
        
        $result = $this->update($user, $where);
        if ($result) {
            $this->clearCache();
        }
        
        return $result;
    }

    public function removeUser(array $conditions)
    {
        $where = new Where();
        
        if (isset($conditions['user_id'])) {
            $where->equalTo('user_id', $conditions['user_id']);
        }
        if (isset($conditions['email'])) {
            $where->equalTo('email', $conditions['email']);
        }
        if (isset($conditions['phone_number'])) {
            $where->equalTo('phone_number', $conditions['phone_number']);
        }
        
        $result = $this->delete($where);
        if ($result) {
            $this->clearCache();
        }
        return $result;
    }
}