<?php

namespace Rcm\Acl;

use Psr\Container\ContainerInterface;
use Rcm\RequestContext\AppContext;
use Rcm\RequestContext\RequestContextBindings;
use RcmUser\Api\Authentication\GetCurrentUser as GetUserByRequest;

class GetCurrentUserFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        $appContext = $requestContext->get(AppContext::class);

        return new GetCurrentUser(
            $requestContext->get(RequestContextBindings::CURRENT_REQUEST),
            $appContext->get(GetUserByRequest::class)
        );
    }
}
