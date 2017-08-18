<?php

namespace Rcm\Factory;

use Rcm\Controller\Plugin\RcmIsAllowed;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ControllerPluginRcmIsAllowed
 *
 * ControllerPluginRcmIsAllowed
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmIsAllowedFactory
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $mgr mgr
     *
     * @return mixed|RcmIsAllowed
     */
    public function __invoke($mgr)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $pluginManager For IDE */
        $pluginManager = $mgr;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $pluginManager->getServiceLocator();

        /** @var \RcmUser\Service\RcmUserService $rcmUserService */
        $rcmUserService = $serviceLocator->get(
            \RcmUser\Service\RcmUserService::class
        );

        $service = new RcmIsAllowed($rcmUserService);

        return $service;
    }
}
