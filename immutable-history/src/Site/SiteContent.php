<?php

namespace Rcm\ImmutableHistory\Site;

use Rcm\ImmutableHistory\ContentInterface;

class SiteContent implements ContentInterface
{
    const CONTENT_SCHEMA_VERSION = 1;

    /**
     * ContainerContent constructor.
     * @param array $blockInstances
     */
    public function __construct()
    {
        //@TODO add lang, country, theme, ect
        throw new \Exception('//@TODO add lang, country, theme, ect');
    }

    public function toArrayForLongTermStorage(): array
    {
        return [
            'contentSchemaVersion' => self::CONTENT_SCHEMA_VERSION
        ];
    }
}
