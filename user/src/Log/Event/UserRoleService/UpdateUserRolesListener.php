<?php

namespace RcmUser\Log\Event\UserRoleService;

use RcmUser\User\Service\UserRoleService;

/**
 * Class UpdateUserRolesListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UpdateUserRolesListener extends AbstractUserRoleServiceListener
{
    /**
     * @var string
     */
    protected $event = UserRoleService::EVENT_UPDATE_USER_ROLES;
}
