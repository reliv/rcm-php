<?php

namespace Rcm\ImmutableHistory\SiteWideContainer;

use Rcm\ImmutableHistory\ContentInterface;

class ContainerContent implements ContentInterface
{
    const CONTENT_SCHEMA_VERSION = 1;

    protected $blockInstances;

    /**
     * ContainerContent constructor.
     * @param array $blockInstances
     */
    public function __construct(array $blockInstances)
    {
        $this->blockInstances = $blockInstances;
    }

    public function toArrayForLongTermStorage(): array
    {
        return [
            'blockInstances' => $this->blockInstances,
            'contentSchemaVersion' => self::CONTENT_SCHEMA_VERSION
        ];
    }
}
