<?php

namespace Rcm\ImmutableHistory\SiteWideContainer;

use Doctrine\ORM\Mapping as ORM;
use Rcm\ImmutableHistory\LocatorInterface;
use Rcm\ImmutableHistory\VersionEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="rcm_immutable_site_wide_container_version")
 */
class ImmutableSiteWideContainerVersionEntity implements VersionEntityInterface
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
     * @ORM\Column(type="integer")
     */
    protected $siteId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

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
     * ImmutableSiteWideContainerVersionEntity constructor.
     * @param string $resourceId
     * @param \DateTime $date
     * @param string $status
     * @param string $action
     * @param string $userId
     * @param string $programmaticReason
     * @param SiteWideContainerLocator $locator
     * @param null $content
     * @throws \Exception
     */
    public function __construct(
        string $resourceId,
        \DateTime $date,
        string $status,
        string $action,
        string $userId,
        string $programmaticReason,
        SiteWideContainerLocator $locator,
        $content = null
    ) {
        $this->resourceId = $resourceId;
        $this->status = $status;
        $this->action = $action;
        $this->userId = $userId;
        $this->programmaticReason = $programmaticReason;
        if ($content instanceof ContainerContent) {
            $this->content = $content->toArrayForLongTermStorage();
        } elseif ($content === null) {
            $this->content = null;
        } elseif (is_array($content)) {
            $this->content = $content;
        } else {
            throw new \Exception('Content must be null, instance of ContainerContent, or an array');
        }
        $this->siteId = $locator->getSiteId();
        $this->name = $locator->getName();
        $this->date = $date;
    }

    public function getLocator(): LocatorInterface
    {
        return new SiteWideContainerLocator($this->siteId, $this->name);
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
    public function getSiteId(): int
    {
        return $this->siteId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array | null
     */
    public function getContentAsArray()
    {
        return $this->content;
    }
}
