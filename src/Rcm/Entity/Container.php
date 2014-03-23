<?php
/**
 * Page Information Entity
 *
 * This is a Doctorine 2 definition file for plugin containers.  This file
 * is used for any module that needs to know container information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Page Information Entity
 *
 * This is a Doctorine 2 definition file for plugin containers.  This file
 * is used for any module that needs to know container information.
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_containers")
 */
class Container
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
    protected $currentRevision;

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
     *     indexBy="revisionId"
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

    /**
     * Gets the Name property
     *
     * @return string Name
     *
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the Name property
     *
     * @param string $name Name of Page.  Should be URL friendly and should not
     *                     included spaces.
     *
     * @return null
     *
     * @throws \Rcm\Exception\InvalidArgumentException Exception thrown
     *                                                         if name contains
     *                                                         spaces.
     */
    public function setName($name)
    {
        //Check for spaces.  Throw exception if spaces are found.
        if (strpos($name, ' ')) {
            throw new \Rcm\Exception\InvalidArgumentException(
                'Page Names should not contains spaces.'
            );
        }

        $this->name = $name;
    }

    /**
     * Gets the Author property
     *
     * @return string Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the Author property
     *
     * @param string $author ID of Author.
     *
     * @return null
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Gets the CreatedDate property
     *
     * @return \DateTime CreatedDate
     *
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Sets the CreatedDate property
     *
     * @param \DateTime $createdDate Date the page was initially created.
     *
     * @return null
     */
    public function setCreatedDate(\DateTime $createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Gets the LastPublished property
     *
     * @return \DateTime LastPublished
     */
    public function getLastPublished()
    {
        return $this->lastPublished;
    }

    /**
     * Sets the LastPublished property
     *
     * @param \DateTime $lastPublished Date the page was last published.
     *
     * @return null
     */
    public function setLastPublished(\DateTime $lastPublished)
    {
        $this->lastPublished = $lastPublished;
    }

    /**
     * Get Published Revision
     *
     * @return \Rcm\Entity\Revision
     */
    public function getPublishedRevision()
    {
        return $this->currentRevision;
    }

    /**
     * Set the current published revision for the page
     *
     * @param \Rcm\Entity\Revision $revision Revision object to add
     *
     * @return null
     */
    public function setPublishedRevision(
        Revision $revision
    )
    {
        $revision->publishRevision();
        $this->currentRevision = $revision;
    }

    /**
     * Gets the CurrentRevision property
     *
     * @return \Rcm\Entity\Revision CurrentRevision
     */
    public function getCurrentRevision()
    {
        return $this->getPublishedRevision();
    }

    /**
     * Sets the CurrentRevision property
     *
     * @param \Rcm\Entity\Revision $currentRevision  Revision object to add
     *
     * @return null
     */
    public function setCurrentRevision(
        Revision $currentRevision
    )
    {
        $this->setPublishedRevision($currentRevision);
    }

    public function removeCurrentRevision()
    {
        $this->setStagedRevision($this->currentRevision);
        $this->currentRevision = null;
    }

    /**
     * Gets the Current Staged property
     *
     * @return \Rcm\Entity\Revision CurrentRevision
     */
    public function getStagedRevision()
    {
        return $this->stagedRevision;
    }

    /**
     * Sets the current staged property
     *
     * @param \Rcm\Entity\Revision $revision                Revision object
     *                                                          to add
     *
     * @return null
     */
    public function setStagedRevision(
        Revision $revision
    ) {
        $revision->stageRevision();
        $this->stagedRevision = $revision;
    }

    public function removedStagedRevision()
    {
        $this->stagedRevision = null;
    }

    /**
     * Get the site that uses this page.
     *
     * @return \Rcm\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set site the page belongs to
     *
     * @param \Rcm\Entity\Site $site Site object to add
     *
     * @return void
     */
    public function setSite(
        \Rcm\Entity\Site $site
    )
    {
        $this->site = $site;
    }

    /**
     * Get a list of all page revisions for the site.
     *
     * @return array Array of all page revisions for the page
     */
    public function getRevisions()
    {
        return $this->revisions->toArray();
    }

    public function getRevisionById($revId)
    {
        return $this->revisions[$revId];
    }

    /**
     * Set Page Revision
     *
     * @param \Rcm\Entity\Revision $revision Revision object to add
     *
     * @return void
     */
    public function addRevision(
        Revision $revision
    ) {
        $this->revisions->add($revision);
    }

    /**
     * Get the raw sites property.  Used for unit testing and should not
     * be used by outside scripts
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRawSites()
    {
        return $this->sites;
    }

    /**
     * Get the raw revisions property.  Used for unit testing and should not
     * be used by outside scripts.
     *
     * @return array|\Doctrine\Common\Collections\ArrayCollection
     */
    public function getRawRevisions()
    {
        return $this->revisions;
    }


    /**
     * Return the last draft saved.
     *
     * @return \Rcm\Entity\Revision
     */
    public function getLastSavedRevision()
    {
        $current = $this->currentRevision;
        $staged = $this->stagedRevision;

        /** @var \Rcm\Entity\Revision $revision */
        foreach ($this->revisions as $revision) {

            if (!empty($current)
                && $revision->getRevisionId() == $current->getRevisionId()
            ) {
                continue;
            }

            if (!empty($staged)
                && $revision->getRevisionId() == $staged->getRevisionId()
            ) {
                continue;
            }

            $sorted[$revision->getRevisionId()] = $revision;
        }

        if (empty($sorted)) {
            return null;
        }

        ksort($sorted, SORT_NUMERIC);

        $return = array_pop($sorted);

        return $return;
    }
}