<?php

namespace Rcm\Service;

use Rcm\Entity\Site;
use Zend\Validator\Ip;

/**
 * Class DomainRedirectService
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class DomainRedirectService
{
    /**
     * @var DomainService
     */
    protected $domainService;

    /**
     * DomainRedirectService constructor.
     *
     * @param DomainService $domainService
     */
    public function __construct(
        DomainService $domainService
    ) {
        $this->domainService = $domainService;
    }

    /**
     * getSiteNotAvailableRedirectUrl
     *
     * @param Site $site
     *
     * @return string
     */
    public function getSiteNotAvailableRedirectUrl(Site $site)
    {
        if ($site->isSiteAvailable()) {
            return null;
        }

        $defaultDomain = $this->domainService->getDefaultDomainName();

        if (empty($defaultDomain)) {
            return null;
        }

        if ($defaultDomain == $site->getDomain()->getDomainName()) {
            return null;
        }

        return $defaultDomain;
    }

    /**
     * getPrimaryRedirectUrl
     * If the IP is not a domain and is not the primary, return redirect for primary
     *
     * @param Site $site
     *
     * @return null|string
     */
    public function getPrimaryRedirectUrl(Site $site)
    {
        $currentDomain = $site->getDomain()->getDomainName();

        $ipValidator = new Ip();
        $isIp = !$ipValidator->isValid($currentDomain);
        if ($isIp) {
            return null;
        }

        $primaryDomain = $site->getDomain()->getPrimary();
        if (empty($primaryDomain)) {
            return null;
        }

        if ($primaryDomain->getDomainName() == $currentDomain) {
            return null;
        }

        return $primaryDomain->getDomainName();
    }
}
