<?php

namespace RcmLogin\Email;

use RcmLogin\Entity\ResetPassword;
use RcmUser\User\Entity\UserInterface;
use Reliv\App\Mailer\Service\TemplateMailer;
use Zend\Mail\Exception\InvalidArgumentException;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class DefaultMailer implements Mailer
{
    protected $templateMailer;

    public function __construct(TemplateMailer $templateMailer)
    {
        $this->templateMailer = $templateMailer;
    }

    /**
     * send
     *
     * @param ResetPassword $resetPassword
     * @param UserInterface $user
     * @param array $mailConfig
     *
     * @return mixed
     */
    public function sendRestPasswordEmail(
        ResetPassword $resetPassword,
        UserInterface $user,
        $mailConfig
    ) {
        $toEmail = $user->getEmail();
        $fromEmail = $mailConfig['fromEmail'];
        $fromName = $mailConfig['fromName'];
        $subject = $mailConfig['subject'];
        $bodyTemplate = $mailConfig['body'];

        //Ignore blank emails
        if (!trim($toEmail)) {
            return;
        }

        $resetPasswordLinkUrl =
            'https://' . $_SERVER['HTTP_HOST']
            . '/reset-password?fromPasswordResetEmail=1&id='
            . $resetPassword->getResetId() . '&key='
            . $resetPassword->getHashKey();

        $this->templateMailer->send(
            $toEmail,
            'donotreply@reliv.com',
            'Reliv International',
            'password_reset',
            [
                'url' => $resetPasswordLinkUrl
            ]
        );
    }
}
