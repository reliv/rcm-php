<?php

namespace Rcm\ImmutableHistory\SiteSettingsSection;

use Rcm\ImmutableHistory\ContentInterface;

class SiteSettingsSectionContent implements ContentInterface
{
    const CONTENT_SCHEMA_VERSION = 1;

    protected $settings;
    protected $siteId;

    public function __construct(
        array $settings
    ) {
        $this->settings = $settings;
    }

    public function toArrayForLongTermStorage(): array
    {
        return [
            'settings' => $this->settings,
            'contentSchemaVersion' => self::CONTENT_SCHEMA_VERSION,
        ];
    }
}
