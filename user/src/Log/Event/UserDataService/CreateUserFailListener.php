<?php

namespace RcmUser\Log\Event\UserDataService;

use RcmUser\User\Service\UserDataService;

/**
 * Class CreateUserFailListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class CreateUserFailListener extends AbstractUserDataServiceListener
{
    /**
     * @var string
     */
    protected $event = UserDataService::EVENT_CREATE_USER_FAIL;
}
