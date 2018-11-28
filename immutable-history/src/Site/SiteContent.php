<?php

namespace Rcm\ImmutableHistory\Site;

use Rcm\ImmutableHistory\ContentInterface;

class SiteContent implements ContentInterface
{
    const CONTENT_SCHEMA_VERSION = 1;

    protected $status;
    protected $countryIso3;
    protected $languageId;
    protected $theme;
    protected $siteTitle;
    protected $faviconUrl;

    /**
     * ContainerContent constructor.
     * @param array $blockInstances
     */
    public function __construct(
        string $status,
        string $countryIso3,
        int $languageId,
        string $theme,
        string $siteTitle,
        string $faviconUrl
    ) {
        $this->status = $status;
        $this->countryIso3 = $countryIso3;
        $this->languageId = $languageId;
        $this->theme = $theme;
        $this->siteTitle = $siteTitle;
        $this->faviconUrl = $faviconUrl;
    }

    public function toArrayForLongTermStorage(): array
    {
        return [
            'status' => $this->status,
            'countryIso3' => $this->countryIso3,
            'languageId' => $this->languageId,
            'theme' => $this->theme,
            'siteTitle' => $this->siteTitle,
            'faviconUrl' => $this->faviconUrl,
            'contentSchemaVersion' => self::CONTENT_SCHEMA_VERSION
        ];
    }
}
