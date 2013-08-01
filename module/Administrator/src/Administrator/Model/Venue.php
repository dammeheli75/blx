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
        return $this->select();
    }

    public function getVenue($conditions = null)
    {
        $where = new Where();
        
        if (isset($conditions['venue_id'])) {
            $where->equalTo('venue_id', $conditions['venue_id']);
        }
        
        return $this->select($where)->current();
    }
}