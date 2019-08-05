<?php

namespace RcmLogin\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface as Delegate;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\RedirectResponse;

/**
 * Middleware that conditionally redirects the user to the login page.
 *
 * Inject a callable object into this. When processing a request, it will be
 * called, passing in the the request. If the callable returns false, the user
 * will be redirected to the login page with a message indicating the user is
 * not allowed to view the page and will be redirected back to the first page
 * after logging in.
 *
 * Callables can be function names, PHP "callable arrays", objects with the
 * __invoke() method, or anything else PHP considers to be callable.
 */
class ConditionalRedirectMiddleware implements MiddlewareInterface
{
    /** @var callable */
    public $isAllowed;

    /**
     * @param callable $isAllowed Returns whether the user is allowed to view a response correlating the passed request
     */
    public function __construct(
        callable $isAllowed
    ) {
        $this->isAllowed = $isAllowed;
    }

    public function process(
        Request $request,
        Delegate $delegate
    ) {
        /** @var callable */
        $isAllowed = $this->isAllowed;

        if (!$isAllowed($request)) {
            $url = '/login?errorCode=unauthorized&redirect='
                . urlencode($request->getUri()->getPath());
            return new RedirectResponse($url);
        }

        return $delegate->process($request);
    }
}
