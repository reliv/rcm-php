<?php

namespace Rcm\ImmutableHistory\Page;

use Rcm\ImmutableHistory\LocatorInterface;

class PageLocator implements LocatorInterface
{
    protected $siteId;
    protected $pathname;

    public function __construct(int $siteId, string $pathname)
    {
        $this->siteId = $siteId;
        $this->pathname = $pathname;
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
    public function getPathname(): string
    {
        return $this->pathname;
    }

    public function toArray(): array
    {
        return [
            'siteId' => $this->siteId,
            'pathname' => $this->pathname
        ];
    }
}
