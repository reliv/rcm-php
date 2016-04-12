<?php

namespace Rcm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Rcm\Exception\InvalidArgumentException;
use Reliv\RcmApiLib\Model\ApiPopulatableInterface;
use Reliv\RcmApiLib\Model\ApiSerializableInterface;

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
abstract class ContainerAbstract implements ContainerInterface
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
     * @var \Rcm\Entity\Revision Integer published Page Revision
     */
    protected $publishedRevision;

    /**
     * @var int
     */
    protected $publishedRevisionId;

    /**
     * @var \Rcm\Entity\Revision Integer Staged Revision
     */
    protected $stagedRevision;

    /**
     * @var int
     */
    protected $stagedRevisionId;

    /**
     * @var \Rcm\Entity\Site
     **/
    protected $site;

    /**
     * @var int
     */
    protected $siteId;

    /**
     * @var array|\Doctrine\Common\Collections\ArrayCollection
     */
    protected $revisions;

    /**
     * @var Revision Used to store the current displayed revision
     */
    protected $currentRevision;

    /**
     * @var Revision Place Holder for last saved draft
     */
    protected $lastSavedDraft;

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

        if (!empty($this->publishedRevision)) {
            $revision = clone $this->publishedRevision;
            $this->removePublishedRevision();
            $this->revisions->add($revision);
            $this->setStagedRevision($revision);
        } elseif (!empty($this->stagedRevision)) {
            $revision = clone $this->stagedRevision;
            $this->setStagedRevision($revision);
            $this->revisions->add($revision);
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
                'Container Names should not contain spaces.'
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
     * getCreatedDateString
     *
     * @param string $format
     *
     * @return null|string
     */
    public function getCreatedDateString($format = \DateTime::ISO8601)
    {
        $date = $this->getCreatedDate();

        if (empty($date)) {
            return null;
        }

        return $date->format($format);
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
     * getLastPublishedString
     *
     * @param string $format
     *
     * @return null|string
     */
    public function getLastPublishedString($format = \DateTime::ISO8601)
    {
        $date = $this->getLastPublished();

        if (empty($date)) {
            return null;
        }

        return $date->format($format);
    }

    /**
     * Get Published Revision
     *
     * @return \Rcm\Entity\Revision
     */
    public function getPublishedRevision()
    {
        return $this->publishedRevision;
    }

    /**
     * Set the published published revision for the page
     *
     * @param Revision $revision Revision object to add
     *
     * @return null
     */
    public function setPublishedRevision(Revision $revision)
    {
        if (!empty($this->stagedRevision)) {
            $this->removeStagedRevision();
        }

        $revision->publishRevision();
        $this->publishedRevision = $revision;
        $this->publishedRevisionId = $revision->getRevisionId();
        $this->setLastPublished(new \DateTime());
    }

    /**
     * getPublishedRevisionId
     *
     * @return int
     */
    public function getPublishedRevisionId()
    {
        return $this->publishedRevisionId;
    }

    /**
     * Gets the Staged revision
     *
     * @return Revision Staged Revision
     */
    public function getStagedRevision()
    {
        return $this->stagedRevision;
    }

    /**
     * Sets the staged revision
     *
     * @param Revision $revision Revision object to add
     *
     * @return null
     */
    public function setStagedRevision(Revision $revision)
    {
        if (!empty($this->publishedRevision)
            && $this->publishedRevision->getRevisionId() == $revision->getRevisionId(
            )
        ) {
            $this->removePublishedRevision();
        }

        $this->stagedRevision = $revision;
        $this->stagedRevisionId = $revision->getRevisionId();
    }

    /**
     * getStagedRevisionId
     *
     * @return mixed
     */
    public function getStagedRevisionId()
    {
        return $this->stagedRevisionId;
    }

    /**
     * Remove Published Revision
     */
    public function removePublishedRevision()
    {
        $this->publishedRevision = null;
        $this->publishedRevisionId = null;
    }

    /**
     * Remove Staged Revision
     *
     * @return void
     */
    public function removeStagedRevision()
    {
        $this->stagedRevision = null;
        $this->stagedRevisionId = null;
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
        $this->siteId = $site->getSiteId();
    }

    /**
     * getSiteId
     *
     * @return int|null
     */
    public function getSiteId()
    {
        return $this->siteId;
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
     * Overwrite revisions and Set a group of revisions
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
        if (!empty($this->lastSavedDraft)) {
            return $this->lastSavedDraft;
        }

        $published = $this->publishedRevision;
        $staged = $this->stagedRevision;

        $arrayCollection = $this->revisions->toArray();

        /** @var \Rcm\Entity\Revision $revision */
        $revision = end($arrayCollection);

        if (empty($revision)) {
            return null;
        }

        $found = false;

        while (!$found) {
            if (empty($revision)) {
                break;
            } elseif (!empty($published)
                && $published->getRevisionId() == $revision->getRevisionId()
            ) {
                $found = false;
            } elseif (!empty($staged)
                && $staged->getRevisionId() == $revision->getRevisionId()
            ) {
                $found = false;
            } elseif ($revision->wasPublished()) {
                $found = false;
            } else {
                $found = true;
            }

            if (!$found) {
                $revision = prev($arrayCollection);
            }
        }

        return $this->lastSavedDraft = $revision;
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
        return $this->revisions->get($revisionId);
    }

    /**
     * getCurrentRevision
     *
     * @return Revision
     */
    public function getCurrentRevision()
    {
        return $this->currentRevision;
    }

    /**
     * setCurrentRevision
     *
     * @param $currentRevision
     *
     * @return void
     */
    public function setCurrentRevision($currentRevision)
    {
        $this->currentRevision = $currentRevision;
    }

    /**
     * populate
     *
     * @param array $data
     * @param array $ignore List of properties to skip population for
     *
     * @return mixed
     */
    public function populate(
        array $data,
        array $ignore = []
    ) {
        $prefix = 'set';

        foreach ($data as $property => $value) {
            // Check for ignore keys
            if (in_array($property, $ignore)) {
                continue;
            }

            $method = $prefix . ucfirst($property);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * populateFromObject
     *
     * @param ApiPopulatableInterface $object Object of THIS type
     * @param array                   $ignore List of properties to skip population for
     *
     * @return mixed
     */
    public function populateFromObject(
        ApiPopulatableInterface $object,
        array $ignore = []
    ) {
        if ($object instanceof ContainerInterface) {
            $this->populate($object->toArray());
        }
    }

    /**
     * jsonSerialize
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray(['revisions', 'site']);
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return mixed
     */
    public function toArray($ignore = ['revisions', 'site'])
    {
        $prefix = 'get';
        $properties = get_object_vars($this);
        $data = [];

        foreach ($properties as $property => $value) {
            // Check for ignore keys
            if (in_array($property, $ignore)) {
                continue;
            }

            $method = $prefix . ucfirst($property);

            if (method_exists($this, $method)) {
                $data[$property] = $this->$method();
            }
        }

        if (!in_array('revisions', $ignore)) {
            $data['revisions'] = $this->modelArrayToArray(
                $this->getRevisions()->toArray(),
                []
            );
        }

        if (!in_array('siteId', $ignore)) {
            $data['siteId'] = $this->getSiteId();
        }

        if (!in_array('createdDateString', $ignore)) {
            $data['createdDateString'] = $this->getCreatedDateString();
        }

        if (!in_array('lastPublishedString', $ignore)) {
            $data['lastPublishedString'] = $this->getLastPublishedString();
        }
        
        return $data;
    }

    /**
     * modelArrayToArray
     *
     * @param array $modelArray
     * @param array $ignore
     *
     * @return array
     */
    protected function modelArrayToArray($modelArray, $ignore = [])
    {
        $array = [];

        /** @var ApiSerializableInterface $item */
        foreach ($modelArray as $item) {
            $array[] = $item->toArray($ignore);
        }

        return $array;
    }
}
