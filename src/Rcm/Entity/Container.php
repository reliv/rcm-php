<?php
/**
 * Page Information Entity
 *
 * This is a Doctrine 2 definition file for plugin containers.  This file
 * is used for any module that needs to know container information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace Rcm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
 * @ORM\Table (
 *     name="rcm_containers",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="uq_name_site",
 *             columns={"name", "siteId"}
 *         )
 *     }
 * )
 *
 */
class Container extends ContainerAbstract
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
     * @var \DateTime Date page was first created
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdDate;

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
     * @var \Rcm\Entity\Revision Integer Staged Revision ID
     *
     * @ORM\OneToOne(targetEntity="Revision")
     * @ORM\JoinColumn(name="stagedRevisionId", referencedColumnName="revisionId")
     */
    protected $stagedRevision;

    /**
     * @var \Rcm\Entity\Site
     *
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="containers")
     * @ORM\JoinColumn(name="siteId", referencedColumnName="siteId")
     **/
    protected $site;

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
     * Constructor for Page Entity.  Adds a hydrator to site reference
     */
    public function __construct()
    {
        $this->revisions = new ArrayCollection();
        $this->createdDate = new \DateTime();
    }

    /**
     * Clone the container
     *
     * @return void
     */
    public function __clone()
    {
        if (!$this->containerId) {
            return;
        }

        $this->containerId = null;
        parent::__clone();
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
}
