<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;

class Permission extends AbstractTableGateway
{

    protected $table = 'permissions';

    protected $primaryKey = 'permission_id';

    public function getPermissions()
    {
        return $this->select()->toArray();
    }
}