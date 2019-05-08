<?php

namespace Rcm\ImmutableHistory\I18nMessage;

use Doctrine\ORM\Mapping as ORM;
use Rcm\ImmutableHistory\LocatorInterface;
use Rcm\ImmutableHistory\VersionEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="rcm_immutable_i18n_message_version")
 */
class ImmutableI18nMessageVersionEntity implements VersionEntityInterface
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $locale;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $defaultText;

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
     * ImmutableRedirectVersionEntity constructor.
     * @param string $resourceId
     * @param \DateTime $date
     * @param string $status
     * @param string $action
     * @param string $userId
     * @param string $programmaticReason
     * @param RedirectLocator $locator
     */
    public function __construct(
        string $resourceId,
        \DateTime $date,
        string $status,
        string $action,
        string $userId,
        string $programmaticReason,
        RedirectLocator $locator,
        $content = null
    ) {
        $this->resourceId = $resourceId;
        $this->status = $status;
        $this->action = $action;
        $this->userId = $userId;
        $this->programmaticReason = $programmaticReason;
        $this->locale = $locator->getLocale();
        $this->defaultText = $locator->getDefaultText();
        $this->date = $date;
        if ($content instanceof I18nMessageContent) {
            $this->content = $content->toArrayForLongTermStorage();
        } elseif ($content === null) {
            $this->content = null;
        } elseif (is_array($content)) {
            $this->content = $content;
        } else {
            throw new \Exception('Content must be null, instance of I18nMessageContent, or an array');
        }
    }

    public function getLocator(): LocatorInterface
    {
        return new I18nMessageLocator($this->locale, $this->defaultText);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
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
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getDefaultText()
    {
        return $this->defaultText;
    }

    /**
     * @return array | null
     */
    public function getContentAsArray()
    {
        return $this->content;
    }
}
