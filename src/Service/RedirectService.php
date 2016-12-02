<?php

namespace Rcm\Service;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Redirect;

/**
 * Class RedirectService
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class RedirectService
{
    /**
     * @var \Rcm\Repository\Redirect
     */
    protected $redirectRepo;

    /**
     * @var SiteService
     */
    protected $siteService;

    /**
     * RedirectService constructor.
     *
     * @param EntityManager $entityManager
     * @param SiteService   $siteService
     */
    public function __construct(
        EntityManager $entityManager,
        SiteService $siteService
    ) {
        $this->redirectRepo = $entityManager->getRepository(Redirect::class);
        $this->siteService = $siteService;
    }

    /**
     * getRequestUrl
     *
     * @return string
     */
    public function getRequestUrl()
    {
        $requestUri = PhpServer::getRequestUri();
        $baseUri = explode('?', $requestUri);

        return $baseUri[0];
    }

    /**
     * getRedirectUrl
     *
     * @param int|null    $siteId
     * @param string|null $requestUrl
     *
     * @return null
     */
    public function getRedirectUrl($siteId = null, $requestUrl = null)
    {
        if (empty($siteId)) {
            $siteId = $this->siteService->getCurrentSite()->getSiteId();
        }

        if (empty($siteId)) {
            return null;
        }

        if (empty($requestUrl)) {
            $requestUrl = $this->getRequestUrl();
        }

        $redirect = $this->redirectRepo->getRedirect($requestUrl, $siteId);

        if (!empty($redirect)) {
            return $redirect->getRedirectUrl();
        }

        return null;
    }
}
