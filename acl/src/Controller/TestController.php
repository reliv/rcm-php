<?php

namespace Rcm\Acl\Controller;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\IsAllowed;
use Rcm\RequestContext\GetRequestContext;
use Rcm\RequestContext\RequestContext;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * @TODO Delete this test controller eventually (it is safe to keep for now though)
 */
class TestController implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $isAllowed = $request->getAttribute(RequestContext::class)->get(IsAllowed::class);
        if (!$isAllowed->__invoke(
            'read',
            ['contentType' => 'page', 'country' => 'USA', 'type' => 'content'])
        ) {
            return new HtmlResponse('access denied', 401);
        }

        return new HtmlResponse('you have access');
    }
}
