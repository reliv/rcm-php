<?php

namespace Rcm\Acl\Service;

use Psr\Container\ContainerInterface;
use Rcm\RequestContext\RequestContextBindings;
use RcmUser\Api\Authentication\GetCurrentUser;

class GetCurrentUserIdFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        $appContainer = $requestContext->get(RequestContextBindings::SERVICE_MANAGER);

        return new GetCurrentUserId(
            $requestContext->get(RequestContextBindings::CURRENT_REQUEST),
            $appContainer->get(GetCurrentUser::class)
        );
    }
}
