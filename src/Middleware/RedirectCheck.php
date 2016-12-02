<?php

namespace Rcm\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Rcm\Service\RedirectService;

/**
 * Class RedirectCheck
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
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
     * __invoke
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param callable|null     $next
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        // @todo Set from request site
        $redirectUrl = $this->redirectService->getRedirectUrl();

        if (!empty($redirect)) {
            $response = $response->withHeader('Location', $redirectUrl);
            return $response->withStatus(302);
        }

        return $next($request, $response);
    }
}
