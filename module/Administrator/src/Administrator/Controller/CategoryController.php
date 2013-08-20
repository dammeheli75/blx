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
use Kendo\UI\Grid;
use Kendo\UI\GridToolbarItem;
use Kendo\UI\GridPageable;
use Kendo\UI\GridPageableMessages;
use Kendo\UI\GridFilterable;
use Kendo\UI\GridFilterableMessages;
use Kendo\UI\GridFilterableOperators;
use Kendo\UI\GridFilterableOperatorsString;
use Kendo\UI\GridEditable;
use Kendo\UI\GridColumn;
use Kendo\UI\GridColumnCommandItem;
use Kendo\Data\DataSource;
use Kendo\Data\DataSourceTransportRead;
use Kendo\Data\DataSourceTransportCreate;
use Kendo\Data\DataSourceTransportUpdate;
use Kendo\Data\DataSourceTransportDestroy;
use Kendo\Data\DataSourceTransport;
use Kendo\Data\DataSourceSchemaModelField;
use Kendo\Data\DataSourceSchemaModel;
use Kendo\Data\DataSourceSchemaModelFieldValidation;
use Kendo\Data\DataSourceSchema;
use Kendo\JavaScriptFunction;
use Zend\Json\Encoder;
use Administrator\Model\Category;
use Zend\Cache\PatternFactory;

class CategoryController extends AbstractActionController
{

