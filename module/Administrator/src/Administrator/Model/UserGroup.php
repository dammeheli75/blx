<?php
namespace Administrator\Model;

use Blx\Db\TableGateway\AbstractTableGateway;
use Blx\User\UserManager;

class UserGroup extends AbstractTableGateway
{

    protected $table = 'user_groups';

    protected $primaryKey = 'group_id';

    public function getGroups()
    {
        $translator = UserManager::getStaticServiceManager()->get('translator');
        
        return array(
            array(
                'group_id' => UserManager::ADMINISTRATOR_GROUP,
                'title' => $translator->translate('Administrator')
            ),
            array(
                'group_id' => UserManager::MANAGER_GROUP,
                'title' => $translator->translate('Manager')
            ),
            array(
                'group_id' => UserManager::COLLABORATOR_GROUP,
                'title' => $translator->translate('Collaborator')
            )
        );
    }

    public function getGroup(array $options)
    {
        $results = array();
        if (isset($options['group_id'])) {
            foreach ($this->getGroups() as $group) {
                if ($group['group_id'] == $options['group_id']) {
                    $results[] = $group;
                }
            }
        } else {
            $results = $this->getGroups();
        }
        if (count($results) > 0) {
            return $results[0];
        }
        return null;
    }

    public function count($conditions = null)
    {
        return count($this->getGroups());
    }
}