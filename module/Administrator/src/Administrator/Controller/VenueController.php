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
use Zend\Json\Encoder;
use Administrator\Model\Venue;
use Kendo\UI\Grid;
use Kendo\UI\GridColumn;
use Kendo\JavaScriptFunction;
use Kendo\UI\GridColumnCommandItem;
use Kendo\Data\DataSource;
use Kendo\Data\DataSourceTransportRead;
use Kendo\Data\DataSourceTransportCreate;
use Kendo\Data\DataSourceTransportUpdate;
use Kendo\Data\DataSourceTransportDestroy;
use Kendo\Data\DataSourceTransport;
use Kendo\Data\DataSourceSchema;
use Kendo\Data\DataSourceSchemaModel;
use Kendo\Data\DataSourceSchemaModelField;
use Kendo\Data\DataSourceSchemaModelFieldValidation;
use Kendo\UI\GridToolbarItem;
use Kendo\UI\GridEditable;
use Kendo\UI\GridPageable;
use Kendo\UI\GridPageableMessages;

class VenueController extends AbstractActionController
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
        // Columns
        //
        // ID
        $idColumn = new GridColumn();
        $idColumn->field('ID')
            ->title($translator->translate('#'))
            ->width(80)
            ->attributes(' style="text-align:center"');
        $grid->addColumn($idColumn);
        // Title
        $titleColumn = new GridColumn();
        $titleColumn->field('title')
            ->title($translator->translate('Tieu de'))
            ->width(480);
        $grid->addColumn($titleColumn);
        // Longitude
        $longitudeColumn = new GridColumn();
        $longitudeColumn->field('longitude')
            ->title($translator->translate('Kinh do'))
            ->width(180)
            ->attributes(' style="text-align:center"')
            ->editor(new JavaScriptFunction("function (container, options) {
                    \$('<input name=\"longitude\" type=\"text\" class=\"k-input k-textbox\" data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container);
                }"));
        $grid->addColumn($longitudeColumn);
        // Latitude
        $latitudeColumn = new GridColumn();
        $latitudeColumn->field('latitude')
            ->title($translator->translate('Vi do'))
            ->width(180)
            ->attributes(' style="text-align:center"')
            ->editor(new JavaScriptFunction("function (container, options) {
                    \$('<input name=\"latitude\" type=\"text\" class=\"k-input k-textbox\" data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container);
                }"));
        $grid->addColumn($latitudeColumn);
        // Command
        $commandColumn = new GridColumn();
        $editCommand = new GridColumnCommandItem();
        $editCommand->name('edit')->text($translator->translate('Sua'));
        $destroyCommand = new GridColumnCommandItem();
        $destroyCommand->name('destroy')->text($translator->translate('Xoa'));
        $commandColumn->addCommandItem($editCommand)
            ->addCommandItem($destroyCommand)
            ->title('&nbsp;');
        $grid->addColumn($commandColumn);
        //
        // DataSource
        //
        $dataSource = new DataSource();
        // Transport Read
        $transportRead = new DataSourceTransportRead();
        $transportRead->url($this->url()
            ->fromRoute('administrator/venues/default', array(
            'action' => 'read'
        )));
        // Transport Create
        $transportCreate = new DataSourceTransportCreate();
        $transportCreate->url($this->url()
            ->fromRoute('administrator/venues/default', array(
            'action' => 'create'
        )))
            ->type('POST');
        // Transport Update
        $transportUpdate = new DataSourceTransportUpdate();
        $transportUpdate->url($this->url()
            ->fromRoute('administrator/venues/default', array(
            'action' => 'update'
        )))
            ->type('POST');
        // Transport Destroy
        $transportDestroy = new DataSourceTransportDestroy();
        $transportDestroy->url($this->url()
            ->fromRoute('administrator/venues/default', array(
            'action' => 'destroy'
        )))
            ->type('POST');
        $transport = new DataSourceTransport();
        $transport->read($transportRead)
            ->create($transportCreate)
            ->update($transportUpdate)
            ->destroy($transportDestroy);
        $dataSource->transport($transport);
        // Schema
        $schema = new DataSourceSchema();
        // Model
        $model = new DataSourceSchemaModel();
        $model->id('ID');
        $idModelField = new DataSourceSchemaModelField('ID');
        $idModelField->editable(false)
            ->defaultValue(null)
            ->type('number');
        $model->addField($idModelField);
        $titleModelField = new DataSourceSchemaModelField('title');
        $titleModelFieldValidation = new DataSourceSchemaModelFieldValidation();
        $titleModelFieldValidation->required(true);
        $titleModelField->type('string')->validation($titleModelFieldValidation);
        $model->addField($titleModelField);
        $longitudeModelField = new DataSourceSchemaModelField('longitude');
        $longitudeModelFieldValidation = new DataSourceSchemaModelFieldValidation();
        $longitudeModelFieldValidation->required(true);
        $longitudeModelField->type('number')->validation($longitudeModelFieldValidation);
        $model->addField($longitudeModelFieldValidation);
        $latitudeModelField = new DataSourceSchemaModelField('latitude');
        $latitudeModelFieldValidation = new DataSourceSchemaModelFieldValidation();
        $latitudeModelFieldValidation->required(true);
        $latitudeModelField->type('number')->validation($latitudeModelFieldValidation);
        $model->addField($latitudeModelField);
        $schema->data('venues')
            ->total('total')
            ->model($model);
        $dataSource->schema($schema);
        $grid->dataSource($dataSource);
        //
        // Editable
        //
        $editable = new GridEditable();
        $editable->mode('inline')->confirmation($translator->translate('Co chac chan muon xoa dia diem nay?'));
        $grid->editable($editable);
        //
        // Sortable
        //
        $grid->sortable(true);
        //
        // Height
        //
        $grid->height(new JavaScriptFunction("function () { return $(window).height() - 66;}"));
        //
        		// Pageable
        //
        $pageableMessage = new GridPageableMessages();
        $pageableMessage->display($translator->translate("Hien thi {0}-{1}/{2} dia diem"));
        $pageable = new GridPageable();
        $pageable->pageSize(50)->messages($pageableMessage);
        $grid->pageable($pageable);
        //
        // Toolbar
        //
        $createButtonToolbar = new GridToolbarItem();
        $createButtonToolbar->name('create');
        $createButtonToolbar->text($translator->translate('Them dia diem'));
        $grid->addToolbarItem($createButtonToolbar);
        
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
        
        $venueModel = new Venue($serviceManager);
        
        $venues = $venueModel->cache->getVenues();
        
        $response = array(
            'success' => true,
            'total' => count($venues)
        );
        
        foreach ($venues as $venue) {
            $response['venues'][] = array(
                'ID' => $venue['venue_id'],
                'title' => $venue['title'],
                'longitude' => $venue['longitude'],
                'latitude' => $venue['latitude']
            );
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }

    public function createAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $response = array();
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            
            $venueModel = new Venue();
            $timeCreated = new \DateTime();
            
            $venue = array(
                'title' => $postData['title'],
                'longitude' => $postData['longitude'],
                'latitude' => $postData['latitude'],
                'time_created' => $timeCreated->format('Y-m-d h:i:s')
            );
            
            if ($venueModel->createVenue($venue)) {
                $response['success'] = true;
                $response['insert_id'] = $venueModel->getLastInsertValue();
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

    public function updateAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $serviceManager = $this->getEvent()
            ->getApplication()
            ->getServiceManager();
        $response = array();
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            
            $venueModel = new Venue();
            
            $user = array(
                'title' => $postData['title'],
                'longitude' => $postData['longitude'],
                'latitude' => $postData['latitude']
            );
            
            if ($venueModel->updateVenue(array(
                'venue_id' => $postData['ID']
            ), $user)) {
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
            
            $venueModel = new Venue();
            
            if ($venueModel->removeVenue(array(
                'venue_id' => $postData['ID']
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
