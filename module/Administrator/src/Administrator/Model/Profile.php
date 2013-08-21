<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class Profile extends AbstractTableGateway
{

    protected $table = 'profiles';

    protected $primaryKey = 'profile_id';

    public function getProfiles()
    {
        $select = new Select($this->getTable());
        $select->order('last_update DESC');
        
        return $this->selectWith($select)->toArray();
    }

    public function getProfilesForFixture($filterable = null, $pageable = null, $option = null)
    {
        $select = new Select($this->getTable());
        $where = new Where();
        
        // Filterable
        if (isset($filterable['full_name'])) {
            $where->like('full_name', "%{$filterable['full_name']}%");
        }
        
//         $where->isNotNull('test_date')->isNotNull('test_venue_id');
        
        $select->where($where)->order('time_created DESC');
        // Pageable
        if (isset($pageable['offset'])) {
            $select->offset($pageable['offset']);
        }
        if (isset($pageable['limit'])) {
            $select->limit($pageable['limit']);
        }
        
        return $this->selectWith($select)->toArray();
    }

    public function countProfilesForFixture($filterable = null)
    {
        $select = new Select($this->getTable());
        $where = new Where();
        
        // Filterable
        if (isset($filterable['full_name'])) {
            $where->like('full_name', "%" . $filterable['full_name'] . "%");
        }
        
//         $where->isNotNull('test_date')->isNotNull('test_venue_id');
        $select->where($where)->order('time_created DESC');
        $select->columns(array(
            $this->getPrimaryKey() => new Expression('COUNT(*)')
        ));
        
        $countResult = $this->selectWith($select)->current();
        return $countResult[$this->getPrimaryKey()];
    }

    public function getProfile($conditions = null)
    {
        $where = new Where();
        
        if (isset($conditions['profile_id'])) {
            $where->equalTo('profile_id', $conditions['profile_id']);
        }
        
        $result = $this->select($where)->toArray();
        
        if (count($result) > 0) {
            return $result[0];
        }
        return false;
    }

    public function createProfile(array $profile)
    {
        $result = $this->insert($profile);
        
        if ($result) {
            $this->clearCache();
        }
        return $result;
    }

    public function updateProfile(array $conditions, array $profile)
    {
        $where = new Where();
        
        if (isset($conditions['profile_id'])) {
            $where->equalTo('profile_id', $conditions['profile_id']);
        }
        
        $result = $this->update($profile, $where);
        if ($result) {
            $this->clearCache();
        }
        
        return $result;
    }

    public function removeProfile(array $conditions)
    {
        $where = new Where();
        
        if (isset($conditions['profile_id'])) {
            $where->equalTo('profile_id', $conditions['profile_id']);
        }
        
        $result = $this->delete($where);
        if ($result) {
            $this->clearCache();
        }
        
        return $result;
    }

    public function isExists($conditions = NULL)
    {
        if (is_numeric($conditions)) {
            return parent::isExists($conditions);
        }
        if (is_array($conditions)) {
            $where = new Where();
            
            if (isset($conditions['full_name'])) {
                $where->equalTo('full_name', $conditions['full_name']);
            }
            
            if (isset($conditions['address'])) {
                $where->equalTo('address', $conditions['address']);
            }
            
            if (isset($conditions['phone_number'])) {
                $where->equalTo('phone_number', $conditions['phone_number']);
            }
            
            $select = new Select();
            $select->from($this->getTable())
                ->where($where)
                ->columns(array(
                $this->getPrimaryKey() => new Expression('COUNT(*)')
            ));
            
            $countResult = $this->selectWith($select)->current();
            
            if ($countResult[$this->getPrimaryKey()])
                return true;
            return false;
        }
    }
}