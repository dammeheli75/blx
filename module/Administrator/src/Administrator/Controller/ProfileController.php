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
use Administrator\Model\Collaborator;
use Administrator\Model\Venue;

class ProfileController extends AbstractActionController
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
            ->width(50)
            ->attributes(' style="text-align:center"')
            ->filterable(false);
        $grid->addColumn($idColumn);
        // Full Name
        $fullNameColumn = new GridColumn();
        $fullNameColumn->field('fullName')
            ->title($translator->translate('Ho va ten'))
            ->width(160)
            ->filterable(false)
            ->template(new JavaScriptFunction("function (dataItem) {
                    return '<a class=\"tooltip-cell\" data-toggle=\"tooltip\" title=\'" . $translator->translate('Nam sinh:') . "&nbsp;' + kendo.toString(dataItem.birthday, \"dd/MM/yyyy\") + '\'>' + dataItem.fullName + '</a>';
                }"));
        $grid->addColumn($fullNameColumn);
        // Birthday
        $birthdayColumn = new GridColumn();
        $birthdayColumn->field('birthday')
            ->title($translator->translate('Nam sinh'))
            ->hidden(true)
            ->filterable(false)
            ->editor(new JavaScriptFunction("function (container, options) {
                    $('<input name=\"birthday\" style=\"border-radius: 0\" data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container)
                        .kendoDatePicker({
                            // defines when the calendar should return date
                            depth: \"year\",

                            // display month and year in the input
                            format: \"dd/MM/yyyy\"
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
            return dataItem.collaborator.title;
        }"))
            ->editor(new JavaScriptFunction("function (container, options) {
                    \$('<input data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            optionLabel: '" . $translator->translate('Chon cong tac vien') . "',
                            dataTextField: \"title\",
                            dataValueField: \"ID\",
                            dataSource: {
                                transport: {
                                    read: \"http://localhost/blx/public/administrator/collaborator/read\"
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
            ->format('{0:dd/MM/yyyy}')
            ->template(new JavaScriptFunction("function (dataItem) {
                    if (dataItem.testDate) {
                        return kendo.toString(dataItem.testDate, \"dd/MM/yyyy\");
                    } else {
                        return '--';
                    }

                }"))
            ->editor(new JavaScriptFunction("function (container, options) {
                    $('<input style=\"border-radius: 0\" data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container)
                        .kendoDatePicker({
                            // defines when the calendar should return date
                            depth: \"year\",

                            // display month and year in the input
                            format: \"dd/MM/yyyy\"
                        });
                }"));
        $grid->addColumn($testDateColumn);
        // Test Venue
        $testVenueColumn = new GridColumn();
        $testVenueColumn->field('testVenue')
            ->title($translator->translate('Dia diem thi'))
            ->filterable(false)
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
                                    read: 'http://localhost/blx/public/administrator/venue/read'
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
            ->attributes(' style="padding-left: 15px"')
            ->template(new JavaScriptFunction("function (dataItem) {
                    if (dataItem.licenseFront) {
                        return '<img width=\"50\" height=\"32\" src=\"' + dataItem.licenseFront + '\" alt=\'" . $translator->translate('Bang lai xe cua') . "&nbsp;' + dataItem.fullName + '\'>';
                    } else {
                        return \"--\";
                    }
                }"));
        $grid->addColumn($licenseFrontColumn);
        // License Back
        $licenseBackColumn = new GridColumn();
        $licenseBackColumn->field('licenseBack')
            ->title($translator->translate('BLX&nbsp;<sup>sau</sup>'))
            ->width(76)
            ->filterable(false)
            ->sortable(false)
            ->attributes(' style="padding-left: 15px"')
            ->template(new JavaScriptFunction("function (dataItem) {
                    if (dataItem.licenseBack) {
                        return '<img width=\"50\" height=\"32\" src=\"' + dataItem.licenseBack + '\" alt=\'" . $translator->translate('Bang lai xe cua') . "&nbsp;' + dataItem.fullName + '\'>';
                    } else {
                        return \"--\";
                    }
                }"));
        $grid->addColumn($licenseBackColumn);
        // Note
        $noteColumn = new GridColumn();
        $noteColumn->field('note')
            ->title($translator->translate('Ghi chu'))
            ->filterable(false)
            ->sortable(false);
        $grid->addColumn($noteColumn);
        // Command
        $commandColumn = new GridColumn();
        $editCommand = new GridColumnCommandItem();
        $editCommand->name('edit')->text($translator->translate('Sua'));
        $destroyCommand = new GridColumnCommandItem();
        $destroyCommand->name('destroy')->text($translator->translate('Xoa'));
        $commandColumn->addCommandItem($editCommand)->addCommandItem($destroyCommand);
        $commandColumn->title('&nbsp;')
            ->width(160)
            ->attributes(' style="padding-left: 15px"');
        $grid->addColumn($commandColumn);
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
        $createButtonToolbar = new GridToolbarItem();
        $createButtonToolbar->name('create');
        $createButtonToolbar->text($translator->translate('Them ho so'));
        $grid->addToolbarItem($createButtonToolbar);
        //
        // DataSource
        //
        $dataSource = new DataSource();
        // Transport Read
        $transportRead = new DataSourceTransportRead();
        $transportRead->url('http://localhost/blx/public/administrator/profiles/read');
        // Transport Create
        $transportCreate = new DataSourceTransportCreate();
        $transportCreate->url('http://localhost/blx/public/administrator/profiles/create')->type('POST');
        // Transport Update
        $transportUpdate = new DataSourceTransportUpdate();
        $transportUpdate->url('http://localhost/blx/public/administrator/profiles/update')->type('POST');
        // Transport Destroy
        $transportDestroy = new DataSourceTransportDestroy();
        $transportDestroy->url('http://localhost/blx/public/administrator/profiles/destroy')->type('POST');
        $transport = new DataSourceTransport();
        $transport->read($transportRead)
            ->create($transportCreate)
            ->update($transportUpdate)
            ->destroy($transportDestroy);
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
        $birthdayModelFieldValidation = new DataSourceSchemaModelFieldValidation();
        $birthdayModelFieldValidation->required(true);
        $birthdayModelField->nullable(false)->validation($birthdayModelFieldValidation);
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
        $model->addField($testDateModelField);
        $testVenueModelField = new DataSourceSchemaModelField('testVenue');
        $model->addField($testVenueModelField);
        $collaboratorModelField = new DataSourceSchemaModelField('collaborator');
        $model->addField($collaboratorModelField);
        $licenseFrontModelField = new DataSourceSchemaModelField('licenseFront');
        $licenseFrontModelField->type('string');
        $model->addField($licenseFrontModelField);
        $licenseBackModelField = new DataSourceSchemaModelField('licenseBack');
        $licenseBackModelField->type('string');
        $model->addField($licenseBackModelField);
        $noteModelField = new DataSourceSchemaModelField('note');
        $noteModelField->type('string');
        $model->addField($noteModelField);
        
        $schema->total('total')
            ->data('profiles')
            ->model($model);
        $dataSource->schema($schema);
        $grid->dataSource($dataSource);
        
        // Events
        $grid->dataBound(new JavaScriptFunction("function () { \$('[data-toggle=\"tooltip\"]').tooltip({placement: \"right\"});}"));
        
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
        
        $profileModel = new Profile($serviceManager);
        $collaboratorModel = new Collaborator($serviceManager);
        $venueModel = new Venue($serviceManager);
        
        $profiles = $profileModel->getProfiles();
        
        $response = array(
            'succes' => true,
            'total' => count($profiles)
        );
        
        foreach ($profiles as $profile) {
            $collaborator = $collaboratorModel->getCollaborator(array(
                'collaborator_id' => $profile->collaborator_id
            ));
            $venue = $profile->test_venue_id ? $venueModel->getVenue(array(
                'venue_id' => $profile->test_venue_id
            )) : NULL;
            
            $response['profiles'][] = array(
                'ID' => $profile->profile_id,
                'fullName' => $profile->full_name,
                'birthday' => $profile->birthday,
                'address' => $profile->address,
                'phoneNumber' => $profile->phone_number,
                'collaborator' => array(
                    'ID' => $profile->collaborator_id,
                    'title' => $collaborator->title
                ),
                'testStatus' => $profile->test_status,
                'testDate' => $profile->test_date,
                'testVenue' => ($profile->test_venue_id && $venue) ? array(
                    'ID' => $profile->test_venue_id,
                    'title' => $venue->title
                ) : NULL,
                'licenseFront' => $profile->license_front,
                'licenseBack' => $profile->license_back,
                'note' => $profile->note
            );
        }
        
        $viewModel->setVariable('response', Encoder::encode($response));
        $this->getResponse()
            ->getHeaders()
            ->addHeaderLine('Content-Type', 'application/json');
        return $viewModel;
    }

    /**
     *
     * @todo Kiem duyet thong tin
     * @return \Zend\View\Model\ViewModel
     */
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
            
            $profileModel = new Profile($serviceManager);
            $collaboratorModel = new Collaborator($serviceManager);
            $venueModel = new Venue($serviceManager);
            
            $birthday = new \DateTime($postData['birthday']);
            $testDate = new \DateTime($postData['testDate']);
            $timeCreated = new \DateTime();
            
            $profile = array(
                'full_name' => $postData['fullName'],
                'birthday' => $birthday->format('Y-m-d'),
                'address' => $postData['address'],
                'phone_number' => $postData['phoneNumber'],
                'phone_onlysms' => false,
                'test_status' => $postData['testStatus'],
                'test_date' => $testDate->format('Y-m-d'),
                'license_front' => $postData['licenseFront'],
                'license_back' => $postData['licenseBack'],
                'note' => $postData['note']
            );
            
            if ($collaboratorModel->isExists($postData['collaborator'])) {
                $profile['collaborator_id'] = $postData['collaborator'];
            }
            
            if (is_array($postData['testVenue'])) {
                if ($venueModel->isExists($postData['testVenue']['ID'])) {
                    $profile['test_venue_id'] = $postData['testVenue']['ID'];
                }
            }
            
            if ($profileModel->createProfile($profile)) {
                $response['success'] = true;
                $response['insert_id'] = $profileModel->getLastInsertValue();
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
            
            $profileModel = new Profile($serviceManager);
            $collaboratorModel = new Collaborator($serviceManager);
            $venueModel = new Venue($serviceManager);
            
            $birthday = new \DateTime($postData['birthday']);
            $testDate = new \DateTime($postData['testDate']);
            $timeCreated = new \DateTime();
            
            $profile = array(
                'full_name' => $postData['fullName'],
                'birthday' => $birthday->format('Y-m-d'),
                'address' => $postData['address'],
                'phone_number' => $postData['phoneNumber'],
                'phone_onlysms' => false,
                'test_status' => $postData['testStatus'],
                'test_date' => $testDate->format('Y-m-d'),
                'license_front' => $postData['licenseFront'],
                'license_back' => $postData['licenseBack'],
                'note' => $postData['note']
            );
            
            if ($collaboratorModel->isExists($postData['collaborator']['ID'])) {
                $profile['collaborator_id'] = $postData['collaborator']['ID'];
            }
            
            if (is_array($postData['testVenue'])) {
                if ($venueModel->isExists($postData['testVenue']['ID'])) {
                    $profile['test_venue_id'] = $postData['testVenue']['ID'];
                }
            }
            
            if ($profileModel->isExists($postData['ID'])) {
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
            
            $profileModel = new Profile($serviceManager);
            
            if ($profileModel->isExists($postData['ID'])) {
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
    }
}
