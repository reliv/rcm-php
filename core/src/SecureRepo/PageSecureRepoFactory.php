<?php

namespace Rcm\SecureRepo;

use Psr\Container\ContainerInterface;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Entity\Page;
use Rcm\RequestContext\RequestContextBindings;
use Rcm\SecurityPropertyProvider\PageSecurityPropertyProvider;

class PageSecureRepoFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        return new PageSecureRepo(
            $requestContext->get(\Doctrine\ORM\EntityManager::class),
            $requestContext->get('Rcm\ImmutableHistory\PageVersionRepo'),
            $requestContext->get('Rcm\ImmutableHistory\SiteWideContainerVersionRepo'),
            $requestContext->get(\Rcm\ImmutableHistory\Page\PageContentFactory::class),
            $requestContext->get(\Rcm\ImmutableHistory\Page\RcmPageNameToPathname::class),
            $requestContext->get(PageSecurityPropertyProvider::class),
            $requestContext->get(\Rcm\Service\CurrentSite::class), //ideally should come from $requestContext instead
            $requestContext->get(\Rcm\Acl\GetCurrentUser::class),
            $requestContext->get(AssertIsAllowed::class)
        );
    }
}
