<?php

namespace Rcm\SecureRepo;

use Psr\Container\ContainerInterface;
use Rcm\Acl\AssertIsAllowed;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\SecurityPropertyProvider\SiteSecurityPropertyProvider;
use RcmUser\Service\RcmUserService;

class SiteSecureRepoFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        return new SiteSecureRepo(
            $requestContext->get('Config'),
            $requestContext->get('Doctrine\ORM\EntityManager'),
            $requestContext->get(RcmUserService::class),
            $requestContext->get(PageSecureRepo::class),
            $requestContext->get('Rcm\ImmutableHistory\SiteVersionRepo'),
            $requestContext->get('Rcm\ImmutableHistory\SiteWideContainerVersionRepo'),
            $requestContext->get(PageContentFactory::class),
            $requestContext->get(\Rcm\Acl\GetCurrentUser::class),
            $requestContext->get(SiteSecurityPropertyProvider::class),
            $requestContext->get(AssertIsAllowed::class)
        );
    }
}