    public function indexAction()
    {
        if ($this->acl()->isAllowed('post_category', 'management')) {
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
            if ($this->acl()->isAllowed('post_category', 'create')) {
                $toolbarCreateButton = new GridToolbarItem();
                $toolbarCreateButton->name('create')->text($translator->translate('Tao chuyen muc moi'));
                $grid->addToolbarItem($toolbarCreateButton);
            }
            //
            // Pageable
            //
            $pageable = new GridPageable();
            $pageableMessage = new GridPageableMessages();
            $pageableMessage->display($translator->translate('Hien thi {0}-{1}/{2} chuyen muc'))
                ->_empty($translator->translate('Khong co chuyen muc nao'));
            $pageable->messages($pageableMessage)->pageSize(50);
            $grid->pageable($pageable);
            //
            // Sortable
            //
            $grid->sortable(true);
            //
            // Filterable
            //
            $filterable = new GridFilterable();
            $filterableMessage = new GridFilterableMessages();
            $filterableMessage->info($translator->translate('Loc theo:'))
                ->_and($translator->translate('va'))
                ->_or($translator->translate('hoac'))
                ->filter($translator->translate('Loc'))
                ->clear($translator->translate('Xoa'));
            $filterableOperator = new GridFilterableOperators();
            $filterableOperatorString = new GridFilterableOperatorsString();
            $filterableOperatorString->contains($translator->translate('Co chua'));
            $filterableOperator->string($filterableOperatorString);
            $filterable->messages($filterableMessage)->operators($filterableOperator);
            $grid->filterable($filterable);
            //
            // Editable
            //
            $editable = new GridEditable();
            $editable->mode('popup')->confirmation($translator->translate('Co chac chan muon xoa chuyen muc nay?'));
            $grid->editable($editable);
            //
            // Columns
            //
            // ID
            $idColumn = new GridColumn();
            $idColumn->field('ID')
                ->title($translator->translate('#'))
                ->width(80)
                ->attributes(' style="text-align: center"')
                ->filterable(false);
            $grid->addColumn($idColumn);
            // Title
            $titleColumn = new GridColumn();
            $titleColumn->field('title')
                ->title($translator->translate('Ten chuyen muc'))
                ->width(320);
            $grid->addColumn($titleColumn);
            // Description
            $descriptionColumn = new GridColumn();
            $descriptionColumn->field('description')
                ->title($translator->translate('Mo ta'))
                ->width(360)
                ->filterable(false);
            $grid->addColumn($descriptionColumn);
            // Slug
            $slugColumn = new GridColumn();
            $slugColumn->field('slug')
                ->title($translator->translate('Slug'))
                ->width(240)
                ->filterable(false)
                ->editor(new JavaScriptFunction("function (container, options) {
                    $('<input type=\"text\" name=\"slug\" class=\"k-input k-textbox\"  placeholder=\"" . $translator->translate('Neu de trong slug se duoc tao tu dong') . "\" data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container);
                }"));
            $grid->addColumn($slugColumn);
            // PostCount
            $postCountColumn = new GridColumn();
            $postCountColumn->field('postCount')
                ->title($translator->translate('So bai viet'))
                ->width(120)
                ->filterable(false)
                ->attributes(' style="text-align: center"');
            $grid->addColumn($postCountColumn);
            // Command
            if ($this->acl()->isAllowed('post_category', 'update') || $this->acl()->isAllowed('post_category', 'delete')) {
                $commandColumn = new GridColumn();
                $commandColumn->title('&nbsp;');
                if ($this->acl()->isAllowed('post_category', 'update')) {
                    $editCommand = new GridColumnCommandItem();
                    $editCommand->name('edit')->text($translator->translate('Sua'));
                    $commandColumn->addCommandItem($editCommand);
                }
                if ($this->acl()->isAllowed('post_category', 'delete')) {
                    $deleteCommand = new GridColumnCommandItem();
                    $deleteCommand->name('destroy')->text($translator->translate('Xoa'));
                    $commandColumn->addCommandItem($deleteCommand);
                }
                $grid->addColumn($commandColumn);
            }
            //
            // DataSource
            //
            $dataSource = new DataSource();
            $transport = new DataSourceTransport();
            // Transport Read
            if ($this->acl()->isAllowed('post_category', 'read')) {
                $transportRead = new DataSourceTransportRead();
                $transportRead->url($this->url()
                    ->fromRoute('administrator/categories/default', array(
                    'action' => 'read'
                )));
                $transport->read($transportRead);
            }
            // Transport Create
            if ($this->acl()->isAllowed('post_category', 'create')) {
                $transportCreate = new DataSourceTransportCreate();
                $transportCreate->url($this->url()
                    ->fromRoute('administrator/categories/default', array(
                    'action' => 'create'
                )))
                    ->type('POST');
                $transport->create($transportCreate);
            }
            // Transport Update
            if ($this->acl()->isAllowed('post_category', 'update')) {
                $transportUpdate = new DataSourceTransportUpdate();
                $transportUpdate->url($this->url()
                    ->fromRoute('administrator/categories/default', array(
                    'action' => 'update'
                )))
                    ->type('POST');
                $transport->update($transportUpdate);
            }
            // Transport Destroy
            if ($this->acl()->isAllowed('post_category', 'delete')) {
                $transportDestroy = new DataSourceTransportDestroy();
                $transportDestroy->url($this->url()
                    ->fromRoute('administrator/categories/default', array(
                    'action' => 'destroy'
                )))
                    ->type('POST');
                $transport->destroy($transportDestroy);
            }
            $dataSource->transport($transport);
            // Model Fields
            $schemaModel = new DataSourceSchemaModel();
            $schemaModel->id('ID');
            $idModelField = new DataSourceSchemaModelField('ID');
            $idModelField->editable(false)
                ->type('number')
                ->defaultValue(null);
            $schemaModel->addField($idModelField);
            $titleModelField = new DataSourceSchemaModelField('title');
            $titleModelFieldValidation = new DataSourceSchemaModelFieldValidation();
            $titleModelFieldValidation->required(true);
            $titleModelField->nullable(false)->validation($titleModelFieldValidation);
            $schemaModel->addField($titleModelField);
            $descriptionModelField = new DataSourceSchemaModelField('description');
            $descriptionModelField->type('string');
            $schemaModel->addField($descriptionModelField);
            $slugModelField = new DataSourceSchemaModelField('slug');
            $slugModelField->type('string');
            $schemaModel->addField($slugModelField);
            $postCountModelField = new DataSourceSchemaModelField('postCount');
            $postCountModelFieldValidation = new DataSourceSchemaModelFieldValidation();
            $postCountModelFieldValidation->required(true);
            $postCountModelField->type('number')
                ->editable(false)
                ->defaultValue(0)
                ->validation($postCountModelFieldValidation);
            $schemaModel->addField($postCountModelField);
            // Schema
            $schema = new DataSourceSchema();
            $schema->data('categories')
                ->total('total')
                ->model($schemaModel);
            $dataSource->schema($schema);
            $grid->dataSource($dataSource);
            
            $viewModel->setVariable('grid', $grid);
            return $viewModel;
        } else {
            // Redirect
            $this->redirect()->toRoute('restriction-access');
        }
    }

    public function readAction()
    {
        if ($this->acl()->isAllowed('post_category', 'read')) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $serviceManager = $this->getEvent()
                ->getApplication()
                ->getServiceManager();
            $response = array();
            
            $categoryModel = new Category();
            
            $categories = $categoryModel->cache->getCategories();
            
            $response = array(
                'succes' => true,
                'total' => count($categories)
            );
            
            foreach ($categories as $category) {
                $response['categories'][] = array(
                    'ID' => $category['category_id'],
                    'title' => $category['title'],
                    'description' => $category['description'],
                    'slug' => $category['slug'],
                    'postCount' => $category['post_count']
                );
            }
            
            $viewModel->setVariable('response', Encoder::encode($response));
            $this->getResponse()
                ->getHeaders()
                ->addHeaderLine('Content-Type', 'application/json');
            return $viewModel;
        } else {
            // Redirect
            $this->redirect()->toRoute('restriction-access');
        }
    }

    public function createAction()
    {
        if ($this->acl()->isAllowed('post_category', 'create')) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $serviceManager = $this->getEvent()
                ->getApplication()
                ->getServiceManager();
            $response = array();
            
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPost();
                
                $categoryModel = new Category();
                
                $slug = $postData['slug'];
                $timeCreated = new \DateTime();
                
                $stringUtilityCache = PatternFactory::factory('class', array(
                    'class' => 'Blx\Utility\String',
                    'storage' => \Blx\Cache\StorageFactory::factory(array(
                        'ttl' => 0,
                        'namespace' => 'StringUtility'
                    ))
                ));
                
                $category = array(
                    'title' => $postData['title'],
                    'description' => $postData['description'],
                    'time_created' => $timeCreated->format('Y-m-d h:i:s')
                );
                
                // 'slug' => $stringUtilityCache->seoUrl($slug)
                if ($slug && strlen($slug) > 0) {
                    $category['slug'] = $stringUtilityCache->seoUrl($slug);
                } else {
                    $category['slug'] = $stringUtilityCache->seoUrl($postData['title']);
                }
                
                if ($categoryModel->createCategory($category)) {
                    $response['success'] = true;
                    $response['insert_id'] = $categoryModel->getLastInsertValue();
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
        } else {
            // Redirect
            $this->redirect()->toRoute('restriction-access');
        }
    }

    public function updateAction()
    {
        if ($this->acl()->isAllowed('post_category', 'update')) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $serviceManager = $this->getEvent()
                ->getApplication()
                ->getServiceManager();
            $response = array();
            
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPost();
                
                $categoryModel = new Category();
                
                $slug = $postData['slug'];
                $timeCreated = new \DateTime();
                $stringUtilityCache = PatternFactory::factory('class', array(
                    'class' => 'Blx\Utility\String',
                    'storage' => \Blx\Cache\StorageFactory::factory(array(
                        'ttl' => 0,
                        'namespace' => 'StringUtility'
                    ))
                ));
                
                $category = array(
                    'title' => $postData['title'],
                    'description' => $postData['description']
                );
                
                // 'slug' => $stringUtilityCache->seoUrl($slug)
                if ($slug && strlen($slug) > 0) {
                    $category['slug'] = $stringUtilityCache->seoUrl($slug);
                } else {
                    $category['slug'] = $stringUtilityCache->seoUrl($postData['title']);
                }
                
                if ($categoryModel->updateCategory(array(
                    'category_id' => $postData['ID']
                ), $category)) {
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
        } else {
            // Redirect
            $this->redirect()->toRoute('restriction-access');
        }
    }

    public function destroyAction()
    {
        if ($this->acl()->isAllowed('post_category', 'delete')) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $serviceManager = $this->getEvent()
                ->getApplication()
                ->getServiceManager();
            $response = array();
            
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPost();
                
                $categoryModel = new Category();
                
                if ($categoryModel->removeCategory(array(
                    'category_id' => $postData['ID']
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
        } else {
            // Redirect
            $this->redirect()->toRoute('restriction-access');
        }
    }
}
