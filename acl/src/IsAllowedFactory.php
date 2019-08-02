<?php

namespace Rcm\Acl;

use Psr\Container\ContainerInterface;
use Rcm\Acl\Service\GetCurrentUserId;
use Rcm\Acl\Service\GetGroupIdsByUserId;
use Rcm\RequestContext\RequestContextBindings;

class IsAllowedFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        $appContainer = $requestContext->get(RequestContextBindings::SERVICE_MANAGER);

        return new IsAllowed(
            $appContainer->get(RunQuery::class),
            $requestContext->get(GetCurrentUserId::class),
            $appContainer->get(GetGroupIdsByUserId::class)
        );
    }
}
