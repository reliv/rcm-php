<?php

namespace Rcm\SiteSettingsSections;

use Rcm\Entity\Site;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="rcm_site_settings_section",
 *     indexes={
 *         @ORM\Index(name="site_sectionName_index", columns={"siteId", "sectionName"})
 *     },
 *     uniqueConstraints={
 *        @UniqueConstraint(name="site_sectionName_constraint", columns={"siteId", "sectionName"})
 *    }
 * )
 */
class SiteSettingsSectionEntity
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
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Rcm\Entity\Site")
     * @ORM\JoinColumn(name="siteId", referencedColumnName="siteId")
     **/
    protected $site;

    /**
     * @var string Page name
     *
     * @ORM\Column(type="string")
     */
    protected $sectionName;

    /**
     * @var string Page name
     *
     * @ORM\Column(type="json")
     */
    protected $settings = [];

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $lastModified;

    public function __construct(Site $site, string $sectionName, array $settings, \DateTime $lastModified)
    {
        $this->site = $site;
        $this->sectionName = $sectionName;
        $this->settings = $settings;
        $this->lastModified = $lastModified;
    }

    /**
     * @return Site
     */
    public function getSite(): Site
    {
        return $this->site;
    }

    /**
     * @return string
     */
    public function getSectionName(): string
    {
        return $this->sectionName;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @param \DateTime $lastModified
     */
    public function setLastModified(\DateTime $lastModified): void
    {
        $this->lastModified = $lastModified;
    }
}
