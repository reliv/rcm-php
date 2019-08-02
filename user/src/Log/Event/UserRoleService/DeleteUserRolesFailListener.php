<?php

namespace RcmUser\Log\Event\UserRoleService;

use RcmUser\User\Service\UserRoleService;

/**
 * Class DeleteUserRolesFailListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DeleteUserRolesFailListener extends AbstractUserRoleServiceListener
{
    /**
     * @var string
     */
    protected $event = UserRoleService::EVENT_DELETE_USER_ROLES_FAIL;
}
