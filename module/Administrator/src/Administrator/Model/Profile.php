<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class Profile extends AbstractTableGateway
{

    protected $table = 'profiles';

    protected $primaryKey = 'profile_id';

    public function __construct($serviceManager)
    {
        parent::__construct($serviceManager);
        
        $this->featureSet = new FeatureSet(array(
            new RowGatewayFeature('profile_id')
        ));
    }

    public function getProfiles()
    {
        return $this->select();
    }

    public function getProfilesForFixture($filterable = null, $pageable = null, $option = null)
    {
        $select = new Select($this->getTable());
        $where = new Where();
        
        // Filterable
        if (isset($filterable['full_name'])) {
            $where->like('full_name', "%{$filterable['full_name']}%");
        }
        
        $where->isNotNull('test_date')->isNotNull('test_venue_id');
        
        $select->where($where);
        // Pageable
        if (isset($pageable['offset'])) {
            $select->offset($pageable['offset']);
        }
        if (isset($pageable['limit'])) {
            $select->limit($pageable['limit']);
        }
        
        return $this->selectWith($select);
    }

    public function countProfilesForFixture($filterable = null)
    {
        $select = new Select($this->getTable());
        $where = new Where();
        
        // Filterable
        if (isset($filterable['full_name'])) {
            $where->like('full_name', "%" . $filterable['full_name'] . "%");
        }
        
        $where->isNotNull('test_date')->isNotNull('test_venue_id');
        $select->where($where);
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
        
        return $this->select($where)->current();
    }

    public function createProfile(array $profile)
    {
        return $this->insert($profile);
    }

    public function updateProfile(array $where, array $profile)
    {
        $row = $this->getProfile($where);
        
        foreach ($profile as $field => $value) {
            $row->$field = $value;
        }
        
        return $row->save();
    }

    public function removeProfile(array $conditions)
    {
        $where = new Where();
        
        if (isset($conditions['profile_id'])) {
            $where->equalTo('profile_id', $conditions['profile_id']);
        }
        
        return $this->delete($where);
    }
}