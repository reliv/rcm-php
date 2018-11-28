<?php

namespace Rcm\ImmutableHistory\Site;

use Rcm\ImmutableHistory\LocatorInterface;

class SiteLocator implements LocatorInterface
{
    protected $host;

    public function __construct(string $host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    public function toArray(): array
    {
        return [
            'host' => $this->host,
        ];
    }
}
