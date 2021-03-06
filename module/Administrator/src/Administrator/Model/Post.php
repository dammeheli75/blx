<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;

class Post extends AbstractTableGateway
{

    protected $table = 'posts';

    protected $primaryKey = 'post_id';

    public function getPosts($conditions = NULL, $order = 'last_updated DESC')
    {
        $select = new Select($this->getTable());
        $where = new Where();
        
        if (isset($conditions['category_id'])) {
            $where->equalTo('category_id', $conditions['category_id']);
        }
        
        $select->where($where)->order($order);
        
        return $this->selectWith($select)->toArray();
    }

    public function getPost($conditions = null)
    {
        $where = new Where();
        
        if (isset($conditions['post_id'])) {
            $where->equalTo('post_id', $conditions['post_id']);
        }
        
        $result = $this->select($where)->toArray();
        if (count($result) > 0) {
            return $result[0];
        }
        return false;
    }

    public function createPost($post)
    {
        $result = $this->insert($post);
        
        if ($result) {
            $this->clearCache();
        }
        return $result;
    }

    public function updatePost(array $conditions, array $post)
    {
        $where = new Where();
        
        if (isset($conditions['post_id'])) {
            $where->equalTo('post_id', $conditions['post_id']);
        }
        
        $result = $this->update($post, $where);
        if ($result) {
            $this->clearCache();
        }
        
        return $result;
    }

    public function removePost(array $conditions)
    {
        $where = new Where();
        
        if (isset($conditions['post_id'])) {
            $where->equalTo('post_id', $conditions['post_id']);
        }
        
        $result = $this->delete($where);
        if ($result) {
            $this->clearCache();
        }
        return $result;
    }
}