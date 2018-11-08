<?php

namespace Rcm\ImmutableHistory\HumanReadableChangeLog;

use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEvent;
use Rcm\ImmutableHistory\VersionActions;

interface GetHumanReadableChangeLogEventsByDateRangeInterface
{
    /**
     * @param \DateTime $greaterThanDate
     * @param \DateTime $lessThanDate
     * @return array of ChangeLogEvent
     */
    public function __invoke(\DateTime $greaterThanDate, \DateTime $lessThanDate): array;
}
