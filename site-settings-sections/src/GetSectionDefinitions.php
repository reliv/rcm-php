<?php

namespace Rcm\SiteSettingsSections;

class GetSectionDefinitions
{
    /** @var array */
    protected $config;

    public function __construct(
        array $config
    ) {
        $this->config = $config;
    }

    public function __invoke(): array
    {
        return $this->config;
    }
}
