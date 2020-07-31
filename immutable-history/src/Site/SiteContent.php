<?php

namespace Rcm\ImmutableHistory\Site;

use Rcm\ImmutableHistory\ContentInterface;

class SiteContent implements ContentInterface
{
    const CONTENT_SCHEMA_VERSION = 2;

    protected $status;
    protected $countryIso3;
    protected $languageId;
    protected $theme;
    protected $siteTitle;
    protected $faviconUrl;
    protected $loginPage;
    protected $notAuthorizedPage;
    protected $notFoundPage;
    protected $siteLayout;

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
        string $faviconUrl,
        string $loginPage,
        string $notAuthorizedPage,
        string $notFoundPage,
        string $siteLayout
    ) {
        $this->status = $status;
        $this->countryIso3 = $countryIso3;
        $this->languageId = $languageId;
        $this->theme = $theme;
        $this->siteTitle = $siteTitle;
        $this->faviconUrl = $faviconUrl;
        $this->loginPage = $loginPage;
        $this->notAuthorizedPage = $notAuthorizedPage;
        $this->notFoundPage = $notFoundPage;
        $this->siteLayout = $siteLayout;
    }

    public function toArrayForLongTermStorage(): array
    {
        return [
            'contentSchemaVersion' => self::CONTENT_SCHEMA_VERSION,
            'countryIso3' => $this->countryIso3,
            'faviconUrl' => $this->faviconUrl,
            'languageId' => $this->languageId,
            'loginPage' => $this->loginPage,
            'notAuthorizedPage' => $this->notAuthorizedPage,
            'notFoundPage' => $this->notFoundPage,
            'siteLayout' => $this->siteLayout,
            'siteTitle' => $this->siteTitle,
            'status' => $this->status,
            'theme' => $this->theme,
        ];
    }
}
