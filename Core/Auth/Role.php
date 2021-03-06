<?php
/*
 * This file is part of the Evo package.
 *
 * (c) John Andrew <simplygenius78@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Evo\Auth\Roles;

use Evo\UserManager\Rbac\Model\RolePermissionModel;
use Evo\UserManager\Rbac\Group\Model\GroupRoleModel;

class Role
{

    /** @var array  */
    protected array $permissions;
    protected array $gRoles;

    /**
     * Role constructor.
     * @return void
     */
    protected function __construct()
    {
        $this->permissions = [];
    }

    /**
     * return a role object with associated permissions
     * @param $roleID
     * @return array
     */
    public static function getRolePermissions($roleID)
    {
        $role = new Role();
        $sql = "SELECT t2.permission_name FROM role_permission as t1 JOIN permissions as t2 ON t1.permission_id = t2.id WHERE t1.role_id = :role_id";
        $row = (new RolePermissionModel())
            ->getRepository()
            ->getEm()
            ->getCrud()
            ->rawQuery($sql, ['role_id' => $roleID], 'fetch_all');
        if ($row) {
            foreach ($row as $r) {
                $role->permissions[$r['permission_name']] = true;
            }
            return $role;
        }
    }

    /**
     * return a role object with associated group
     * @param $roleID
     * @return array
     */
    public static function getRoleGroups($groupID)
    {
        $role = new Role();
        $sql = "SELECT t2.role_name FROM group_role as t1 JOIN roles as t2 ON t1.group_id = t2.id WHERE t1.group_id = :group_id";
        $row = (new GroupRoleModel())
            ->getRepository()
            ->getEm()
            ->getCrud()
            ->rawQuery($sql, ['group_id' => $groupID], 'fetch_all');
        if ($row) {
            foreach ($row as $r) {
                $role->gRoles[$r['role_name']] = true;
            }
            return $role;
        }
    }


    /**
     * Check if a permission is set
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return isset($this->permissions[$permission]);
    }

}
