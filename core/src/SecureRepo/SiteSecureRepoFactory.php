<?php

namespace Rcm\SecureRepo;

use Psr\Container\ContainerInterface;
use Rcm\Acl\AssertIsAllowed;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\SecurityPropertiesProvider\SiteSecurityPropertiesProvider;
use Rcm\Service\CurrentSite;
use Rcm\Service\LayoutManager;
use RcmUser\Service\RcmUserService;

class SiteSecureRepoFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        return new SiteSecureRepo(
            $requestContext->get('Config'),
            $requestContext->get('Doctrine\ORM\EntityManager'),
            $requestContext->get(PageSecureRepo::class),
            $requestContext->get('Rcm\ImmutableHistory\SiteVersionRepo'),
            $requestContext->get('Rcm\ImmutableHistory\SiteWideContainerVersionRepo'),
            $requestContext->get(PageContentFactory::class),
            $requestContext->get(\Rcm\Acl\GetCurrentUser::class),
            $requestContext->get(SiteSecurityPropertiesProvider::class),
            $requestContext->get(AssertIsAllowed::class),
            new SiteSecureRepoPaginatorFactory(),
            $requestContext->get(CurrentSite::class),
            $requestContext->get(LayoutManager::class)
        );
    }
}
