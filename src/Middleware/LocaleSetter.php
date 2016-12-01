<?php

namespace Rcm\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Rcm\Service\LocaleService;

/**
 * Class LocaleSetter
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class LocaleSetter
{
    /**
     * @var LocaleService
     */
    protected $localeService;

    /**
     * LocaleSetter constructor.
     *
     * @param LocaleService $localeService
     */
    public function __construct(
        LocaleService $localeService
    ) {
        $this->localeService = $localeService;
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
        // Set default locale
        // @todo Set from request site
        $this->localeService->setLocale(null);

        return $next($request, $response);
    }
}
