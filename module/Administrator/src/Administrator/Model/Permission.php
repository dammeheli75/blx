<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Where;

class Permission extends AbstractTableGateway
{

    protected $table = 'permissions';

    protected $primaryKey = 'permission_id';

    public function getPermissions()
    {
        return $this->select()->toArray();
    }

    public function updatePermission($perrmission = NULL)
    {
        $where = new Where();
        if (! $perrmission) {
            throw new \Exception("Permission must be have value");
        } else 
            if (isset($perrmission['permission_id']) && is_int($perrmission['permission_id'])) {
                $where->equalTo('permission_id', $perrmission['permission_id']);
                return $this->update($perrmission['permission'], $where);
            } else 
                if (isset($perrmission['role']) && isset($perrmission['resource']) && isset($perrmission['privilege']) && isset($perrmission['allow'])) {
                    $where->equalTo('role', $perrmission['role'])
                        ->equalTo('resource', $perrmission['resource'])
                        ->equalTo('privilege', $perrmission['privilege']);
                    $result = $this->update(array(
                        'is_allow' => $perrmission['allow']
                    ), $where);
                    if ($result){
                        $this->clearCache();
                        \Blx\Cache\StorageFactory::clearCache('Acl');
                    }
                } else {
                    throw new \Exception("Permission must have role, resource and privilege value");
                }
    }
}