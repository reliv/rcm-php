<?php

namespace Rcm\Service;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Site;
use Rcm\Tracking\Model\Tracking;
use Zend\Validator\Ip;

/**
 * Class Site
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class SiteService
{
    /**
     * @var DomainService
     */
    protected $domainService;

    /**
     * @var \Rcm\Repository\Site
     */
    protected $siteRepo;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @param DomainService $domainService
     * @param EntityManager $entityManager
     */
    public function __construct(
        DomainService $domainService,
        EntityManager $entityManager
    ) {
        $this->domainService = $domainService;
        $this->siteRepo = $entityManager->getRepository(Site::class);
    }

    /**
     * setCache
     *
     * @param string $domain
     * @param Site   $site
     *
     * @return void
     */
    protected function setCache($domain, Site $site)
    {
        $this->cache[$domain] = $site;
    }

    /**
     * getCache
     *
     * @param $domain
     *
     * @return mixed|null
     */
    protected function getCache($domain)
    {
        if (array_key_exists($domain, $this->cache)) {
            return $this->cache[$domain];
        }

        return null;
    }

    /**
     * isConsoleRequest
     *
     * @return bool
     */
    public function isConsoleRequest()
    {
        return PhpServer::isCliRequest();
    }

    /**
     * getSite
     *
     * @param string $domain
     * @param bool   $useCache
     *
     * @return mixed|null|Site
     */
    public function getSite($domain, $useCache = true)
    {
        $site = null;

        if ($useCache) {
            $site = $this->getCache($domain);
        }

        if (!empty($site)) {
            return $site;
        }

        $site = $this->siteRepo->getSiteByDomain($domain);

        if (empty($site)) {
            $site = new Site(
                Tracking::UNKNOWN_USER_ID,
                'Fake site due to site domain not found ' . get_class($this)
            );
        }

        return $site;
    }

    /**
     * getCurrentDomain
     *
     * @param null $default
     *
     * @return null|string
     */
    public function getCurrentDomain($default = null)
    {
        $currentDomain = PhpServer::getRequestDomain();

        //Use the default site if the requested domain name is an IP address
        $ipValidator = new Ip();
        if ($ipValidator->isValid($currentDomain)) {
            $currentDomain = $default;
        }

        return $currentDomain;
    }

    /**
     * getSiteDomain
     *
     * @param Site $site
     *
     * @return null|string
     */
    public function getSiteDomain(Site $site)
    {
        $domain = $site->getDomain();

        if (empty($domain)) {
            return $this->domainService->getDefaultDomainName();
        }

        $domainName = $domain->getDomainName();

        if (empty($domainName)) {
            return $this->domainService->getDefaultDomainName();
        }

        return $domainName;
    }

    /**
     * getCurrentSite
     *
     * @param string|null $currentDomain
     * @param bool        $useCache
     *
     * @return mixed|null|Site
     */
    public function getCurrentSite($currentDomain = null, $useCache = true)
    {
        if ($this->isConsoleRequest()) {
            // Fake Site for console
            return new Site(
                Tracking::UNKNOWN_USER_ID,
                'Fake site for console in ' . get_class($this)
            );
        }

        if (empty($currentDomain)) {
            $currentDomain = $this->getCurrentDomain(
                $this->domainService->getDefaultDomainName()
            );
        }

        return $this->getSite($currentDomain, $useCache);
    }
}
