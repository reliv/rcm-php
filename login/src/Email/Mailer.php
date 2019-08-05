<?php

namespace RcmLogin\Email;

use RcmLogin\Entity\ResetPassword;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Mailer
{
    /**
     * sendRestPasswordEmail
     *
     * @param ResetPassword $resetPw
     * @param UserInterface $user
     * @param array         $mailConfig
     *
     * @return mixed
     */
    public function sendRestPasswordEmail(
        ResetPassword $resetPw,
        UserInterface $user,
        $mailConfig
    );
}
