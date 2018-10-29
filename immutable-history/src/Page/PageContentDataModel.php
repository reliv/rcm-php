<?php

namespace Rcm\ImmutableHistory\Page;

use Rcm\ImmutableHistory\ContentInterface;

class PageContentDataModel implements ContentInterface
{
    const CONTET_SCHEMA_VERSION = 1;

    protected $title;
    protected $keywords;
    protected $description;
    protected $blockInstances;

    public function __construct(string $title, string $description, string $keywords, array $blockInstances)
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
            'contentSchemaVersion' => $this::CONTET_SCHEMA_VERSION
        ];
    }
}
