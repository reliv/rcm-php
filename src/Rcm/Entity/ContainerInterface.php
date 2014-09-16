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

use Rcm\Exception\InvalidArgumentException;

/**
 * Container Abstract.  Contains methods shared by container classes
 *
 * Interface for containers.  This class defines shared methods and properties for
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
interface ContainerInterface
{
    /**
     * Clone the container
     *
     * @return void
     */
    public function __clone();

    /**
     * Gets the Name property
     *
     * @return string Name
     *
     */
    public function getName();

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
    public function setName($name);

    /**
     * Gets the Author property
     *
     * @return string Author
     */
    public function getAuthor();

    /**
     * Sets the Author property
     *
     * @param string $author ID of Author.
     *
     * @return null
     */
    public function setAuthor($author);

    /**
     * Gets the CreatedDate property
     *
     * @return \DateTime CreatedDate
     *
     */
    public function getCreatedDate();

    /**
     * Sets the CreatedDate property
     *
     * @param \DateTime $createdDate Date the page was initially created.
     *
     * @return null
     */
    public function setCreatedDate(\DateTime $createdDate);

    /**
     * Gets the LastPublished property
     *
     * @return \DateTime LastPublished
     */
    public function getLastPublished();

    /**
     * Sets the LastPublished property
     *
     * @param \DateTime $lastPublished Date the page was last published.
     *
     * @return null
     */
    public function setLastPublished(\DateTime $lastPublished);

    /**
     * Get Published Revision
     *
     * @return \Rcm\Entity\Revision
     */
    public function getPublishedRevision();

    /**
     * Set the current published revision for the page
     *
     * @param Revision $revision Revision object to add
     *
     * @return null
     */
    public function setPublishedRevision(Revision $revision);

    /**
     * Gets the CurrentRevision property
     *
     * @return Revision CurrentRevision
     */
    public function getCurrentRevision();

    /**
     * Sets the CurrentRevision property
     *
     * @param Revision $currentRevision Revision object to add
     *
     * @return null
     */
    public function setCurrentRevision(Revision $currentRevision);

    /**
     * Remove Current Revision
     *
     * @return void
     */
    public function removeCurrentRevision();

    /**
     * Gets the Current Staged property
     *
     * @return Revision CurrentRevision
     */
    public function getStagedRevision();

    /**
     * Sets the current staged property
     *
     * @param Revision $revision Revision object to add
     *
     * @return null
     */
    public function setStagedRevision(Revision $revision);

    /**
     * Remove Staged Revision
     *
     * @return void
     */
    public function removedStagedRevision();

    /**
     * Get the site that uses this page.
     *
     * @return Site
     */
    public function getSite();

    /**
     * Set site the page belongs to
     *
     * @param Site $site Site object to add
     *
     * @return void
     */
    public function setSite(Site $site);

    /**
     * Set Page Revision
     *
     * @param Revision $revision Revision object to add
     *
     * @return void
     */
    public function addRevision(Revision $revision);

    /**
     * Get the entire revision list
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRevisions();

    /**
     * Overwrite current revisions and Set a group of revisions
     *
     * @param array $revisions Array of Revisions to be added
     *
     * @throws InvalidArgumentException
     */
    public function setRevisions(array $revisions);

    /**
     * Return the last draft saved.
     *
     * @return Revision
     */
    public function getLastSavedDraftRevision();

    /**
     * Get a page revision by ID
     *
     * @param int $revisionId
     *
     * @return null|Revision
     */
    public function getRevisionById($revisionId);

    /**
     * Get array of draft revisions that have not been published
     *
     * @param integer $limit Number of drafts to get
     *
     * @return array
     */
    public function getDraftRevisionList($limit = 0);

    /**
     * Get array of published revisions that have not been published
     *
     * @param integer $limit Number of drafts to get
     *
     * @return array
     */
    public function getPublishedRevisionList($limit = 0);
}
