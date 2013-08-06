<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Where;

class User extends AbstractTableGateway
{

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    public function getUsers($conditions = null)
    {
        $where = new Where();
        
        if (isset($conditions['group_id'])) {
            $where->equalTo('group_id', $conditions['group_id']);
        }
        
        return $this->select($where)->toArray();
    }

    public function getUser($conditions = null)
    {
        $where = new Where();
        
        if (isset($conditions['user_id'])) {
            $where->equalTo('user_id', $conditions['user_id']);
        }
        if (isset($conditions['group_id'])) {
            $where->equalTo('group_id', $conditions['group_id']);
        }
        if (isset($conditions['email'])) {
            $where->equalTo('email', $conditions['email']);
        }
        if (isset($conditions['phone_number'])) {
            $where->equalTo('phone_number', $conditions['phone_number']);
        }
        
        $result = $this->select($where)->toArray();
        
        if (count($result) > 0) {
            return $result[0];
        }
        return false;
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