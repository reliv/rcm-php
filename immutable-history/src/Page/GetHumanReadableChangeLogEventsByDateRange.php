<?php

namespace Rcm\ImmutableHistory\Page;

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
    protected $siteIdToDomainName;

    public function __construct(
        EntityManager $entityManger,
        SiteIdToDomainName $siteIdToDomainName,
        string $versionEntityClassName,
        UserIdToUserFullName $userIdToUserFullName
    ) {
        parent::__construct(
            $entityManger,
            $userIdToUserFullName,
            $versionEntityClassName
        );
        $this->siteIdToDomainName = $siteIdToDomainName;
    }

    protected function getResourceTypeDescription(VersionEntityInterface $version)
    {
        return 'page';
    }

    protected function getParentCurrentLocationDescription(VersionEntityInterface $version)
    {
        return $this->siteIdToDomainName->__invoke($version->getSiteId());
    }

    protected function getResourceLocationDescription(VersionEntityInterface $version)
    {
        return $version->getPathName();
    }
}
