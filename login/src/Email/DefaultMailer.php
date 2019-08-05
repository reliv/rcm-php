<?php

namespace RcmLogin\Email;

use RcmLogin\Entity\ResetPassword;
use RcmUser\User\Entity\UserInterface;
use Zend\Mail\Exception\InvalidArgumentException;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

/**
 * Class Mailer
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Mailer
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class DefaultMailer implements Mailer
{
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

        $vars = [
            'name' => '',
            'userId' => $user->getId(),
            'url' =>
                'https://' . $_SERVER['HTTP_HOST']
                . '/reset-password?fromPasswordResetEmail=1&id='
                . $resetPassword->getResetId() . '&key='
                . $resetPassword->getHashKey()
        ];

        foreach ($vars as $name => $value) {
            $bodyTemplate = str_replace(
                '__' . $name . '__',
                $value,
                $bodyTemplate
            );

            // Handle BC
            $bodyTemplate = str_replace(
                '{' . $name . '}',
                $value,
                $bodyTemplate
            );
        }
        try {
            $html = new MimePart($bodyTemplate);
            $html->type = "text/html";

            $body = new MimeMessage();
            $body->setParts([$html]);

            $message = new Message();
            $message->setBody($body)
                ->setFrom($fromEmail, $fromName)
                ->setSubject($subject);

            foreach (explode(',', $toEmail) as $email) {
                $message->addTo(trim($email));
            }

            $transport = new \Zend\Mail\Transport\Sendmail();

            $transport->send($message);
        } catch (InvalidArgumentException $e) {
            // nothing
        }
    }
}
