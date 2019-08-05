<?php

namespace Rcm\Acl;

use Psr\Container\ContainerInterface;
use Rcm\Acl\Service\GetCurrentUserId;
use Rcm\Acl\Service\GetGroupIdsByUserId;
use Rcm\RequestContext\AppContext;
use Rcm\RequestContext\RequestContextBindings;

class AssertIsAllowedFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        $appContext = $requestContext->get(AppContext::class);

        return new AssertIsAllowed(
            $requestContext->get(IsAllowed::class)
        );
    }
}
