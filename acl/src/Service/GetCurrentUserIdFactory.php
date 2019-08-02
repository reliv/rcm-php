<?php

namespace Rcm\Acl\Service;

use Psr\Container\ContainerInterface;
use Rcm\RequestContext\AppContext;
use Rcm\RequestContext\RequestContextBindings;
use RcmUser\Api\Authentication\GetCurrentUser;

/**
 *  @deprecated use Rcm\Acl\GetCurrentUser instead
 *
 * Class GetCurrentUserIdFactory
 * @package Rcm\Acl\Service
 */
class GetCurrentUserIdFactory
{
    public function __invoke(ContainerInterface $requestContext)
    {
        $appContext = $requestContext->get(AppContext::class);

        return new GetCurrentUserId(
            $requestContext->get(RequestContextBindings::CURRENT_REQUEST),
            $appContext->get(GetCurrentUser::class)
        );
    }
}
