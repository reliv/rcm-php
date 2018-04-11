<?php

namespace Rcm\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Rcm\Service\RedirectService;

class RedirectCheck
{
    /**
     * @var RedirectService
     */
    protected $redirectService;

    /**
     * RedirectCheck constructor.
     *
     * @param RedirectService $redirectService
     */
    public function __construct(
        RedirectService $redirectService
    ) {
        $this->redirectService = $redirectService;
    }

    /**
     * If there is a redirect in the DB for the request URL, redirect to it.
     *
     * __invoke
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        // @todo Set from request site
        $redirectUrl = $this->redirectService->getRedirectUrl();

        if (!empty($redirect)) {
            $queryParams = $request->getQueryParams();

            return new RedirectResponse(
                $redirectUrl . (count($queryParams) ? '?' . http_build_query($queryParams) : null)
            );
        }

        return $next($request, $response);
    }
}
