<?php

namespace RcmUser\Log\Event\UserRoleService;

use RcmUser\User\Service\UserRoleService;

/**
 * Class RemoveUserRoleSuccessListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RemoveUserRoleSuccessListener extends AbstractUserRoleServiceListener
{
    /**
     * @var string
     */
    protected $event = UserRoleService::EVENT_REMOVE_USER_ROLE_SUCCESS;
}
