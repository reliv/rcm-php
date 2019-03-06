<?php

namespace Rcm\ImmutableHistory\SiteSettingsSection;

use Doctrine\ORM\Mapping as ORM;
use Rcm\ImmutableHistory\LocatorInterface;
use Rcm\ImmutableHistory\VersionEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="rcm_immutable_site_settings_section_version")
 */
class ImmutableSiteSettingsSectionVersionEntity implements VersionEntityInterface
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $date;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $siteId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $sectionName;

    /**
     * @var array
     *
     * @ORM\Column(type="json", nullable=true)
     */
    protected $content;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $action;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue
     */
    protected $resourceId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $userId;

    /**
     * @var string Page Layout
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $programmaticReason;

    /**
     * ImmutableSiteSettingsSectionVersionEntity constructor.
     * @param string $resourceId
     * @param \DateTime $date
     * @param string $status
     * @param string $action
     * @param string $userId
     * @param string $programmaticReason
     * @param SiteSettingsSectionLocator $locator
     */
    public function __construct(
        string $resourceId,
        \DateTime $date,
        string $status,
        string $action,
        string $userId,
        string $programmaticReason,
        SiteSettingsSectionLocator $locator,
        $content = null
    ) {
        $this->resourceId = $resourceId;
        $this->status = $status;
        $this->action = $action;
        $this->userId = $userId;
        $this->programmaticReason = $programmaticReason;
        $this->sectionName = $locator->getSectionName();
        $this->siteId = $locator->getSiteId();
        $this->date = $date;
        if ($content instanceof SiteSettingsSectionContent) {
            $this->content = $content->toArrayForLongTermStorage();
        } elseif ($content === null) {
            $this->content = null;
        } elseif (is_array($content)) {
            $this->content = $content;
        } else {
            throw new \Exception('Content must be null, instance of SiteSettingsSectionContent, or an array');
        }
    }

    public function getLocator(): LocatorInterface
    {
        return new SiteSettingsSectionLocator($this->siteId, $this->sectionName);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getProgrammaticReason(): string
    {
        return $this->programmaticReason;
    }

    /**
     * @return int
     */
    public function getSiteSettingsSectionId(): int
    {
        return $this->SiteSettingsSectionId;
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

    /**
     * @return array | null
     */
    public function getContentAsArray()
    {
        return $this->content;
    }
}
