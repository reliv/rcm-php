<?php

namespace Rcm\ImmutableHistory\Page;

use Doctrine\ORM\Mapping as ORM;
use Rcm\ImmutableHistory\LocatorInterface;
use Rcm\ImmutableHistory\VersionEntityInterface;

/**
 * @TODO add indexes
 * @TODO can we block update and delete quiries on this entity somehow?
 * @ORM\Entity
 * @ORM\Table(name="rcm_immutable_page_version")
 */
class ImmutablePageVersionEntity implements VersionEntityInterface
{
//    public const LOCATOR_FIELD_NAMES = ['siteId', 'relateUrl'];

    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue
     */
    protected $resourceId;

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
    protected $pathname;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
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
     */
    protected $userId;

    /**
     * @var string Page Layout
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $programmaticReason;

    /**
     * ImmutablePageVersion constructor.
     * @param string $resourceId
     * @param \DateTime $date
     * @param string $status
     * @param string $action
     * @param string $userId
     * @param string $programmaticReason
     * @param PageLocator $locator
     * @param ContentInterface $content
     */
    public function __construct(
        string $resourceId,
        \DateTime $date,
        string $status,
        string $action,
        string $userId,
        string $programmaticReason,
        PageLocator $locator,
        PageContent $content
    ) {
        $this->resourceId = $resourceId;
        $this->status = $status;
        $this->action = $action;
        $this->userId = $userId;
        $this->programmaticReason = $programmaticReason;
        $this->content = $content->toArrayForLongTermStorage();
        $this->siteId = $locator->getSiteId();
        $this->pathname = $locator->getPathname();
        $this->date = $date;
    }

    public function getLocator(): PageLocator
    {
        return new PageLocator($this->siteId, $this->pathname);
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
    public function getPathname(): string
    {
        return $this->pathname;
    }

    /**
     * @return array
     */
    public function getContentAsArray(): array
    {
        return $this->content;
    }

}
