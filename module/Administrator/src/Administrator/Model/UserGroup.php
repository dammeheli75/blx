<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Where;

class UserGroup extends AbstractTableGateway
{

    protected $table = 'user_groups';
    
    protected $primaryKey = 'group_id';

    public function getGroups()
    {
        return $this->select();
    }

    public function getGroup(array $options)
    {
        $where = new Where();
        
        if (isset($options['group_id'])) {
            $where->equalTo('group_id', $options['group_id']);
        }
        
        return $this->select($where)->current();
    }
}