<?php
namespace Blx\Db\TableGateway;

use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\Db\ResultSet\HydratingResultSet;

abstract class AbstractTableGateway extends \Zend\Db\TableGateway\AbstractTableGateway implements TableGatewayInterface
{

    const LOGIC_AND = 'and';

    const LOGIC_OR = 'or';

    public $cache;

    protected $primaryKey;

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function __construct()
    {
        $this->featureSet = new FeatureSet(array(
            new RowGatewayFeature($this->getPrimaryKey())
        ));
        
        $this->featureSet->addFeature(new GlobalAdapterFeature());
        $this->initialize();
        
        // Cache
        $this->cache = new TableGatewayCache($this);
        
        return $this;
    }

    public function isExists($primaryKeyValue)
    {
        if ($this->count(array(
            $this->getPrimaryKey() => $primaryKeyValue
        ))) {
            return true;
        }
        
        return false;
    }

    public function count($conditions = null)
    {
        $where = new Where();
        
        if (isset($conditions[$this->getPrimaryKey()])) {
            $where->equalTo($this->getPrimaryKey(), $conditions[$this->getPrimaryKey()]);
        }
        
        $select = new Select();
        $select->from($this->getTable())
            ->where($where)
            ->columns(array(
            $this->getPrimaryKey() => new Expression('COUNT(*)')
        ));
        
        $countResult = $this->selectWith($select)->current();
        
        return $countResult[$this->getPrimaryKey()];
    }

    public function __call($method, $args)
    {
        $class = get_class($this->object);
        $class_methods = get_class_methods($class);
        
        if (in_array($method, $class_methods)) {
            $caller = array(
                $this->cache,
                $method
            );
            return call_user_func_array($caller, $args);
        }
        
        throw new \Exception(" Method " . $method . " does not exist in this class " . get_class($class) . ".");
    }
}