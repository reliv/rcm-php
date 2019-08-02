<?php

namespace RcmUser\Log\Event\UserAuthenticationService;

use RcmUser\Authentication\Service\UserAuthenticationService;

/**
 * Class ValidateCredentialsFailListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ValidateCredentialsFailListener extends AbstractUserAuthenticationServiceListener
{
    /**
     * @var string
     */
    protected $event = UserAuthenticationService::EVENT_VALIDATE_CREDENTIALS_FAIL;
}
