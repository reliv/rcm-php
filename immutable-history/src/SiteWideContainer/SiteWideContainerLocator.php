<?php

namespace Rcm\ImmutableHistory\SiteWideContainer;

use Rcm\ImmutableHistory\LocatorInterface;

class SiteWideContainerLocator implements LocatorInterface
{
    protected $siteId;
    protected $name;

    public function __construct(int $siteId, string $name)
    {
        $this->siteId = $siteId;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getSiteId(): int
    {
        return $this->siteId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'siteId' => $this->siteId,
            'name' => $this->name
        ];
    }
}
