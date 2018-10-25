<?php

namespace Rcm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rcm\Tracking\Model\Tracking;

/**
 * Page Information Entity
 *
 * This is a Doctrine 2 definition file for plugin containers.  This file
 * is used for any module that needs to know container information.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity (repositoryClass="Rcm\Repository\Container")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table (
 *     name="rcm_containers",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="uq_name_site",
 *             columns={"name", "siteId"}
 *         )
 *     },
 *     indexes={
 *         @ORM\Index(name="container_name", columns={"name"})
 *     }
 * )
 *
 */
class Container extends ContainerAbstract implements Tracking
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $containerId;

    /**
     * @var string Container name
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string Authors name
     *
     * @ORM\Column(type="string")
     */
    protected $author;

    /**
     * @var \DateTime Date page was last published
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastPublished;

    /**
     * @var \Rcm\Entity\Revision Integer Current Page Revision ID
     *
     * @ORM\OneToOne(targetEntity="Revision")
     * @ORM\JoinColumn(name="publishedRevisionId", referencedColumnName="revisionId")
     */
    protected $publishedRevision;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $publishedRevisionId;

    /**
     * @var \Rcm\Entity\Revision Integer Staged Revision ID
     *
     * @ORM\OneToOne(targetEntity="Revision")
     * @ORM\JoinColumn(name="stagedRevisionId", referencedColumnName="revisionId")
     */
    protected $stagedRevision;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $stagedRevisionId;

    /**
     * @var \Rcm\Entity\Site
     *
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="containers")
     * @ORM\JoinColumn(name="siteId", referencedColumnName="siteId")
     **/
    protected $site;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $siteId;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="Revision",
     *     indexBy="revisionId",
     *     cascade={"persist"}
     * )
     * @ORM\JoinTable(
     *     name="rcm_containers_revisions",
     *     joinColumns={
     *         @ORM\JoinColumn(
     *             name="containerId",
     *             referencedColumnName="containerId",
     *             onDelete="CASCADE"
     *         )
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(
     *             name="revisionId",
     *             referencedColumnName="revisionId",
     *             onDelete="CASCADE"
     *         )
     *     }
     * )
     **/
    protected $revisions;

    /**
     * <tracking>
     *
     * @var \DateTime Date object was first created
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdDate;

    /**
     * <tracking>
     *
     * @var string User ID of creator
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $createdByUserId;

    /**
     * <tracking>
     *
     * @var string Short description of create reason
     *
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $createdReason = Tracking::UNKNOWN_REASON;

    /**
     * <tracking>
     *
     * @var \DateTime Date object was modified
     *
     * @ORM\Column(type="datetime")
     */
    protected $modifiedDate;

    /**
     * <tracking>
     *
     * @var string User ID of modifier
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $modifiedByUserId;

    /**
     * <tracking>
     *
     * @var string Short description of create reason
     *
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $modifiedReason = Tracking::UNKNOWN_REASON;

    /**
     * @param string $createdByUserId <tracking>
     * @param string $createdReason   <tracking>
     */
    public function __construct(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        $this->revisions = new ArrayCollection();

        parent::__construct($createdByUserId, $createdReason);
    }

    /**
     * Get a clone with special logic
     *
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return static
     */
    public function newInstance(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        /** @var static $new */
        $new = parent::newInstance(
            $createdByUserId,
            $createdReason
        );

        // if no id, then it has not been save and can be returned
        if (empty($new->containerId)) {
            return $new;
        }

        $new->containerId = null;

        return $new;
    }

    /**
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return null|ContainerAbstract|ContainerInterface
     */
    public function newInstanceIfHasRevision(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        $new = parent::newInstanceIfHasRevision(
            $createdByUserId,
            $createdReason
        );

        if (empty($new)) {
            return null;
        }

        $new->containerId = null;

        return $new;
    }

    /**
     * Get the current Page ID
     *
     * @return int Unique page ID number
     */
    public function getContainerId()
    {
        return $this->containerId;
    }

    /**
     * @deprecated Do NOT use
     * Set the ID of the Page.  This was added for unit testing and should
     * not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own,
     *
     * @param int $containerId Unique Page ID
     *
     * @return void
     */
    public function setContainerId($containerId)
    {
        $this->containerId = $containerId;
    }

    /**
     * @return void
     *
     * @ORM\PrePersist
     */
    public function assertHasTrackingData()
    {
        parent::assertHasTrackingData();
    }

    /**
     * @return void
     *
     * @ORM\PreUpdate
     */
    public function assertHasNewModifiedData()
    {
        parent::assertHasNewModifiedData();
    }
}
