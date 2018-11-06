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

    /**
     * PageContent constructor.
     * @param string $title
     * @param string | null $description
     * @param string | null $keywords
     * @param array $blockInstances
     */
    public function __construct(string $title, $description, $keywords, array $blockInstances)
    {
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->blockInstances = $blockInstances;
    }

    public function toArrayForLongTermStorage(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'blockInstances' => $this->blockInstances,
            'contentSchemaVersion' => $this::CONTENT_SCHEMA_VERSION
        ];
    }
}
