<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Where;

class Collaborator extends AbstractTableGateway
{

    protected $table = 'collaborators';

    protected $primaryKey = 'collaborator_id';

    public function getCollaborators()
    {
        return $this->select()->toArray();
    }

    public function getCollaborator($condition = null)
    {
        $where = new Where();
        
        if (isset($condition['collaborator_id'])) {
            $where->equalTo('collaborator_id', $condition['collaborator_id']);
        }
        
        $result = $this->select($where)->toArray();
        
        return $result[0];
    }
}