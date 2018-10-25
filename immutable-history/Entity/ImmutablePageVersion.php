<?php

namespace Rcm\ImmutableHistory\Entity;

/**
 * @TODO add indexes
 * @TODO can we block update and delete quiries on this entity somehow?
 *
 * @ORM\Table(name="rcm_immutable_page_version")
 */
class ImmutablePageVersion
{
//    public const LOCATOR_FIELD_NAMES = ['siteResourceId', 'relateUrl'];

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
     * @var int|null Can be null
     *
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $fromVersionId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $date;

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
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $siteResourceId;

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

    public function __construct(
        int $resourceId,
        int $fromVersionId,
        \DateTime $date,
        string $status,
        string $action,
        string $userId,
        string $programmaticReason,
        array $locator,
        string $content
    ) {
        $this->fromVersionId = $fromVersionId;
        $this->resourceId = $resourceId;
        $this->status = $status;
        $this->action = $action;
        $this->userId = $userId;
        $this->programmaticReason = $programmaticReason;
        $this->content = $content;
        $this->siteResourceId = $locator['siteResourceId'];
        $this->relativeUrl = $locator['relativeUrl'];
    }

    public function getLocator()
    {
        return [
            'siteResourceId' => $this->siteResourceId,
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
    public function getSiteResourceId(): int
    {
        return $this->siteResourceId;
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
