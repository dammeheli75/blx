<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Where;

class Category extends AbstractTableGateway
{

    protected $table = 'categories';

    protected $primaryKey = 'category_id';

    public function getCategories()
    {
        return $this->select()->toArray();
    }

    public function createCategory($category)
    {
        $result = $this->insert($category);
        
        if ($result) {
            $this->clearCache();
        }
        return $result;
    }

    public function updateCategory(array $conditions, array $category)
    {
        $where = new Where();
        
        if (isset($conditions['category_id'])) {
            $where->equalTo('category_id', $conditions['category_id']);
        }
        
        $result = $this->update($category, $where);
        if ($result) {
            $this->clearCache();
        }
        
        return $result;
    }
    
    public function removeCategory(array $conditions)
    {
    	$where = new Where();
    
    	if (isset($conditions['category_id'])) {
    		$where->equalTo('category_id', $conditions['category_id']);
    	}
    
    	$result = $this->delete($where);
    	if ($result) {
    		$this->clearCache();
    	}
    	return $result;
    }
}