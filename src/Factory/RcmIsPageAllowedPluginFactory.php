<?php

namespace Rcm\Factory;

use Rcm\Controller\Plugin\RcmIsPageAllowed;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the RcmIsPageAllowed Helper
 *
 * This file contains the factory needed to generate the RcmIsPageAllowed Helper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2018 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
class RcmIsPageAllowedPluginFactory
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $mgr mgr
     *
     * @return RcmIsPageAllowed
     */
    public function __invoke($mgr)
    {
        $serviceLocator = $mgr->getServiceLocator();
        $cmsPermissionChecks = $serviceLocator->get(
            \Rcm\Acl\CmsPermissionChecks::class
        );

        $service = new RcmIsPageAllowed($cmsPermissionChecks);

        return $service;
    }
}
