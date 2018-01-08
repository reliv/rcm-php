<?php

namespace Rcm\Factory;

use Rcm\Controller\Plugin\IsSiteAdmin;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Should Show Revisions Helper
 *
 * This file contains the factory needed to generate the Should Show Revisions Helper
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
class IsSiteAdminPluginFactory
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $mgr mgr
     *
     * @return IsSiteAdmin
     */
    public function __invoke($mgr)
    {
        $serviceLocator = $mgr->getServiceLocator();
        $cmsPermissionChecks = $serviceLocator->get(
            \Rcm\Acl\CmsPermissionChecks::class
        );

        $service = new IsSiteAdmin($cmsPermissionChecks);

        return $service;
    }
}
