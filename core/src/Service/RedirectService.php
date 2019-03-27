<?php

namespace Rcm\Service;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Redirect;

class RedirectService implements RedirectServiceInterface
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
     * @param SiteService $siteService
     */
    public function __construct(
        EntityManager $entityManager,
        SiteService $siteService
    ) {
        $this->redirectRepo = $entityManager->getRepository(Redirect::class);
        $this->siteService = $siteService;
    }

    /**
     * Gets the current siteId. This can be overridden in classes that extend this class.
     *
     * @return int
     */
    protected function getSiteId()
    {
        return $this->siteService->getCurrentSite()->getSiteId();
    }

    /**
     * getRequestUrl
     *
     * @return string
     */
    protected function getRequestUrl()
    {
        $requestUri = PhpServer::getRequestUri();
        $baseUri = explode('?', $requestUri);

        return $baseUri[0];
    }

    /**
     * getRedirectUrl
     *
     * @return null
     */
    public function getRedirectUrl()
    {
        $siteId = $this->getSiteId();

        $requestUrl = $this->getRequestUrl();

        $redirect = $this->redirectRepo->getRedirect($requestUrl, $siteId);

        if (!empty($redirect)) {
            return $redirect->getRedirectUrl();
        }

        return null;
    }
}
