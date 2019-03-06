<?php

namespace Rcm\ImmutableHistory\SiteSettingsSection;

use Rcm\ImmutableHistory\LocatorInterface;

class SiteSettingsSectionLocator implements LocatorInterface
{
    protected $sectionName;
    protected $siteId;

    /**
     * SiteSettingsSectionLocator constructor.
     * @param $siteId
     * @param string $sectionName
     */
    public function __construct($siteId, string $sectionName)
    {
        $this->sectionName = $sectionName;
        $this->siteId = $siteId;
    }

    /**
     * @return string
     */
    public function getSectionName(): string
    {
        return $this->sectionName;
    }

    /**
     * @return int | null
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    public function toArray(): array
    {
        return [
            'sectionName' => $this->sectionName,
            'siteId' => $this->siteId,
        ];
    }
}
