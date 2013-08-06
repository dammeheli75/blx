<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Where;

class Venue extends AbstractTableGateway
{

    protected $table = 'venues';

    protected $primaryKey = 'venue_id';

    public function getVenues()
    {
        return $this->select()->toArray();
    }

    public function getVenue($conditions = null)
    {
        $where = new Where();
        
        if (isset($conditions['venue_id'])) {
            $where->equalTo('venue_id', $conditions['venue_id']);
        }
        
        $result = $this->select($where)->toArray();
        
        if (count($result) > 0) {
            return $result[0];
        }
        return false;
    }

    public function createVenue(array $venue)
    {
        $result = $this->insert($venue);
        
        if ($result) {
            $this->clearCache();
        }
        return $result;
    }

    public function updateVenue(array $conditions, array $venue)
    {
        $where = new Where();
        
        if (isset($conditions['venue_id'])) {
            $where->equalTo('venue_id', $conditions['venue_id']);
        }
        
        $result = $this->update($venue, $where);
        if ($result) {
            $this->clearCache();
        }
        
        return $result;
    }

    public function removeVenue(array $conditions)
    {
        $where = new Where();
        
        if (isset($conditions['venue_id'])) {
            $where->equalTo('venue_id', $conditions['venue_id']);
        }
        
        $result = $this->delete($where);
        if ($result) {
            $this->clearCache();
        }
        return $result;
    }
}