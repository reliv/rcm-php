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
     * @var array Page name
     *
     * @ORM\Column(type="json")
     */
    protected $settings = [];

    public function __construct(Site $site, string $sectionName, array $settings)
    {
        $this->site = $site;
        $this->sectionName = $sectionName;
        $this->settings = $settings;
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
}
