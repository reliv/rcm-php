<?php

namespace Rcm\ImmutableHistory\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @TODO add indexes
 * @TODO can we block update and delete quiries on this entity somehow?
 * @ORM\Entity
 * @ORM\Table(name="rcm_immutable_page_version")
 */
class ImmutablePageVersion
{
//    public const LOCATOR_FIELD_NAMES = ['siteId', 'relateUrl'];

    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $versionId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
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
    protected $relativeUrl;

    /**
     * @var string
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
     * @var int|null Can be null
     *
     * @ORM\Column(type="integer", nullable=true)
     * @ORM\GeneratedValue
     */
    protected $fromVersionId;

    /**
     * ImmutablePageVersion constructor.
     * @param int $resourceId
     * @param int|null $fromVersionId
     * @param \DateTime $date
     * @param string $status
     * @param string $action
     * @param string $userId
     * @param string $programmaticReason
     * @param array $locator
     * @param array $content
     */
    public function __construct(
        int $resourceId,
        $fromVersionId,
        \DateTime $date,
        string $status,
        string $action,
        string $userId,
        string $programmaticReason,
        array $locator,
        array $content
    ) {
        $this->fromVersionId = $fromVersionId;
        $this->resourceId = $resourceId;
        $this->status = $status;
        $this->action = $action;
        $this->userId = $userId;
        $this->programmaticReason = $programmaticReason;
        $this->content = $content;
        $this->siteId = $locator['siteId'];
        $this->relativeUrl = $locator['relativeUrl'];
        $this->date = $date;
    }

    public function getLocator()
    {
        return [
            'siteId' => $this->siteId,
            'relativeUrl' => $this->relativeUrl
        ];
    }

    /**
     * @return int
     */
    public function getVersionId(): int
    {
        return $this->versionId;
    }

    /**
     * @return int
     */
    public function getFromVersionId(): int
    {
        return $this->fromVersionId;
    }

    /**
     * @return int
     */
    public function getResourceId(): int
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
    public function getRelativeUrl(): string
    {
        return $this->relativeUrl;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

}
