<?php

namespace RcmUser\Log\Event\UserAuthenticationService;

use RcmUser\Authentication\Service\UserAuthenticationService;

/**
 * Class AuthenticateFailListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AuthenticateFailListener extends AbstractUserAuthenticationServiceListener
{
    /**
     * @var string
     */
    protected $event = UserAuthenticationService::EVENT_AUTHENTICATE_FAIL;
}
