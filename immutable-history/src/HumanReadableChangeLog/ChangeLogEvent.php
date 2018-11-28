<?php

namespace Rcm\ImmutableHistory\HumanReadableChangeLog;

class ChangeLogEvent
{
    /**
     * @var \DateTime
     */
    protected $date;
    /**
     * @var string
     */
    protected $userId;
    /**
     * @var string
     */
    protected $userDescription;

    /**
     * @var string
     */
    protected $actionDescription;

    /**
     * @var array
     */
    protected $resourceLocatorArray;

    /**
     * @var string
     */
    protected $resourceLocationDescription;

    /**
     * @var string
     */
    protected $parentCurrentLocationDescription;

    /**
     * @var string
     */
    protected $resourceTypeDescription;

    /**
     * @var int
     */
    protected $versionId;

    /**
     * ChangeLogEvent constructor.
     * @param \DateTime $date
     * @param string $userId
     * @param string $userDescription
     * @param string $actionDescription
     * @param array $resourceLocatorArray
     * @param array $resourceLocationDescription
     * @param array $parentCurrentLocationDescription
     * @param string $resourceTypeDescription
     * @param int $versionId
     */
    public function __construct(
        \DateTime $date,
        string $userId,
        string $userDescription,
        string $actionDescription,
        array $resourceLocatorArray,
        string $resourceLocationDescription,
        string $parentCurrentLocationDescription,
        string $resourceTypeDescription,
        int $versionId
    ) {
        $this->date = $date;
        $this->userId = $userId;
        $this->userDescription = $userDescription;
        $this->actionDescription = $actionDescription;
        $this->resourceLocatorArray = $resourceLocatorArray;
        $this->resourceLocationDescription = $resourceLocationDescription;
        $this->parentCurrentLocationDescription = $parentCurrentLocationDescription;
        $this->resourceTypeDescription = $resourceTypeDescription;
        $this->versionId = $versionId;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getUserDescription(): string
    {
        return $this->userDescription;
    }

    /**
     * @return string
     */
    public function getActionDescription(): string
    {
        return $this->actionDescription;
    }

    /**
     * @return array
     */
    public function getResourceLocatorArray(): array
    {
        return $this->resourceLocatorArray;
    }

    /**
     * @return string
     */
    public function getResourceLocationDescription(): string
    {
        return $this->resourceLocationDescription;
    }

    /**
     * @return string
     */
    public function getParentCurrentLocationDescription(): string
    {
        return $this->parentCurrentLocationDescription;
    }

    /**
     * @return string
     */
    public function getResourceTypeDescription(): string
    {
        return $this->resourceTypeDescription;
    }

    /**
     * @return int
     */
    public function getVersionId(): int
    {
        return $this->versionId;
    }
}
