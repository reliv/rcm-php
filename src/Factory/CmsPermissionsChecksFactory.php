<?php

namespace Rcm\Factory;

use \Rcm\Acl\CmsPermissionChecks;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Cms Permissions Helper
 *
 * Factory for the Container Manager.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class CmsPermissionsChecksFactory implements FactoryInterface
{

    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return CmsPermissionChecks
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \RcmUser\Service\RcmUserService $rcmUserService */
        $rcmUserService = $serviceLocator->get(\RcmUser\Service\RcmUserService::class);
        return new CmsPermissionChecks($rcmUserService);
    }
}
