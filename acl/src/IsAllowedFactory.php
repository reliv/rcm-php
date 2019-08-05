<?php

namespace Rcm\Acl;

use Psr\Container\ContainerInterface;
use Rcm\Acl\Service\GetCurrentUserId;
use Rcm\Acl\Service\GetGroupIdsByUserId;
use Rcm\RequestContext\AppContext;
use Rcm\RequestContext\RequestContextBindings;

class IsAllowedFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        $appContext = $requestContext->get(AppContext::class);

        return new IsAllowed(
            $appContext->get(RunQuery::class),
            $requestContext->get(GetCurrentUserId::class),
            $appContext->get(GetGroupIdsByUserId::class)
        );
    }
}
