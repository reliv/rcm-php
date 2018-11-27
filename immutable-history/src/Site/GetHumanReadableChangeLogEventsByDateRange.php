<?php

namespace Rcm\ImmutableHistory\Site;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEvent;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRangeAbstract;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRangeInterface;
use Rcm\ImmutableHistory\Site\SiteIdToDomainName;
use Rcm\ImmutableHistory\User\UserIdToUserFullName;
use Rcm\ImmutableHistory\VersionActions;
use Rcm\ImmutableHistory\VersionEntityInterface;

class GetHumanReadableChangeLogEventsByDateRange extends GetHumanReadableChangeLogEventsByDateRangeAbstract
{
    protected function getResourceTypeDescription(VersionEntityInterface $version)
    {
        return 'site';
    }

    protected function getParentCurrentLocationDescription(VersionEntityInterface $version)
    {
        return 'N/A';
    }

    protected function getResourceLocationDescription(VersionEntityInterface $version)
    {
        return $version->getHost();
    }
}
