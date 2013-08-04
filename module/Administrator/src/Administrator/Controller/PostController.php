<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Administrator for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Administrator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Kendo\JavaScriptFunction;
use Kendo\UI\Grid;
use Kendo\UI\GridToolbarItem;
use Kendo\UI\GridPageable;
use Kendo\UI\GridPageableMessages;
use Kendo\UI\GridEditable;
use Kendo\UI\GridColumn;
use Kendo\UI\GridColumnCommandItem;
use Administrator\Model\Category;
use Administrator\Model\Post;
use Administrator\Model\User;
use Kendo\Data\DataSource;
use Kendo\Data\DataSourceTransportRead;
use Kendo\Data\DataSourceTransportDestroy;
use Kendo\Data\DataSourceTransport;
use Kendo\Data\DataSourceSchema;
use Zend\Json\Encoder;
use Kendo\Data\DataSourceSchemaModel;
use Kendo\Data\DataSourceSchemaModelField;
use Kendo\UI\DropDownList;

class PostController extends AbstractActionController
{

    public function indexAction()
    {
        $viewModel = new ViewModel();
        
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $translator = $serviceManager->get('translator');
        
        //
        // Grid
        //
        $grid = new Grid('grid');
        //
        // Height
        //
        $grid->height(new JavaScriptFunction("$(window).height() - 66"));
        //
        // Toolbar
        //
        $toolbarAdd = new GridToolbarItem();
        $toolbarAdd->name('add')->templateId('addButtonTemplate');
        $grid->addToolbarItem($toolbarAdd);
        $toolbarSearch = new GridToolbarItem();
        $toolbarSearch->name('search')->templateId('quickSearchPostTemplate');
        $grid->addToolbarItem($toolbarSearch);
        //
        // Sortable
        //
        $grid->sortable(true);
        //
        // Resizable
        //
        $grid->resizable(true);
        //
        // Pageable
        //
        $pageable = new GridPageable();
        $pageableMessage = new GridPageableMessages();
        $pageableMessage->display($translator->translate('Hien thi {0}-{1}/{2} bai viet'))
            ->_empty($translator->translate('Khong co bai viet nao'));
        $pageable->pageSize(50)->messages($pageableMessage);
        $grid->pageable($pageable);
        //
        // Editable
        //
        $editable = new GridEditable();
        $editable->mode('popup')->confirmation($translator->translate('Chu y: bai viet da xoa se khong lay lai duoc. Co chac chan muon xoa bai viet nay?'));
        $grid->editable($editable);
        //
        // Columns
        //
        // ID
        $idColumn = new GridColumn();
        $idColumn->field('ID')
            ->title($translator->translate('#'))
            ->width(60)
            ->sortable(false)
            ->attributes(' style="text-align:center"');
        $grid->addColumn($idColumn);
        // Title
        $titleColumn = new GridColumn();
        $titleColumn->field('title')
            ->title($translator->translate('Tieu de'))
            ->width(new JavaScriptFunction('$(window).width() - 780'))
            ->template('<a href="' . $this->url()
            ->fromRoute('administrator/posts/default', array(
            'action' => 'edit'
        )) . '/#= ID #" class=\'post-title\'>#= title #</a>');
        $grid->addColumn($titleColumn);
        // Author
        $authorColumn = new GridColumn();
        $authorColumn->field('author')
            ->title($translator->translate('Tac gia'))
            ->width(140)
            ->sortable(false)
            ->template('#= author.fullName #')
            ->attributes(' style="text-align:center"');
        $grid->addColumn($authorColumn);
        // Category
        $categoryColumn = new GridColumn();
        $categoryColumn->field('category')
            ->title($translator->translate('Chuyen muc'))
            ->width(180)
            ->sortable(false)
            ->template('#= category.title #');
        $grid->addColumn($categoryColumn);
        // Status
        $statusColumn = new GridColumn();
        $statusColumn->field('status')
            ->title($translator->translate('Trang thai'))
            ->width(100)
            ->sortable(false)
            ->attributes(' style="text-align:center"')
            ->template(new JavaScriptFunction("function (dataItem) {
                    var status = dataItem.status;
                    if (status) {
                        switch (status) {
                            case \"published\":
                                return \"" . $translator->translate('Da xuat ban') . "\";
                            case \"pending_review\":
                                return \"" . $translator->translate('Cho xem lai') . "\";
                            default:
                                return \"" . $translator->translate('Ban nhap') . "\";
                        }
                    } else {
                        return \"--\";
                    }
                }"));
        $grid->addColumn($statusColumn);
        // Post Date
        $postDateColumn = new GridColumn();
        $postDateColumn->field('postDate')
            ->title($translator->translate('Ngay dang'))
            ->width(100)
            ->sortable(false)
            ->attributes(' style="text-align:center"')
            ->template('#= kendo.toString(postDate,"dd/MM/yyyy") #');
        $grid->addColumn($postDateColumn);
        // Last Update
        $lastUpdateColumn = new GridColumn();
        $lastUpdateColumn->field('lastUpdate')
            ->title($translator->translate('Sua lan cuoi'))
            ->width(100)
            ->sortable(false)
            ->attributes(' style="text-align:center"')
            ->template('#= kendo.toString(postDate,"dd/MM/yyyy") #');
        $grid->addColumn($lastUpdateColumn);
        // Command
        $commandColumn = new GridColumn();
        $commandDestroy = new GridColumnCommandItem();
        $commandDestroy->name('destroy')->text($translator->translate('Xoa'));
        $commandColumn->title('&nbsp;')->addCommandItem($commandDestroy);
        $grid->addColumn($commandColumn);
        //
        // DataSource
        //
        $dataSource = new DataSource();
        // Transport Read
        $transportRead = new DataSourceTransportRead();
        $transportRead->url($this->url()
            ->fromRoute('administrator/posts/default', array(
            'action' => 'read'
        )));
        // Transport Destroy
        $transportDestroy = new DataSourceTransportDestroy();
        $transportDestroy->url($this->url()
            ->fromRoute('administrator/posts/default', array(
            'action' => 'destroy'
        )))
            ->type('POST');
        $transport = new DataSourceTransport();
        $transport->read($transportRead)->destroy($transportDestroy);
        $dataSource->transport($transport);
        // Schema
        $schema = new DataSourceSchema();
        $schema->data('posts')->total('total');
        // Model
        $model = new DataSourceSchemaModel();
        $postDateModelField = new DataSourceSchemaModelField('postDate');
        $postDateModelField->type('date');
        $model->addField($postDateModelField);
        $lastUpdateModelField = new DataSourceSchemaModelField('lastUpdate');
        $lastUpdateModelField->type('date');
        $model->addField($lastUpdateModelField);
        $model->id('ID');
        $schema->model($model);
        
        $dataSource->schema($schema);
        
        $grid->dataSource($dataSource);
        
        $grid->dataBound(new JavaScriptFunction("function () {
            // Tooltip Activate
            $('[data-toggle=\"tooltip\"]').tooltip({
                placement: \"right\"
            });

            var self = this;
            var quickSearchPost = $('form#quickSearchPost');

            quickSearchPost.find('input[name=\"q\"]').on('change keydown paste input', function () {
                // Filter
                var q = $(this).val();
                if (q != lastQuickSearch) {
                    self.dataSource.filter({
                        field: \"title\",
                        operator: \"contains\",
                        value: q
                    });

                    lastQuickSearch = q;
                }
            });

            quickSearchPost.submit(function () {
                // Filter
                var q = $(this).find('input[name=\"q\"]').val();

                if (q != lastQuickSearch) {
                    self.dataSource.filter({
                        field: \"title\",
                        operator: \"contains\",
                        value: q
                    });

                    lastQuickSearch = q;
                }

                return false;
            });
        }"));
        
        $viewModel->setVariable('grid', $grid);
        return $viewModel;
    }

    public function readAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $response = array();
        
        $postModel = new Post();
        $categoryModel = new Category();
        $userModel = new User();
        
        $posts = $postModel->cache->getPosts();
        
        $response = array(
            'succes' => true,
            'total' => count($posts)
        );
        
        foreach ($posts as $post) {
            
            $author = $userModel->cache->getUser(array(
                'user_id' => $post['author_id']
            ));
            
            $category = $categoryModel->cache->getCategory(array(
                'category_id' => $post['category_id']
            ));
            
            $response['posts'][] = array(
                'ID' => $post['post_id'],
                'title' => $post['title'],
                'author' => array(
                    'ID' => $post['author_id'],
                    'fullName' => $author['full_name']
                ),
                'category' => array(
                    'ID' => $post['category_id'],
                    'title' => $category['title']
                ),
                'status' => $post['status'],
                'postDate' => $post['time_created'],
                'lastUpdate' => $post['last_updated']
            );
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }

    public function addAction()
    {
        $viewModel = new ViewModel();
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $translator = $serviceManager->get('translator');
        
        // Category
        $categoryDropdown = new DropDownList('categoryInput');
        $categoryDataSource = new DataSource();
        $categoryTransportRead = new DataSourceTransportRead();
        $categoryTransportRead->url($this->url()
            ->fromRoute('administrator/categories/default', array(
            'action' => 'read'
        )));
        $categoryTransport = new DataSourceTransport();
        $categoryTransport->read($categoryTransportRead);
        $categorySchema = new DataSourceSchema();
        $categorySchema->data('categories');
        $categoryDataSource->transport($categoryTransport)->schema($categorySchema);
        $categoryDropdown->dataTextField('title')
            ->dataValueField('ID')
            ->dataSource($categoryDataSource)
            ->attr('name', 'category');
        
        // Status
        $statusDropdown = new DropDownList('statusInput');
        $statusDataSource = new DataSource();
        $statusDataSource->data(array(
            array(
                'text' => $translator->translate('Xuat ban'),
                'value' => 'published'
            ),
            array(
                'text' => $translator->translate('Cho xem lai'),
                'value' => 'pending_review'
            ),
            array(
                'text' => $translator->translate('Ban nhap'),
                'value' => 'draft'
            )
        ));
        $statusDropdown->dataTextField('text')
            ->dataValueField('value')
            ->dataSource($statusDataSource)
            ->attr('name', 'status');
        
        $viewModel->setVariable('categoryDropdown', $categoryDropdown);
        $viewModel->setVariable('statusDropdown', $statusDropdown);
        return $viewModel;
    }

    public function saveAddAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $translator = $serviceManager->get('translator');
        
        if ($this->getRequest()->isPost()) {
            $categoryId = $this->getRequest()->getPost('category', 1);
            $status = $this->getRequest()->getPost('status', 'draft');
            $title = $this->getRequest()->getPost('title');
            $content = $this->getRequest()->getPost('content', '');
            
            $categoryModel = new Category();
            
            if ($categoryModel->cache->isExists($categoryId) && $title && strlen($title) > 0) {
                $postModel = new Post();
                
                // Get author ID
                $userModel = new User();
                $user = $userModel->cache->getUser(array(
                    'email' => $this->auth->getIdentity()
                ));
                
                $timeCreated = new \DateTime();
                
                $post = array(
                    'category_id' => $categoryId,
                    'title' => $title,
                    'status' => $status,
                    'content' => $content,
                    'author_id' => $user['user_id'],
                    'time_created' => $timeCreated->format('Y-m-d h:i:s')
                );
                
                $postModel->createPost($post);
            }
        }
        $this->redirect()->toRoute('administrator/posts');
        
        return $viewModel;
    }

    public function editAction()
    {
        $viewModel = new ViewModel();
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $translator = $serviceManager->get('translator');
        
        // Get post from database
        $postModel = new Post();
        $postId = $this->params('post_id', 1);
        $post = $postModel->getPost(array(
            'post_id' => $postId
        ));
        if ($post) {
            
            // Datetime
            $post['time_created'] = new \DateTime($post['time_created']);
            $post['last_updated'] = new \DateTime($post['last_updated']);
            
            $viewModel->setVariable('post', $post);
            
            // Category
            $categoryDropdown = new DropDownList('categoryInput');
            $categoryDataSource = new DataSource();
            $categoryTransportRead = new DataSourceTransportRead();
            $categoryTransportRead->url($this->url()
                ->fromRoute('administrator/categories/default', array(
                'action' => 'read'
            )));
            $categoryTransport = new DataSourceTransport();
            $categoryTransport->read($categoryTransportRead);
            $categorySchema = new DataSourceSchema();
            $categorySchema->data('categories');
            $categoryDataSource->transport($categoryTransport)->schema($categorySchema);
            $categoryDropdown->dataTextField('title')
                ->dataValueField('ID')
                ->dataSource($categoryDataSource)
                ->attr('name', 'category')
                ->value($post['category_id']);
            
            // Status
            $statusDropdown = new DropDownList('statusInput');
            $statusDataSource = new DataSource();
            $statusDataSource->data(array(
                array(
                    'text' => $translator->translate('Xuat ban'),
                    'value' => 'published'
                ),
                array(
                    'text' => $translator->translate('Cho xem lai'),
                    'value' => 'pending_review'
                ),
                array(
                    'text' => $translator->translate('Ban nhap'),
                    'value' => 'draft'
                )
            ));
            $statusDropdown->dataTextField('text')
                ->dataValueField('value')
                ->dataSource($statusDataSource)
                ->attr('name', 'status')
                ->value($post['status']);
            
            $viewModel->setVariable('categoryDropdown', $categoryDropdown);
            $viewModel->setVariable('statusDropdown', $statusDropdown);
        } else {
            $this->redirect()->toRoute('administrator/posts');
        }
        
        return $viewModel;
    }

    public function saveEditAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $translator = $serviceManager->get('translator');
        
        if ($this->getRequest()->isPost()) {
            
            $categoryId = $this->getRequest()->getPost('category', 1);
            $status = $this->getRequest()->getPost('status', 'draft');
            $title = $this->getRequest()->getPost('title');
            $content = $this->getRequest()->getPost('content', '');
            
            $categoryModel = new Category();
            
            if ($categoryModel->cache->isExists($categoryId) && $title && strlen($title) > 0) {
                $postModel = new Post();
                
                // Get author ID
                $userModel = new User();
                $user = $userModel->cache->getUser(array(
                    'email' => $this->auth->getIdentity()
                ));
                
                $timeCreated = new \DateTime();
                
                $post = array(
                    'category_id' => $categoryId,
                    'title' => $title,
                    'status' => $status,
                    'content' => $content,
                    'author_id' => $user['user_id']
                );
                
                $postModel->updatePost(array(
                    'post_id' => $this->getRequest()
                        ->getPost('ID', 1)
                ), $post);
            }
        }
        $this->redirect()->toRoute('administrator/posts');
        
        return $viewModel;
    }

    public function destroyAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $response = array();
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            
            $postModel = new Post();
            
            if ($postModel->removePost(array(
                'post_id' => $postData['ID']
            ))) {
                $response['success'] = true;
            }
        } else {
            $response['success'] = false;
            $response['errors'] = $serviceManager->get('translator')->translate('Truy cap bi han che');
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }
}
