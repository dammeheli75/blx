<?php
namespace Blx\Permissions\Acl;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Administrator\Model\Permission;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;
use Blx\Cache\StorageFactory;
use Application\View\Helper\Acl as AclHelper;

class AclServiceFactory implements FactoryInterface
{

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $cache = StorageFactory::factory(array(
            'ttl' => 7776000, // 90 days
            'namespace' => 'Acl'
        ));
        $acl = $cache->getItem('acl');
        if (! $acl) {
            // Get Acl config from database
            $acl = new Acl();
            $permissionModel = new Permission();
            $permissions = $permissionModel->cache->getPermissions();
            foreach ($permissions as $permission) {
                $role = new GenericRole($permission['role']);
                $resource = new GenericResource($permission['resource']);
                // Role
                if (! $acl->hasRole($permission['role']))
                    $acl->addRole($role);
                    // Resource
                if (! $acl->hasResource($permission['resource']))
                    $acl->addResource($resource);
                    // Access controls
                if ($permission['allow']) {
                    $acl->allow($role, $resource, $permission['privilege']);
                }
            }
            $cache->setItem('acl', $acl);
        }
        
        $viewHelper = new AclHelper();
        $viewHelper->setServiceLocator($serviceLocator);
        
        return $acl;
    }
}