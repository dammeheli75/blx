<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administrator\Model\Category;
use Administrator\Model\Post;
use Administrator\Model\User;
use Zend\Cache\PatternFactory;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Blx\Cache\StorageFactory;

class PostController extends AbstractActionController
{

    public function indexAction()
    {
        $viewModel = new ViewModel();
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        
        // Cache
        $stringUtilityCache = PatternFactory::factory('class', array(
            'class' => 'Blx\Utility\String',
            'storage' => \Blx\Cache\StorageFactory::factory(array(
                'ttl' => 0,
                'namespace' => 'StringUtility'
            ))
        ));
        
        // Models
        $categoryModel = new Category();
        $postModel = new Post();
        $userModel = new User();
        
        $categorySlug = $this->params('category_slug', null);
        $currentPage = $this->params('page');
        
        // Get categories
        $categories = $categoryModel->cache->getCategories();
        $viewModel->setVariable('categories', $categories);
        
        // Check is category page
        if ($categorySlug && strlen($categorySlug) > 0) {
            // Category Page
            $categoryQuery = $categoryModel->cache->getCategory(array(
                'slug' => $categorySlug
            ));
            
            if (! $categoryQuery) {
                $this->getResponse()->setStatusCode(404);
                return;
            }
        } else {
            // Index Page
            $categoryQuery = null;
        }
        
        // Pagination
        $select = new Select();
        $select->from('posts');
        
        // Get posts
        if ($categorySlug && strlen($categorySlug) > 0) {
            // Category Page
            $categoryQuery = $categoryModel->cache->getCategory(array(
                'slug' => $categorySlug
            ));
            
            if (! $categoryQuery) {
                $this->getResponse()->setStatusCode(404);
                return;
            }
        } else {
            // Index Page
            $categoryQuery = null;
        }
        
        $viewModel->setVariable('categoryQuery', $categoryQuery);
        
        $select->where(function (Where $where) use($categoryQuery)
        {
            if ($categoryQuery) {
                $where->equalTo('category_id', $categoryQuery['category_id']);
            }
        });
        
        // Paginator Configuration
        $paginatorAdapter = new DbSelect($select, $serviceManager->get('db'));
        $posts = new Paginator($paginatorAdapter);
        $paginatorCache = StorageFactory::factory(array(
            'ttl' => 86400,
            'namespace' => 'Model.Post'
        ));
        $posts->setCache($paginatorCache);
        $posts->setCurrentPageNumber($currentPage);
        $posts->setItemCountPerPage(10);
        
        $viewModel->setVariables(array(
            'userModel' => $userModel,
            'categoryModel' => $categoryModel,
            'stringUtilityCache' => $stringUtilityCache
        ));
        $viewModel->setVariable('posts', $posts);
        
        return $viewModel;
    }

    public function detailAction()
    {
        $viewModel = new ViewModel();
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        
        // Cache
        $stringUtilityCache = PatternFactory::factory('class', array(
            'class' => 'Blx\Utility\String',
            'storage' => \Blx\Cache\StorageFactory::factory(array(
                'ttl' => 0,
                'namespace' => 'StringUtility'
            ))
        ));
        
        // Model
        $categoryModel = new Category();
        $postModel = new Post();
        $userModel = new User();
        
        // Get categories
        $categories = $categoryModel->cache->getCategories();
        $viewModel->setVariable('categories', $categories);
        
        $categorySlug = $this->params('category_slug');
        $postSlug = $this->params('post_slug');
        $postId = $this->params('post_id');
        
        $category = $categoryModel->cache->getCategory(array(
            'slug' => $categorySlug
        ));
        $post = $postModel->cache->getPost(array(
            'post_id' => $postId
        ));
        
        if ($category && $post && $category['category_id'] == $post['category_id']) {
            // Detail Page
            // Author
            $author = $userModel->cache->getUser(array(
                'user_id' => $post['author_id']
            ));
            if ($author)
                $post['author'] = $author;
                
                // Category
            $category = $categoryModel->cache->getCategory(array(
                'category_id' => $post['category_id']
            ));
            if ($category) {
                $post['category'] = $category;
            }
            
            // Datetime
            $post['last_updated'] = new \DateTime($post['last_updated']);
            
            // Post Slug
            $post['slug'] = $stringUtilityCache->seoUrl($post['title']);
            
            $viewModel->setVariable('post', $post);
        } else {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        return $viewModel;
    }
}
