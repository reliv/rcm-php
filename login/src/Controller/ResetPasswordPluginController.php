<?php

namespace RcmLogin\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Rcm\Plugin\PluginInterface;
use RcmLogin\Email\Mailer;
use RcmLogin\Entity\ResetPassword;
use RcmLogin\Form\ResetPasswordForm;
use RcmUser\Service\RcmUserService;
use Zend\InputFilter\InputFilterInterface;

/**
 * Reset Password Plugin Controller
 *
 * @category  Reliv
 * @author    Brian Janish <bjanish@relivinc.com>
 * @copyright 2013 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class ResetPasswordPluginController extends CreatePasswordPluginController implements
    PluginInterface
{

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var \RcmUser\Service\RcmUserService
     */
    protected $rcmUserManager;

    /**
     * @var EntityManager
     */
    protected $entityMgr;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var InputFilterInterface
     */
    protected $resetPasswordInputFilter;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    const COULD_NOT_RESET_PASSWORD_MESSAGE = 'Your password could not be reset.'
    . ' Either we could not find your account, or your account does not have an email on file.';

    /**
     * @param EntityManager $entityManager
     * @param null $config
     * @param Mailer $mailer
     * @param RcmUserService $rcmUserManager
     * @param InputFilterInterface $resetPasswordInputFilter
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManager $entityManager,
        $config,
        Mailer $mailer,
        RcmUserService $rcmUserManager,
        InputFilterInterface $resetPasswordInputFilter,
        LoggerInterface $logger
    ) {
        $this->entityMgr = $entityManager;
        $this->resetPasswordInputFilter = $resetPasswordInputFilter;
        parent::__construct($entityManager, $config, $rcmUserManager, $resetPasswordInputFilter, 'RcmResetPassword');
        $this->mailer = $mailer;
        $this->rcmUserManager = $rcmUserManager;
        $this->logger = $logger;
    }

    /**
     * @return InputFilterInterface
     */
    protected function getResetPasswordInputFilter()
    {
        return clone($this->resetPasswordInputFilter);
    }

    /**
     * getLabelViewHelper
     *
     * @return \RcmLogin\Form\LabelHelper
     */
    protected function getLabelViewHelper()
    {
        return $this->getServiceLocator()->get('RcmLogin\Form\LabelHelper');
    }

    /**
     * Plugin Action - Returns the guest-facing view model for this plugin
     *
     * @param int $instanceId plugin instance id
     * @param array $instanceConfig Instance Config
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderInstance($instanceId, $instanceConfig)
    {
        //Allows this plugin to also serve as the CreateNewPassword form for simpler page management.
        if ($this->params()->fromQuery('fromPasswordResetEmail') == 1) {
            return parent::renderInstance($instanceId, $instanceConfig);
        }

        $form = new ResetPasswordForm($instanceConfig);
        $error = null;
        $view = parent::renderInstance(
            $instanceId,
            $instanceConfig,
            true
        );

        if ($this->params()->fromQuery('invalidLink')) {
            $error = 'The password reset link you used is invalid.'
                . ' It may be expired or have already been used. Please try again below.';
        }

        $view->setTemplate('rcm-reset-password/plugin');
        $view->setVariables(
            [
                'form' => $form,
                'postSuccess' => false,
                'error' => $error,
                'labelHelper' => $this->getLabelViewHelper(),
            ]
        );

        if (!$this->postIsForThisPlugin()) {
            return $view;
        }

        // Handle Post
        $error = $this->handlePost($form, $instanceConfig);

        if (empty($error)) {
            $view->setVariable('postSuccess', true);
        }

        $view->setVariable('error', $error);

        return $view;
    }

    /**
     * Handle Post for Plugin
     *
     * @param ResetPasswordForm $form
     * @param                   $instanceConfig
     *
     * @return null|string
     */
    protected function handlePost(
        ResetPasswordForm $form,
        $instanceConfig
    ) {

        $resetPw = new ResetPassword();
        $form->setInputFilter($this->getResetPasswordInputFilter());

        $form->setData($this->getRequest()->getPost());

        if (!$form->isValid()) {
            $this->logger->info(
                self::class . ': Invalid ResetPasswordForm form with messages: ' . json_encode($form->getMessages())
            );

            return self::COULD_NOT_RESET_PASSWORD_MESSAGE;
        }

        $formData = $form->getData();
        $userId = $formData['userId'];

        $user = $this->rcmUserManager->buildNewUser();
        $user->setUsername($userId);

        $result = $this->rcmUserManager->readUser($user);

        if (!$result->isSuccess()) {
            $this->logger->info(
                self::class . ': User not found with id: ' . $userId
            );

            return self::COULD_NOT_RESET_PASSWORD_MESSAGE;
        }

        $user = $result->getUser();
        if (empty($user->getEmail())) {
            $this->logger->info(
                self::class . ": User ({$user->getId()}) has no email "
            );

            return self::COULD_NOT_RESET_PASSWORD_MESSAGE;
        }

        $resetPw->setUserId($user->getId());

        $this->entityMgr->persist($resetPw);
        $this->entityMgr->flush();
        $this->mailer->sendRestPasswordEmail(
            $resetPw,
            $user,
            $instanceConfig['prospectEmail']
        );

        return '';
    }
}
