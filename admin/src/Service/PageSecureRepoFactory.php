<?php

namespace RcmAdmin\Service;

use Psr\Container\ContainerInterface;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Entity\Page;
use Rcm\RequestContext\RequestContextBindings;

class PageSecureRepoFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        return new PageSecureRepo(
            $requestContext->get(\RcmUser\Service\RcmUserService::class),
            $requestContext->get(\Doctrine\ORM\EntityManager::class),
            $requestContext->get('Rcm\ImmutableHistory\PageVersionRepo'),
            $requestContext->get('Rcm\ImmutableHistory\SiteWideContainerVersionRepo'),
            $requestContext->get(\Rcm\ImmutableHistory\Page\PageContentFactory::class),
            $requestContext->get(\Rcm\ImmutableHistory\Page\RcmPageNameToPathname::class),
            $requestContext->get(\Doctrine\ORM\EntityManager::class)->getRepository(Page::class),
            $requestContext->get(\Rcm\Service\CurrentSite::class), //ideally should come from $requestContext instead
            $requestContext->get(\Rcm\Acl\GetCurrentUser::class),
            $requestContext->get(AssertIsAllowed::class)
        );
    }
}
