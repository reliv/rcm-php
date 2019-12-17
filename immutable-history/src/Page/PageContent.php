<?php

namespace Rcm\ImmutableHistory\Page;

use Rcm\ImmutableHistory\ContentInterface;

class PageContent implements ContentInterface
{
    const CONTENT_SCHEMA_VERSION = 1;

    protected $title;
    protected $keywords;
    protected $description;
    protected $blockInstances;
    protected $publicReadAccess;
    protected $readAccessGroups;

    /**
     * PageContent constructor.
     * @param string $title
     * @param string | null $description
     * @param string | null $keywords
     * @param array $blockInstances
     */
    public function __construct(
        string $title,
        $description,
        $keywords,
        array $blockInstances,
        bool $publicReadAccess,
        $readAccessGroups
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->blockInstances = $blockInstances;
        $this->publicReadAccess = $publicReadAccess;
        $this->readAccessGroups = $readAccessGroups;
    }

    public function toArrayForLongTermStorage(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'blockInstances' => $this->blockInstances,
            'publicReadAccess' => $this->publicReadAccess,
            'readAccessGroups' => $this->readAccessGroups,
            'contentSchemaVersion' => self::CONTENT_SCHEMA_VERSION
        ];
    }
}
