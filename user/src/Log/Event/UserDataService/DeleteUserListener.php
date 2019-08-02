<?php

namespace RcmUser\Log\Event\UserDataService;

use RcmUser\User\Service\UserDataService;

/**
 * Class DeleteUserFailListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DeleteUserListener extends AbstractUserDataServiceListener
{
    /**
     * @var string
     */
    protected $event = UserDataService::EVENT_DELETE_USER;
}
