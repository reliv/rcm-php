<?php

namespace Rcm\Factory;

use Rcm\Acl\CmsPermissionChecks;
use Rcm\Acl\ResourceName;
use Rcm\Api\Acl\HasRoleBasedAccess;
use Rcm\Api\Acl\IsAllowedShowRevisions;
use Rcm\Api\Acl\IsAllowedSiteAdmin;
use Rcm\Api\Acl\IsPageAllowedForReading;
use Rcm\Api\Acl\IsPageRestricted;
use Rcm\Api\Acl\IsUserLoggedIn;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CmsPermissionsChecksFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return CmsPermissionChecks
     */
    public function __invoke($serviceLocator)
    {
        return new CmsPermissionChecks(
            $serviceLocator->get(ResourceName::class),
            $serviceLocator->get(IsPageAllowedForReading::class),
            $serviceLocator->get(IsAllowedSiteAdmin::class),
            $serviceLocator->get(HasRoleBasedAccess::class),
            $serviceLocator->get(IsUserLoggedIn::class),
            $serviceLocator->get(IsAllowedShowRevisions::class),
            $serviceLocator->get(IsPageRestricted::class)
        );
    }
}
