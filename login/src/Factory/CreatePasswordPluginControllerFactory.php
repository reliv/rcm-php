<?php

namespace RcmLogin\Factory;

use RcmLogin\Controller\CreatePasswordPluginController;
use RcmLogin\InputFilter\CreateNewPasswordInputFilter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for PluginController
 *
 * Factory for PluginController.
 *
 * @category  Reliv
 * @package   RcmCreateNewPassword
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class CreatePasswordPluginControllerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $controllerMgr Zend Controller Manager
     *
     * @return CreatePasswordPluginController
     */

    public function createService(ServiceLocatorInterface $controllerMgr)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $cm For IDE */
        $cm = $controllerMgr;

        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $cm->getServiceLocator();

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $config = $serviceLocator->get('config');

        /** @var \RcmUser\Service\RcmUserService $rcmUserService */
        $rcmUserService = $serviceLocator->get(
            'RcmUser\Service\RcmUserService'
        );

        /** @var CreateNewPasswordInputFilter $inputFilterInterface */
        $inputFilterInterface = $serviceLocator->get(CreateNewPasswordInputFilter::class);

        return new CreatePasswordPluginController(
            $entityManager,
            $config,
            $rcmUserService,
            $inputFilterInterface
        );
    }
}
