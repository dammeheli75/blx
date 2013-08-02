<?php
namespace Blx\Db\TableGateway;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

abstract class AbstractTableGateway extends \Zend\Db\TableGateway\AbstractTableGateway implements TableGatewayInterface
{
    const LOGIC_AND = 'and';
    const LOGIC_OR = 'or';

    protected $primaryKey;

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function __construct(ServiceLocatorInterface $serviceManager)
    {
        $this->adapter = $serviceManager->get('db');
        
        $this->featureSet = new FeatureSet(array(
            new RowGatewayFeature($this->getPrimaryKey())
        ));
        
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
}