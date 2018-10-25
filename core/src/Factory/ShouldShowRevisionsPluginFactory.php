<?php

namespace Rcm\Factory;

use Rcm\Controller\Plugin\ShouldShowRevisions;
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
 * @copyright 2017 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
class ShouldShowRevisionsPluginFactory
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $mgr mgr
     *
     * @return ShouldShowRevisions
     */
    public function __invoke($mgr)
    {
        $serviceLocator = $mgr->getServiceLocator();
        $cmsPermissionChecks = $serviceLocator->get(
            \Rcm\Acl\CmsPermissionChecks::class
        );

        $service = new ShouldShowRevisions($cmsPermissionChecks);

        return $service;
    }
}
