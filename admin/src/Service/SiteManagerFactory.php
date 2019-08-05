<?php


namespace RcmAdmin\Service;


use Psr\Container\ContainerInterface;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\RequestContext\AppContext;
use RcmUser\Service\RcmUserService;

class SiteManagerFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        /**
         * @var $appContext ContainerInterface
         */
        $appContext = $requestContext->get(AppContext::class);

        return new SiteManager(
            $appContext->get('Config'),
            $appContext->get('Doctrine\ORM\EntityManager'),
            $appContext->get(RcmUserService::class),
            $requestContext->get(PageMutationService::class),
            $appContext->get('Rcm\ImmutableHistory\SiteVersionRepo'),
            $appContext->get('Rcm\ImmutableHistory\SiteWideContainerVersionRepo'),
            $appContext->get(PageContentFactory::class)
        );
    }
}
