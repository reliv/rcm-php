<?php

namespace Rcm\ImmutableHistory\Page;

use Rcm\ImmutableHistory\LocatorInterface;

class PageLocatorDataModel implements LocatorInterface
{
    protected $siteId;
    protected $relativeUrl;

    public function __construct(int $siteId, string $relativeUrl)
    {
        $this->siteId = $siteId;
        $this->relativeUrl = $relativeUrl;
    }

    /**
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @return string
     */
    public function getRelativeUrl()
    {
        return $this->relativeUrl;
    }
}
