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
        
        // Get categories
        
        $categories = $categoryModel->cache->getCategories();
        $viewModel->setVariable('categories', $categories);
        
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
        $postConditions = array();
        if ($categoryQuery) {
            // Category Page
            $postConditions['category_id'] = $categoryQuery['category_id'];
        }
        
        $posts = $postModel->cache->getPosts($postConditions);
        
        foreach ($posts as $index => $post) {
            // Author
            $author = $userModel->cache->getUser(array(
                'user_id' => $post['author_id']
            ));
            if ($author)
                $posts[$index]['author'] = $author;
                
                // Category
            $category = $categoryModel->cache->getCategory(array(
                'category_id' => $post['category_id']
            ));
            if ($category) {
                $posts[$index]['category'] = $category;
            }
            
            // Datetime
            $posts[$index]['last_updated'] = new \DateTime($post['last_updated']);
            
            // Post Slug
            $posts[$index]['slug'] = $stringUtilityCache->seoUrl($post['title']);
        }
        
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
