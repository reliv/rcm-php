<?php

namespace RcmUser\Log\Event\UserRoleService;

use RcmUser\User\Service\UserRoleService;

/**
 * Class CreateUserRolesListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class CreateUserRolesListener extends AbstractUserRoleServiceListener
{
    /**
     * @var string
     */
    protected $event = UserRoleService::EVENT_CREATE_USER_ROLES;
}
