<?php

namespace Rcm\ImmutableHistory\HumanReadableChangeLog;

class ChangeLogEvent
{
    /**
     * @var \DateTime
     */
    public $date;
    /**
     * @var string
     */
    public $userId;
    /**
     * @var string
     */
    public $userDescription;

    /**
     * @var string
     */
    public $actionDescription;

    /**
     * @var array
     */
    public $resourceLocatorArray;

    /**
     * @var array
     */
    public $resourceLocationDescription;

    /**
     * @var array
     */
    public $parentCurrentLocationDescription;

    /**
     * @var string
     */
    public $resourceTypeDescription;

    /**
     * @var string
     */
    public $resourceDescription;

    /**
     * @var int
     */
    public $versionId;
}
