<?php

namespace RcmAdmin\Service;

use Psr\Container\ContainerInterface;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use RcmUser\Service\RcmUserService;

class SiteManagerFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        return new SiteManager(
            $requestContext->get('Config'),
            $requestContext->get('Doctrine\ORM\EntityManager'),
            $requestContext->get(RcmUserService::class),
            $requestContext->get(PageMutationService::class),
            $requestContext->get('Rcm\ImmutableHistory\SiteVersionRepo'),
            $requestContext->get('Rcm\ImmutableHistory\SiteWideContainerVersionRepo'),
            $requestContext->get(PageContentFactory::class)
        );
    }
}
