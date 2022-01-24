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

namespace Evo\UserManager\Rbac\Event;

use Evo\Base\BaseActionEvent;

class RolePermissionActionEvent extends BaseActionEvent
{

    /** @var string - name of the event */
    public const NAME = 'evo.usermanager.rbac.event.role_permission_action_event';
}

