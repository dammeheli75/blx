<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class UserGroup extends AbstractTableGateway
{

    protected $table = 'user_groups';

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

    public function isExists(array $term)
    {
        if ($this->count($term)) {
            return true;
        }
        
        return false;
    }

    public function count(array $term)
    {
        $where = new Where();
        
        if (isset($term['group_id'])) {
            $where->equalTo('group_id', $term['group_id']);
        }
        
        if (isset($term['title'])) {
            $where->equalTo('title', $term['title']);
        }
        
        $select = new Select($this->getTable());
        
        $select->columns(array(
            'count' => new Expression('COUNT(group_id)')
        ))->where($where);
        
        $groupCount = $this->selectWith($select)->current();
        
        return $groupCount['count'];
    }
}