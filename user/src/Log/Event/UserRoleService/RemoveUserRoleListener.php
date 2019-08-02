<?php

namespace RcmUser\Log\Event\UserRoleService;

use RcmUser\User\Service\UserRoleService;

/**
 * Class AddUserRoleListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RemoveUserRoleListener extends AbstractUserRoleServiceListener
{
    /**
     * @var string
     */
    protected $event = UserRoleService::EVENT_REMOVE_USER_ROLE;
}
