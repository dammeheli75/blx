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
use Administrator\Model\Permission;
use Administrator\Model\UserGroup;

class PermissionController extends AbstractActionController
{

    public function indexAction()
    {
        if ($this->acl()->isAllowed('permission', 'read')) {
            $viewModel = new ViewModel();
            $serviceManager = $this->getEvent()
                ->getApplication()
                ->getServiceManager();
            $translator = $serviceManager->get('translator');
            
            $permissionModel = new Permission();
            $userGroupModel = new UserGroup();
            
            if ($this->getRequest()->isPost() && $this->acl()->isAllowed('permission', 'update')) {
                $postData = $this->getRequest()->getPost();
                
                // Parse
                $permissionUpdates = array();
                foreach ($postData as $roleId => $roleData) {
                    foreach ($roleData as $resourceId => $resourceData) {
                        foreach ($resourceData as $privilege => $isAllowed) {
                            $permissionUpdates[] = array(
                                'role' => $roleId,
                                'resource' => $resourceId,
                                'privilege' => $privilege,
                                'allow' => $isAllowed
                            );
                        }
                    }
                }
                
                foreach ($permissionUpdates as $permissionUpdate) {
                    $permissionModel->updatePermission(array(
                        'role' => $permissionUpdate['role'],
                        'resource' => $permissionUpdate['resource'],
                        'privilege' => $permissionUpdate['privilege'],
                        'allow' => ($permissionUpdate['allow'] == 'on') ? true : false
                    ));
                }
            }
            
            $permissions = $permissionModel->cache->getPermissions();
            $userGroups = array();
            
            foreach ($permissions as $key => $permission) {
                // Get user group ID
                $temp3 = explode('_', $permission['role']);
                $userGroupID = $temp3[2];
                if (! array_key_exists($userGroupID, $userGroups)) {
                    if ($userGroupID != 'guest') {
                        $userGroup = $userGroupModel->cache->getGroup(array(
                            'group_id' => $userGroupID
                        ));
                        $userGroups[$permission['role']] = $userGroup['title'];
                    } else {
                        $userGroups['user_group_guest'] = $translator->translate('Guest');
                    }
                }
                unset($temp3);
            }
            
            /**
             *
             * @todo Gom nhom du lieu theo role
             */
            $temp = $permissions;
            $permissions = array();
            
            foreach ($temp as $permission) {
                $temp2 = explode('_', $permission['role']);
                $userGroupID = $temp[2];
                $permissions[$permission['resource']][$permission['privilege']]['description'] = $permission['description'];
                $permissions[$permission['resource']][$permission['privilege']]['groups'][$permission['role']] = $permission['allow'];
                unset($temp2);
            }
            unset($temp);
            
            $viewModel->setVariable('permissions', $permissions);
            $viewModel->setVariable('userGroups', $userGroups);
            return $viewModel;
        } else {
            // Redirect
            $this->redirect()->toRoute('restriction-access');
        }
    }
}
