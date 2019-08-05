<?php

namespace RcmLogin\Factory;

use RcmLogin\Controller\ResetPasswordPluginController;
use RcmLogin\InputFilter\ResetPasswordInputFilter;
use RcmLogin\Log\Logger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for PluginController
 *
 * Factory for PluginController.
 *
 * @category  Reliv
 * @package   RcmResetPassword
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class ResetPasswordPluginControllerFactory implements FactoryInterface
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $controllerMgr Zend Controller Manager
     *
     * @return ResetPasswordPluginController
     */
    public function createService(ServiceLocatorInterface $controllerMgr)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $cm For IDE */
        $cm = $controllerMgr;

        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $cm->getServiceLocator();

        $config = $serviceLocator->get('config');

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $mailerService = $config['rcmPlugin']['RcmResetPassword']['mailer'];

        /** @var \RcmLogin\Email\Mailer $mailer */
        $mailer = $serviceLocator->get($mailerService);

        /**
         * @var \RcmUser\Service\RcmUserService $rcmUserManager
         */
        $rcmUserManager = $serviceLocator->get(
            'RcmUser\Service\RcmUserService'
        );

        /** @var ResetPasswordInputFilter $inputFilterInterface */
        $inputFilterInterface = $serviceLocator->get(ResetPasswordInputFilter::class);

        $logger = $serviceLocator->get(
            Logger::class
        );

        return new ResetPasswordPluginController(
            $entityManager,
            $config,
            $mailer,
            $rcmUserManager,
            $inputFilterInterface,
            $logger
        );
    }
}
