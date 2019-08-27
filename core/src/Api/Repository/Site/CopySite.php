<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Rcm\Api\Repository\Options;
use Rcm\Entity\Container;
use Rcm\Entity\Domain;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Tracking\Model\Tracking;

/**
 * @deprecated Use SiteSecureRepo instead
 */
class CopySite
{
    /**
     * @deprecated Use SiteSecureRepo instead
     * @param Site $sourceSite
     * @param string $newDomainName
     * @param array $newSiteData
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array $options
     *
     * @return Site
     * @throws \Exception
     */
    public function __invoke(
        Site $sourceSite,
        string $newDomainName,
        array $newSiteData,
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON,
        array $options = []
    ) {

        throw new \Exception(
            'This was disabled during audit project in 2018-11 because'
            . ' didn\'t apear to be in use and doesn\'t follow audit log rules'
        );
    }
}
