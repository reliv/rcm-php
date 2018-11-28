<?php

namespace Rcm\ImmutableHistory\Site;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEvent;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRangeAbstract;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRangeInterface;
use Rcm\ImmutableHistory\HumanReadableVersionDescriber;
use Rcm\ImmutableHistory\Site\SiteIdToDomainName;
use Rcm\ImmutableHistory\User\UserIdToUserFullName;
use Rcm\ImmutableHistory\VersionActions;
use Rcm\ImmutableHistory\VersionEntityInterface;

class HumanReadableDescriber implements HumanReadableVersionDescriber
{
    public function getResourceTypeDescription(VersionEntityInterface $version): string
    {
        return 'site';
    }

    public function getParentCurrentLocationDescription(VersionEntityInterface $version): string
    {
        return 'N/A';
    }

    public function getResourceLocationDescription(VersionEntityInterface $version): string
    {
        return $version->getHost();
    }
}
