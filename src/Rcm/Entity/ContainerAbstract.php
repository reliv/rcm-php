<?php
/**
 * Container Abstract.  Contains methods shared by container classes
 *
 * Abstract for containers.  This file is used by container objects to keep code DRY
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
use Rcm\Exception\InvalidArgumentException;

/**
 * Container Abstract.  Contains methods shared by container classes
 *
 * Abstract for containers.  This class defines shared methods and properties for
 * container classes.  Please note that if using doctrine the properties need to
 * still be defined by the actual class as well.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
abstract class ContainerAbstract
{
    /**
     * @var string Container name
     */
    protected $name;

    /**
     * @var string Authors name
     */
    protected $author;

    /**
     * @var \DateTime Date page was first created
     */
    protected $createdDate;

    /**
     * @var \DateTime Date page was last published
     */
    protected $lastPublished;

    /**
     * @var \Rcm\Entity\Revision Integer Current Page Revision ID
     */
    protected $currentRevision;

    /**
     * @var \Rcm\Entity\Revision Integer Staged Revision ID
     */
    protected $stagedRevision;

    /**
     * @var \Rcm\Entity\Site
     **/
    protected $site;

    /**
     * @var array|\Doctrine\Common\Collections\ArrayCollection
     */
    protected $revisions;

    /**
     * Clone the container
     *
     * @return void
     */
    public function __clone()
    {
        $this->createdDate = new \DateTime();
        $this->lastPublished = new \DateTime();

        $this->revisions = new ArrayCollection();

        if (!empty($this->currentRevision)) {
            $revision = clone $this->currentRevision;
            $this->currentRevision = $revision;
            $this->revisions[] = $revision;
            $this->stagedRevision = null;
        }
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
     * @throws InvalidArgumentException Exception thrown if name contains spaces.
     */
    public function setName($name)
    {
        //Check for spaces.  Throw exception if spaces are found.
        if (strpos($name, ' ')) {
            throw new InvalidArgumentException(
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
     * @param Revision $revision Revision object to add
     *
     * @return null
     */
    public function setPublishedRevision(Revision $revision)
    {
        if (!empty($this->stagedRevision)
            && $this->stagedRevision->getRevisionId() == $revision->getRevisionId()
        ) {
            $this->stagedRevision = null;
        }

        $revision->publishRevision();
        $this->currentRevision = $revision;
    }

    /**
     * Gets the CurrentRevision property
     *
     * @return Revision CurrentRevision
     */
    public function getCurrentRevision()
    {
        return $this->getPublishedRevision();
    }

    /**
     * Sets the CurrentRevision property
     *
     * @param Revision $currentRevision Revision object to add
     *
     * @return null
     */
    public function setCurrentRevision(Revision $currentRevision)
    {
        $this->setPublishedRevision($currentRevision);
    }

    /**
     * Remove Current Revision
     *
     * @return void
     */
    public function removeCurrentRevision()
    {
        $this->setStagedRevision($this->currentRevision);
    }

    /**
     * Gets the Current Staged property
     *
     * @return Revision CurrentRevision
     */
    public function getStagedRevision()
    {
        return $this->stagedRevision;
    }

    /**
     * Sets the current staged property
     *
     * @param Revision $revision Revision object to add
     *
     * @return null
     */
    public function setStagedRevision(Revision $revision)
    {
        if (!empty($this->currentRevision)
            && $this->currentRevision->getRevisionId() == $revision->getRevisionId()
        ) {
            $this->currentRevision = null;
        }

        $this->stagedRevision = $revision;
    }

    /**
     * Remove Staged Revision
     *
     * @return void
     */
    public function removedStagedRevision()
    {
        $this->stagedRevision = null;
    }

    /**
     * Get the site that uses this page.
     *
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set site the page belongs to
     *
     * @param Site $site Site object to add
     *
     * @return void
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
    }

    /**
     * Set Page Revision
     *
     * @param Revision $revision Revision object to add
     *
     * @return void
     */
    public function addRevision(Revision $revision)
    {
        $this->revisions->set($revision->getRevisionId(), $revision);
    }

    /**
     * Get the entire revision list
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRevisions()
    {
        return $this->revisions;
    }

    /**
     * Overwrite current revisions and Set a group of revisions
     *
     * @param array $revisions Array of Revisions to be added
     *
     * @throws InvalidArgumentException
     */
    public function setRevisions(array $revisions)
    {
        $this->revisions = new ArrayCollection();

        /** @var \Rcm\Entity\Revision $revision */
        foreach ($revisions as $revision) {
            if (!$revision instanceof Revision) {
                throw new InvalidArgumentException(
                    "Invalid Revision passed in.  Unable to set array"
                );
            }

            $this->revisions->set($revision->getRevisionId(), $revision);
        }
    }

    /**
     * Return the last draft saved.
     *
     * @return Revision
     */
    public function getLastSavedDraftRevision()
    {
        $current = $this->currentRevision;
        $staged = $this->stagedRevision;

        /** @var \Rcm\Entity\Revision $revision */
        foreach ($this->revisions as &$revision) {

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

            if ($revision->wasPublished()) {
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

    /**
     * Get a page revision by ID
     *
     * @param int $revisionId
     *
     * @return null|Revision
     */
    public function getRevisionById($revisionId)
    {
        /** @var \Rcm\Entity\Revision $revision */
        foreach ($this->revisions as $revision) {
            if ($revision->getRevisionId() == $revisionId) {
                return $revision;
            }
        }

        return null;
    }

    /**
     * Get array of draft revisions that have not been published
     *
     * @param integer $limit Number of drafts to get
     *
     * @return array
     */
    public function getDraftRevisionList($limit = 0)
    {
        $count = 0;

        $return = array();

        /** @var \Rcm\Entity\Revision $revision */
        foreach (array_reverse($this->revisions->toArray()) as $revision) {

            if ($limit > 0 && $count >= $limit) {
                break;
            }

            if (!$revision->wasPublished()) {
                $return[] = $revision;
                $count++;
            }
        }

        return $return;
    }

    /**
     * Get array of published revisions that have not been published
     *
     * @param integer $limit Number of drafts to get
     *
     * @return array
     */
    public function getPublishedRevisionList($limit = 0)
    {
        $count = 0;

        $return = array();

        /** @var \Rcm\Entity\Revision $revision */
        foreach (array_reverse($this->revisions->toArray()) as $revision) {

            if ($limit > 0 && $count >= $limit) {
                break;
            }

            if ($revision->wasPublished()
                && $revision != $this->currentRevision
                && $revision != $this->stagedRevision
            ) {
                $return[] = $revision;
                $count++;
            }
        }

        return $return;
    }
}
