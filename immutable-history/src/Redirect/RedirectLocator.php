<?php

namespace Rcm\ImmutableHistory\Redirect;

use Rcm\ImmutableHistory\LocatorInterface;

class RedirectLocator implements LocatorInterface
{
    protected $requestUrl;
    protected $siteId;

    /**
     * RedirectLocator constructor.
     * @param string $requestUrl
     * @param int|null $siteId
     */
    public function __construct(string $requestUrl, $siteId)
    {
        $this->requestUrl = $requestUrl;
        $this->siteId = $siteId;
    }

    /**
     * @return string
     */
    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }

    /**
     * @return int | null
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    public function toArray(): array
    {
        return [
            'requestUrl' => $this->requestUrl,
            'siteId' => $this->siteId,
        ];
    }
}
