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
use Administrator\Model\User;
use Zend\Json\Encoder;
use Administrator\Model\UserGroup;
use Kendo\UI\Grid;
use Kendo\UI\GridEditable;
use Kendo\UI\GridToolbarItem;
use Kendo\UI\GridColumn;
use Kendo\JavaScriptFunction;
use Kendo\UI\GridColumnCommandItem;
use Kendo\Data\DataSource;
use Kendo\Data\DataSourceTransport;
use Kendo\Data\DataSourceTransportRead;
use Kendo\Data\DataSourceTransportCreate;
use Kendo\Data\DataSourceTransportUpdate;
use Kendo\Data\DataSourceTransportDestroy;
use Kendo\Data\DataSourceSchema;
use Kendo\Data\DataSourceSchemaModel;
use Kendo\Data\DataSourceSchemaModelField;
use Kendo\Data\DataSourceSchemaModelFieldValidation;

class UserController extends AbstractActionController
{

    /**
     *
     * @todo Fix date time error. Display in vietnam format
     * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
     */
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
        // Editable
        //
        $editable = new GridEditable();
        $editable->mode('popup');
        $grid->editable($editable);
        //
        // Sortable
        //
        $grid->sortable(true);
        //
        // Toolbar
        //
        $createButtonToolbar = new GridToolbarItem();
        $createButtonToolbar->name('create');
        $createButtonToolbar->text($translator->translate('Them thanh vien'));
        $grid->addToolbarItem($createButtonToolbar);
        //
        // Resizable
        //
        $grid->resizable(true);
        //
        // Columns
        //
        // Id
        $idColumn = new GridColumn();
        $idColumn->field('ID')
            ->title('ID')
            ->width(60)
            ->attributes(" style= \"text-align: center;\"")
            ->template(new JavaScriptFunction("function (dataItem) {
                    return '<a class=\"tooltip-cell\" data-toggle=\"tooltip\" title=\"{$translator->translate('Tham gia:')}&nbsp;' + dataItem.joinDate + '\">' + dataItem.ID + '</a>';
                }"));
        $grid->addColumn($idColumn);
        // Group
        $groupColumn = new GridColumn();
        $groupColumn->field('group')
            ->title($translator->translate('Nhom'))
            ->width(160)
            ->template("#= group.title #")
            ->editor(new JavaScriptFunction("function (container, options) {
                    $('<input data-bind=\"value:' + options.field + '\"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            optionLabel: '" . $translator->translate('Chon nhom thanh vien') . "',
                            dataTextField: 'title',
                            dataValueField: 'ID',
                            dataSource: {
                                transport: {
                                    read: {
                                        url: '{$this->url()->fromRoute('administrator/default', array('controller' => 'user-groups','action' => 'read'))}'
                                    }
                                },
                                schema: {
                                    data: 'groups'
                                }
                            }
                        });
                }"));
        $grid->addColumn($groupColumn);
        // Full Name
        $fullNameColumn = new GridColumn();
        $fullNameColumn->field('fullName')
            ->title($translator->translate('Ho va ten'))
            ->width(200);
        $grid->addColumn($fullNameColumn);
        // Email
        $emailColumn = new GridColumn();
        $emailColumn->field('email')
            ->title($translator->translate('Email'))
            ->width(200);
        $grid->addColumn($emailColumn);
        // Password
        $passwordColumn = new GridColumn();
        $passwordColumn->field('password')
            ->title($translator->translate('Mat khau'))
            ->hidden(true)
            ->editor(new JavaScriptFunction("function (container, options) {
                    $('<input name=\"password\" type=\"password\" class=\"k-input k-textbox\" placeholder=\"" . $translator->translate('De trong neu khong muon thay doi') . "\" data-bind=\"'+options.field+'\"/>')
                        .appendTo(container);
                }"));
        $grid->addColumn($passwordColumn);
        // Birthday
        $birthdayColumn = new GridColumn();
        $birthdayColumn->field('birthday')
            ->title($translator->translate('Nam sinh'))
            ->width(100)
            ->attributes(" style= \"text-align: center;\"")
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
            ->width(260);
        $grid->addColumn($addressColumn);
        // Phone Number
        $phoneNumberColumn = new GridColumn();
        $phoneNumberColumn->field('phoneNumber')
            ->title($translator->translate('Dien thoai'))
            ->width(120)
            ->attributes(" style= \"text-align: center;\"");
        $grid->addColumn($phoneNumberColumn);
        // Command
        $commandColumn = new GridColumn();
        $commandColumn->title('&nbsp;');
        $editCommand = new GridColumnCommandItem();
        $editCommand->name('edit')->text($translator->translate('Sua'));
        $commandColumn->addCommandItem($editCommand);
        $destroyCommand = new GridColumnCommandItem();
        $destroyCommand->name('destroy')->text($translator->translate('Xoa'));
        $commandColumn->addCommandItem($destroyCommand);
        $grid->addColumn($commandColumn);
        //
        // DataSource
        //
        $dataSource = new DataSource();
        // Transport Read
        $transportRead = new DataSourceTransportRead();
        $transportRead->url($this->url()->fromRoute('administrator/users/default', array('action' => 'read')));
        // Transport Create
        $transportCreate = new DataSourceTransportCreate();
        $transportCreate->url($this->url()->fromRoute('administrator/users/default', array('action' => 'create')))
            ->type('POST');
        // Transport Update
        $transportUpdate = new DataSourceTransportUpdate();
        $transportUpdate->url($this->url()->fromRoute('administrator/users/default', array('action' => 'update')))
            ->type('POST');
        // Transport Destroy
        $transportDestroy = new DataSourceTransportDestroy();
        $transportDestroy->url($this->url()->fromRoute('administrator/users/default', array('action' => 'destroy')))
            ->type('POST');
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
        $groupModelField = new DataSourceSchemaModelField('group');
        $groupModelFieldValidation = new DataSourceSchemaModelFieldValidation();
        $groupModelFieldValidation->required(true);
        $groupModelField->validation($groupModelFieldValidation);
        $model->addField($groupModelField);
        $fullNameModelField = new DataSourceSchemaModelField('fullName');
        $fullNameModelFieldValidation = new DataSourceSchemaModelFieldValidation();
        $fullNameModelFieldValidation->required(true);
        $fullNameModelField->type('string')->validation($fullNameModelFieldValidation);
        $model->addField($fullNameModelField);
        $birthdayModelField = new DataSourceSchemaModelField('birthday');
        $birthdayModelField->type('date');
        $model->addField($birthdayModelField);
        $emailModelField = new DataSourceSchemaModelField('email');
        $emailModelFieldValidation = new DataSourceSchemaModelFieldValidation();
        $emailModelFieldValidation->required(true);
        $emailModelField->type('string')->validation($emailModelFieldValidation);
        $model->addField($emailModelField);
        $passwordModelField = new DataSourceSchemaModelField('password');
        $passwordModelFieldValidation = new DataSourceSchemaModelFieldValidation();
        $passwordModelFieldValidation->required(true);
        $passwordModelField->type('string')->validation($passwordModelFieldValidation);
        $model->addField($passwordModelField);
        $addressModelField = new DataSourceSchemaModelField('address');
        $addressModelFieldValidation = new DataSourceSchemaModelFieldValidation();
        $addressModelFieldValidation->required(true);
        $addressModelField->type('string')->validation($addressModelFieldValidation);
        $model->addField($addressModelField);
        $phoneNumberModelField = new DataSourceSchemaModelField('phoneNumber');
        $phoneNumberModelFieldValidation = new DataSourceSchemaModelFieldValidation();
        $phoneNumberModelFieldValidation->required(true);
        $phoneNumberModelField->type('string')->validation($phoneNumberModelFieldValidation);
        $model->addField($phoneNumberModelField);
        
        $schema->total('total')
            ->data('users')
            ->model($model);
        $dataSource->schema($schema);
        $grid->dataSource($dataSource);
        
        // Events
        $grid->dataBound(new JavaScriptFunction("function () { \$('[data-toggle=\"tooltip\"]').tooltip({placement: \"right\"});}"));
        $grid->edit(new JavaScriptFunction("function (e) {
            e.model.set('password','');
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
        
        $userModel = new User($serviceManager);
        $userGroupModel = new UserGroup($serviceManager);
        
        $users = $userModel->cache->getUsers();
        
        $response = array(
            'succes' => true,
            'total' => count($users)
        );
        
        foreach ($users as $user) {
            $userGroup = $userGroupModel->cache->getGroup(array(
                'group_id' => $user['group_id']
            ));
            
            $response['users'][] = array(
                'ID' => $user['user_id'],
                'group' => array(
                    'ID' => $user['group_id'],
                    'title' => $userGroup['title']
                ),
                'fullName' => $user['full_name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'birthday' => $user['birthday'],
                'address' => $user['address'],
                'phoneNumber' => $user['phone_number'],
                'joinDate' => $user['time_created'],
                'lastUpdate' => $user['last_updated']
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
            
            $userModel = new User($serviceManager);
            $birthday = new \DateTime($postData['birthday']);
            $timeCreated = new \DateTime();
            
            $password = $postData['password'];
            
            $user = array(
                'group_id' => $postData['group'],
                'full_name' => $postData['fullName'],
                'birthday' => $birthday->format('Y-m-d'),
                'email' => $postData['email'],
                'phone_number' => $postData['phone_number'],
                'address' => $postData['address'],
                'phone_number' => $postData['phoneNumber'],
                'time_created' => $timeCreated->format('Y-m-d h:i:s')
            );
            
            if ($password && strlen($password) > 0) {
                $user['password'] = $password;
            }
            
            if ($userModel->createUser($user)) {
                $response['success'] = true;
                $response['insert_id'] = $userModel->getLastInsertValue();
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
            
            $userModel = new User($serviceManager);
            $existsUser = $userModel->cache->getUser(array(
                'user_id' => $postData['ID']
            ));
            
            $birthday = new \DateTime($postData['birthday']);
            $password = $postData['password'];
            
            $user = array(
                'group_id' => $postData['group']['ID'],
                'full_name' => $postData['fullName'],
                'birthday' => $birthday->format('Y-m-d'),
                'email' => $postData['email'],
                'address' => $postData['address'],
                'phone_number' => $postData['phoneNumber']
            );
            
            if ($password && strlen($password) > 0 && $password != $existsUser['password']) {
                $user['password'] = md5($password);
            }
            
            if ($userModel->updateUser(array(
                'user_id' => $postData['ID']
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
            
            $userModel = new User($serviceManager);
            
            if ($userModel->removeUser(array(
                'user_id' => $postData['ID']
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
