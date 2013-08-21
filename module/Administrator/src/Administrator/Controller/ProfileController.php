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
use Kendo\UI\GridEditable;
use Kendo\JavaScriptFunction;
use Kendo\UI\GridPageable;
use Kendo\UI\GridPageableMessages;
use Kendo\UI\GridFilterable;
use Kendo\UI\GridFilterableMessages;
use Kendo\UI\GridFilterableOperators;
use Kendo\UI\GridFilterableOperatorsString;
use Kendo\UI\GridFilterableOperatorsDate;
use Kendo\UI\GridToolbarItem;
use Kendo\UI\GridColumn;
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
use Zend\Json\Encoder;
use Administrator\Model\Profile;
use Administrator\Model\Venue;
use Administrator\Model\User;

class ProfileController extends AbstractActionController
{

    public function indexAction()
    {
        if ($this->acl()->isAllowed('profile', 'management') && $this->acl()->isAllowed('profile', 'read')) {
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
                ->width(50)
                ->attributes(' style="text-align:center"')
                ->filterable(false);
            $grid->addColumn($idColumn);
            // Full Name
            $fullNameColumn = new GridColumn();
            $fullNameColumn->field('fullName')
                ->title($translator->translate('Ho va ten'))
                ->width(160)
                ->template(new JavaScriptFunction("function (dataItem) {
                    return '<a class=\"tooltip-cell\" data-toggle=\"tooltip\" title=\'" . $translator->translate('Nam sinh:') . "&nbsp;' + kendo.toString(dataItem.birthday, \"dd/MM/yyyy\") + '\'>' + dataItem.fullName + '</a>';
                }"));
            $grid->addColumn($fullNameColumn);
            // Birthday
            $birthdayColumn = new GridColumn();
            $birthdayColumn->field('birthday')
                ->title($translator->translate('Nam sinh'))
                ->width(100)
                ->filterable(false)
                ->hidden(true)
                ->template(new JavaScriptFunction("function (dataItem) {
                    if (dataItem.birthday) {
                        return kendo.toString(dataItem.birthday, 'dd/MM/yyyy');
                    } else {
                        return '--';
                    }
                }"))
                ->editor(new JavaScriptFunction("function (container, options) {
                    $('<input style=\"border-radius: 0\" data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container)
                        .kendoDatePicker({
                            // defines when the calendar should return date
                            depth: 'year',
                            format: 'dd/MM/yyyy'
                        });
                }"));
            $grid->addColumn($birthdayColumn);
            // Address
            $addressColumn = new GridColumn();
            $addressColumn->field('address')
                ->title($translator->translate('Dia chi'))
                ->width(220)
                ->filterable(false);
            $grid->addColumn($addressColumn);
            // Phone Number
            $phoneNumberColumn = new GridColumn();
            $phoneNumberColumn->field('phoneNumber')
                ->title($translator->translate('Dien thoai'))
                ->width(110)
                ->filterable(false);
            $grid->addColumn($phoneNumberColumn);
            // Collaborator
            $collaboratorColumn = new GridColumn();
            $collaboratorColumn->field('collaborator')
                ->title($translator->translate('CTV'))
                ->width(110)
                ->filterable(false)
                ->template(new JavaScriptFunction("function (dataItem) {
                if(dataItem.collaborator.title) {
                    return dataItem.collaborator.title;
                } return '--';
        }"))
                ->editor(new JavaScriptFunction("function (container, options) {
                    \$('<input data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            autoBind: true,
                            optionLabel: '" . $translator->translate('Chon cong tac vien') . "',
                            dataTextField: \"title\",
                            dataValueField: \"ID\",
                            dataSource: {
                                transport: {
                                    read: \"{$this->url()->fromRoute('administrator/default', array('controller' => 'collaborator','action' => 'read'))}\"
                                },
                                schema: {
                                    data: 'collaborators'
                                }
                            }
                        });

                }"));
            $grid->addColumn($collaboratorColumn);
            // Test Date
            $testDateColumn = new GridColumn();
            $testDateColumn->field('testDate')
                ->title($translator->translate('Ngay thi'))
                ->width(90)
                ->filterable(false)
                ->attributes(' style="text-align: center"')
                ->template(new JavaScriptFunction("function (dataItem) {
                    if (dataItem.testDate && dataItem.testVenue) {
                        return '<a class=\"tooltip-cell\" data-toggle=\"tooltip\" title=\"" . $translator->translate('Dia diem:') . "&nbsp;'+dataItem.testVenue.title+'\">'+kendo.toString(dataItem.testDate, \"dd/MM/yyyy\")+'</a>';
                    } else {
                        return '--';
                    }

                }"))
                ->editor(new JavaScriptFunction("function (container, options) {
                    $('<input style=\"border-radius: 0\" data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container)
                        .kendoDatePicker({
                            // defines when the calendar should return date
                            depth: 'year',
                            format: 'dd/MM/yyyy'
                        });
                }"));
            $grid->addColumn($testDateColumn);
            // Test Venue
            $testVenueColumn = new GridColumn();
            $testVenueColumn->field('testVenue')
                ->title($translator->translate('Dia diem thi'))
                ->filterable(false)
                ->hidden(true)
                ->template(new JavaScriptFunction("function (dataItem) {
                if (dataItem.testVenue)
                    return dataItem.testVenue.title;
                return '--';
            }"))
                ->editor(new JavaScriptFunction("function (container, options) {
                    \$('<input data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            optionLabel: '" . $translator->translate('Chon dia diem thi') . "',
                            dataTextField: \"title\",
                            dataValueField: \"ID\",
                            dataSource: {
                                transport: {
                                    read: '{$this->url()->fromRoute('administrator/default', array('controller' => 'venue','action' => 'read'))}'
                                },
                                schema: {
                                    data: 'venues'
                                }
                            }
                        });

                }"));
            $grid->addColumn($testVenueColumn);
            // Test Status
            $testStatusColumn = new GridColumn();
            $testStatusColumn->field('testStatus')
                ->title($translator->translate('Thi'))
                ->filterable(false)
                ->width(80)
                ->attributes(' style="text-align: center"')
                ->template(new JavaScriptFunction("function (dataItem) {
                    switch (dataItem.testStatus) {
                        case 'fail_ theoretical':
                            return '" . $translator->translate('Truot LT') . "';
                            break;
                        case 'pass':
                            return '" . $translator->translate('Dat') . "';
                            break;
                        case 'absence':
                            return '" . $translator->translate('Vang mat') . "';
                            break;
                        case 'fail_practice':
                            return '" . $translator->translate('Truot TH') . "';
                            break;
                        default:
                            return \"--\";
                            break;
                    }
                }"))
                ->editor(new JavaScriptFunction("function (container, options) {
                    $('<input data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            optionLabel: '" . $translator->translate('Chon ket qua thi') . "',
                            dataTextField: \"title\",
                            dataValueField: \"value\",
                            dataSource: [
                                {
                                    title: '" . $translator->translate('Truot ly thuyet') . "',
                                    value: 'fail_ theoretical'
                                },
                                {
                                    title: '" . $translator->translate('Dat') . "',
                                    value: 'pass'
                                },
                                {
                                    title: '" . $translator->translate('Vang mat') . "',
                                    value: 'absence'
                                },
                                {
                                    title: '" . $translator->translate('Truot thuc hanh') . "',
                                    value: 'fail_practice'
                                }

                            ]
                        });

                }"));
            $grid->addColumn($testStatusColumn);
            // License Front
            $licenseFrontColumn = new GridColumn();
            $licenseFrontColumn->field('licenseFront')
                ->title($translator->translate('BLX&nbsp;<sup>truoc</sup>'))
                ->width(76)
                ->filterable(false)
                ->sortable(false)
                ->attributes(' style="padding-left: 10px"')
                ->template(new JavaScriptFunction("function (dataItem) {
                    if (dataItem.licenseFront) {
                        return '<img width=\"50\" height=\"32\" src=\"' + dataItem.licenseFront + '\" alt=\'" . $translator->translate('Bang lai xe cua') . "&nbsp;' + dataItem.fullName + '\'>';
                    } else {
                        return \"<span style=\'padding: 20px\'>--</span>\";
                    }
                }"));
            $grid->addColumn($licenseFrontColumn);
            // Note
            $noteColumn = new GridColumn();
            $noteColumn->field('note')
                ->title($translator->translate('Ghi chu'))
                ->filterable(false)
                ->sortable(false);
            $grid->addColumn($noteColumn);
            // Command
            if ($this->acl()->isAllowed('profile', 'update') || $this->acl()->isAllowed('profile', 'delete')) {
                $commandColumn = new GridColumn();
                if ($this->acl()->isAllowed('profile', 'update')) {
                    $editCommand = new GridColumnCommandItem();
                    $editCommand->name('edit')->text($translator->translate('Sua'));
                    $commandColumn->addCommandItem($editCommand);
                }
                if ($this->acl()->isAllowed('profile', 'delete')) {
                    $destroyCommand = new GridColumnCommandItem();
                    $destroyCommand->name('destroy')->text($translator->translate('Xoa'));
                    $commandColumn->addCommandItem($destroyCommand);
                }
                $commandColumn->title('&nbsp;')
                    ->width(160)
                    ->attributes(' style="padding-left: 15px; text-align: center;"');
                $grid->addColumn($commandColumn);
            }
            //
            // Height
            //
            $grid->height(new JavaScriptFunction("function () { return $(window).height() - 66;}"));
            //
            // Pageable
            //
            $pageableMessage = new GridPageableMessages();
            $pageableMessage->display($translator->translate("Hien thi {0}-{1}/{2} ho so"));
            $pageable = new GridPageable();
            $pageable->pageSize(50)->messages($pageableMessage);
            $grid->pageable($pageable);
            //
            // Sortable
            //
            $grid->sortable(true);
            //
            // Resizable
            //
            $grid->resizable(true);
            //
            // Filterable
            //
            $filterableMessage = new GridFilterableMessages();
            $filterableMessage->info($translator->translate('Dieu kien loc'))
                ->filter($translator->translate('Loc'))
                ->clear($translator->translate('Xoa'))
                ->isFalse($translator->translate('False'))
                ->isTrue($translator->translate('True'));
            $filterableOperatorString = new GridFilterableOperatorsString();
            $filterableOperatorString->contains($translator->translate('Co chua'));
            $filterableOperatorDate = new GridFilterableOperatorsDate();
            $filterableOperatorDate->gt($translator->translate('Sau ngay'))
                ->lt($translator->translate('Truoc ngay'))
                ->eq($translator->translate('La ngay'));
            $filterableOperator = new GridFilterableOperators();
            $filterableOperator->string($filterableOperatorString)->date($filterableOperatorDate);
            $filterable = new GridFilterable();
            $filterable->extra(false)
                ->messages($filterableMessage)
                ->operators($filterableOperator);
            $grid->filterable($filterable);
            //
            // Editable
            //
            $editable = new GridEditable();
            $editable->mode('popup')->confirmation($translator->translate('Co chac chan muon xoa?'));
            $grid->editable($editable);
            //
            // Toolbar
            //
            if ($this->acl()->isAllowed('profile', 'create')) {
                $createButtonToolbar = new GridToolbarItem();
                $createButtonToolbar->name('create');
                $createButtonToolbar->text($translator->translate('Them ho so'));
                $grid->addToolbarItem($createButtonToolbar);
            }
            //
            // DataSource
            //
            $dataSource = new DataSource();
            $transport = new DataSourceTransport();
            // Transport Read
            if ($this->acl()->isAllowed('profile', 'read')) {
                $transportRead = new DataSourceTransportRead();
                $transportRead->url($this->url()
                    ->fromRoute('administrator/profiles/default', array(
                    'action' => 'read'
                )));
                $transport->read($transportRead);
            }
            // Transport Create
            if ($this->acl()->isAllowed('profile', 'create')) {
                $transportCreate = new DataSourceTransportCreate();
                $transportCreate->url($this->url()
                    ->fromRoute('administrator/profiles/default', array(
                    'action' => 'create'
                )))
                    ->type('POST');
                $transport->create($transportCreate);
            }
            // Transport Update
            if ($this->acl()->isAllowed('profile', 'update')) {
                $transportUpdate = new DataSourceTransportUpdate();
                $transportUpdate->url($this->url()
                    ->fromRoute('administrator/profiles/default', array(
                    'action' => 'update'
                )))
                    ->type('POST');
                $transport->update($transportUpdate);
            }
            // Transport Destroy
            if ($this->acl()->isAllowed('profile', 'delete')) {
                $transportDestroy = new DataSourceTransportDestroy();
                $transportDestroy->url($this->url()
                    ->fromRoute('administrator/profiles/default', array(
                    'action' => 'destroy'
                )))
                    ->type('POST');
                $transport->destroy($transportDestroy);
            }
            $dataSource->transport($transport);
            // Schema
            $schema = new DataSourceSchema();
            $model = new DataSourceSchemaModel();
            $model->id('ID');
            // Schema Model Fields
            $idModelField = new DataSourceSchemaModelField('ID');
            $idModelField->editable(false);
            $model->addField($idModelField);
            $fullNameModelField = new DataSourceSchemaModelField('fullName');
            $fullNameModelFieldValidation = new DataSourceSchemaModelFieldValidation();
            $fullNameModelFieldValidation->required(true);
            $fullNameModelField->nullable(false)->validation($fullNameModelFieldValidation);
            $model->addField($fullNameModelField);
            $birthdayModelField = new DataSourceSchemaModelField('birthday');
            $birthdayModelField->type('date');
            $model->addField($birthdayModelField);
            $addressModelField = new DataSourceSchemaModelField('address');
            $addressModelFieldValidation = new DataSourceSchemaModelFieldValidation();
            $addressModelFieldValidation->required(true);
            $addressModelField->nullable(false)->validation($addressModelFieldValidation);
            $model->addField($addressModelField);
            $phoneNumberModelField = new DataSourceSchemaModelField('phoneNumber');
            $phoneNumberModelFieldValidation = new DataSourceSchemaModelFieldValidation();
            $phoneNumberModelFieldValidation->required(true);
            $phoneNumberModelField->nullable(false)->validation($phoneNumberModelFieldValidation);
            $model->addField($phoneNumberModelField);
            $testStatusModelField = new DataSourceSchemaModelField('testStatus');
            $model->addField($testStatusModelField);
            $testDateModelField = new DataSourceSchemaModelField('testDate');
            $testDateModelField->type('date');
            $testDateModelField->defaultValue(null);
            $model->addField($testDateModelField);
            $testVenueModelField = new DataSourceSchemaModelField('testVenue');
            $model->addField($testVenueModelField);
            $collaboratorModelField = new DataSourceSchemaModelField('collaborator');
            $model->addField($collaboratorModelField);
            $licenseFrontModelField = new DataSourceSchemaModelField('licenseFront');
            $licenseFrontModelField->type('string');
            $model->addField($licenseFrontModelField);
            $noteModelField = new DataSourceSchemaModelField('note');
            $noteModelField->type('string');
            $model->addField($noteModelField);
            
            $schema->total('total')
                ->data('profiles')
                ->model($model);
            $dataSource->schema($schema);
            $grid->dataSource($dataSource);
            
            // Events
            $grid->dataBound(new JavaScriptFunction("function () {
            // Tooltip Activate
            $('[data-toggle=\"tooltip\"]').tooltip({
                placement: \"right\"
            });

            var self = this;
            var quickSearch = $('form#quickSearch');
            quickSearch.find('input[name=\"q\"]').on('change keydown paste input', function () {
                // Filter
                var q = $(this).val();
                if (q != lastQuickSearch) {
                    self.dataSource.filter({
                        logic: \"or\",
                        filters: [
                            {
                                field: \"fullName\",
                                operator: \"contains\",
                                value: q
                            },
                            {
                                field: \"phoneNumber\",
                                operator: \"contains\",
                                value: q
                            }
                        ]});

                    lastQuickSearch = q;
                }
            });

            quickSearch.submit(function () {
                // Filter
                var q = $(this).find('input[name=\"q\"]').val();

                if (q != lastQuickSearch) {
                    self.dataSource.filter({
                        logic: \"or\",
                        filters: [
                            {
                                field: \"fullName\",
                                operator: \"contains\",
                                value: q
                            },
                            {
                                field: \"phoneNumber\",
                                operator: \"contains\",
                                value: q
                            }
                        ]});

                    lastQuickSearch = q;
                }

                return false;
            });
        }"));
            $viewModel->setVariable('grid', $grid);
            return $viewModel;
        } else {
            // Redirect
            $this->redirect()->toRoute('restriction-access');
        }
    }

    public function readAction()
    {
        if ($this->acl()->isAllowed('profile', 'read')) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $serviceManager = $this->getEvent()
                ->getApplication()
                ->getServiceManager();
            $response = array();
            
            $profileModel = new Profile();
            $userModel = new User();
            $venueModel = new Venue();
            
            $profiles = $profileModel->cache->getProfiles();
            
            $response = array(
                'succes' => true,
                'total' => count($profiles)
            );
            
            foreach ($profiles as $profile) {
                $collaborator = $userModel->cache->getUser(array(
                    'user_id' => $profile['collaborator_id']
                ));
                $venue = $profile['test_venue_id'] ? $venueModel->cache->getVenue(array(
                    'venue_id' => $profile['test_venue_id']
                )) : NULL;
                
                $response['profiles'][] = array(
                    'ID' => $profile['profile_id'],
                    'fullName' => $profile['full_name'],
                    'birthday' => $profile['birthday'],
                    'address' => $profile['address'],
                    'phoneNumber' => $profile['phone_number'],
                    'collaborator' => array(
                        'ID' => $profile['collaborator_id'],
                        'title' => $collaborator['full_name']
                    ),
                    'testStatus' => $profile['test_status'],
                    'testDate' => $profile['test_date'],
                    'testVenue' => ($profile['test_venue_id'] && $venue) ? array(
                        'ID' => $profile['test_venue_id'],
                        'title' => $venue['title']
                    ) : NULL,
                    'licenseFront' => $profile['license_front'],
                    'note' => $profile['note']
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

    /**
     *
     * @todo Kiem duyet thong tin
     * @return \Zend\View\Model\ViewModel
     */
    public function createAction()
    {
        if ($this->acl()->isAllowed('profile', 'create')) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $serviceManager = $this->getEvent()
                ->getApplication()
                ->getServiceManager();
            $response = array();
            
            if ($this->getRequest()->isPost()) {
                $fullName = $this->getRequest()->getPost('fullName');
                $birthday = $this->getRequest()->getPost('birthday');
                $address = $this->getRequest()->getPost('address');
                $collaborator = $this->getRequest()->getPost('collaborator');
                $phoneNumber = $this->getRequest()->getPost('phoneNumber');
                $testStatus = $this->getRequest()->getPost('testStatus');
                $testVenue = $this->getRequest()->getPost('testVenue');
                $testDate = $this->getRequest()->getPost('testDate');
                $licenseFront = $this->getRequest()->getPost('licenseFront');
                $note = $this->getRequest()->getPost('note');
                
                $profileModel = new Profile();
                $userModel = new User();
                $venueModel = new Venue();
                
                $birthday = strlen($birthday) > 0 ? \DateTime::createFromFormat('D M d Y H:i:s e+', $birthday) : NULL;
                $testDate = strlen($testDate) > 0 ? \DateTime::createFromFormat('D M d Y H:i:s e+', $testDate) : NULL;
                $timeCreated = new \DateTime();
                
                $profile = array(
                    'full_name' => $fullName,
                    'birthday' => $birthday ? $birthday->format('Y-m-d') : NULL,
                    'address' => $address,
                    'phone_number' => $phoneNumber,
                    'phone_onlysms' => false,
                    'test_status' => $testStatus,
                    'test_date' => $testDate ? $testDate->format('Y-m-d') : NULL,
                    'license_front' => $licenseFront,
                    'note' => $note,
                    'time_created' => $timeCreated->format('Y-m-d h:i:s')
                );
                
                if ($userModel->isExists($collaborator)) {
                    $profile['collaborator_id'] = $collaborator;
                }
                
                if ($venueModel->cache->isExists($testVenue)) {
                    $profile['test_venue_id'] = $testVenue;
                }
                
                if (! $profileModel->isExists(array(
                    'full_name' => $fullName,
                    'address' => $address,
                    'phone_number' => $phoneNumber
                ))) {
                    if ($profileModel->createProfile($profile)) {
                        $response['success'] = true;
                        $response['insert_id'] = $profileModel->getLastInsertValue();
                    }
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
        if ($this->acl()->isAllowed('profile', 'update')) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $serviceManager = $this->getEvent()
                ->getApplication()
                ->getServiceManager();
            $response = array();
            
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPost();
                
                $profileModel = new Profile($serviceManager);
                $userModel = new User();
                $venueModel = new Venue($serviceManager);
                
                $birthday = \DateTime::createFromFormat('D M d Y H:i:s e+', $postData['birthday']);
                
                $timeCreated = new \DateTime();
                
                $profile = array(
                    'full_name' => $postData['fullName'],
                    'birthday' => $birthday->format('Y-m-d'),
                    'address' => $postData['address'],
                    'phone_number' => $postData['phoneNumber'],
                    'phone_onlysms' => false,
                    'test_status' => $postData['testStatus'],
                    'license_front' => $postData['licenseFront'],
                    'note' => $postData['note']
                );
                
                if ($userModel->cache->isExists($postData['collaborator']['ID'])) {
                    $profile['collaborator_id'] = $postData['collaborator']['ID'];
                }
                
                if (is_array($postData['testVenue'])) {
                    if ($venueModel->cache->isExists($postData['testVenue']['ID'])) {
                        $profile['test_venue_id'] = $postData['testVenue']['ID'];
                    }
                } else {
                    $profile['test_venue_id'] = NULL;
                }
                
                if (strlen($postData['testDate']) > 0) {
                    $testDate = \DateTime::createFromFormat('D M d Y H:i:s e+', $postData['testDate']);
                    $profile['test_date'] = $testDate->format('Y-m-d');
                } else {
                    $profile['test_date'] = NULL;
                }
                
                if ($profileModel->cache->isExists($postData['ID'])) {
                    if ($profileModel->updateProfile(array(
                        'profile_id' => $postData['ID']
                    ), $profile)) {
                        $response['success'] = true;
                    }
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
        if ($this->acl()->isAllowed('profile', 'delete')) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $serviceManager = $this->getEvent()
                ->getApplication()
                ->getServiceManager();
            $response = array();
            
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPost();
                
                $profileModel = new Profile($serviceManager);
                
                if ($profileModel->cache->isExists($postData['ID'])) {
                    if ($profileModel->removeProfile(array(
                        'profile_id' => $postData['ID']
                    ))) {
                        $response['success'] = true;
                    }
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
