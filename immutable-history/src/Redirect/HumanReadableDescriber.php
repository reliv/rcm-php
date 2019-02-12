<?php

namespace Rcm\ImmutableHistory\Redirect;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEvent;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRangeAbstract;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRangeInterface;
use Rcm\ImmutableHistory\HumanReadableVersionDescriber;
use Rcm\ImmutableHistory\Redirect\RedirectIdToDomainName;
use Rcm\ImmutableHistory\Site\SiteIdToDomainName;
use Rcm\ImmutableHistory\User\UserIdToUserFullName;
use Rcm\ImmutableHistory\VersionActions;
use Rcm\ImmutableHistory\VersionEntityInterface;

class HumanReadableDescriber implements HumanReadableVersionDescriber
{
    protected $siteIdToDomainName;

    public function __construct(
        SiteIdToDomainName $siteIdToDomainName
    ) {
        $this->siteIdToDomainName = $siteIdToDomainName;
    }

    public function getResourceTypeDescription(VersionEntityInterface $version): string
    {
        return 'redirect';
    }

    public function getParentCurrentLocationDescription(VersionEntityInterface $version): string
    {
        if ($version->getSiteId() === null) {
            return 'all websites';
        }

        return $this->siteIdToDomainName->__invoke($version->getSiteId());
    }

    public function getResourceLocationDescription(VersionEntityInterface $version): string
    {
        return $version->getRequestUrl();
    }
}
