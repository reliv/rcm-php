<?php

namespace RcmUser\Log\Event\UserDataService;

use RcmUser\User\Service\UserDataService;

/**
 * Class UpdateUserSuccessListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UpdateUserSuccessListener extends AbstractUserDataServiceListener
{
    /**
     * @var string
     */
    protected $event = UserDataService::EVENT_UPDATE_USER_SUCCESS;
}
