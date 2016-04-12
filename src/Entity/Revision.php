<?php

namespace Rcm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Page Revision Information Entity
 *
 * This object contains a list of page revisions for use with the
 * content management system.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_revisions")
 */
class Revision extends AbstractApiModel
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $revisionId;

    /**
     * @var string Authors name
     *
     * @ORM\Column(type="string")
     */
    protected $author;

    /**
     * @var \DateTime Date revision was created
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdDate;

    /**
     * @var \DateTime Date page was last published
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publishedDate;

    /**
     * @var string Page Layout
     *
     * @ORM\Column(type="boolean")
     */
    protected $published = false;

    /**
     * @var string Md5 of posted data
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $md5;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="PluginWrapper",
     *     fetch="EAGER",
     *     cascade={"persist"}
     * )
     * @ORM\JoinTable(
     *     name="rcm_revisions_plugin_wrappers",
     *     joinColumns={
     *         @ORM\JoinColumn(
     *             name="revisionId",
     *             referencedColumnName="revisionId",
     *             onDelete="CASCADE"
     *         )
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(
     *             name="pluginWrapperId",
     *             referencedColumnName="pluginWrapperId",
     *             onDelete="CASCADE"
     *         )
     *     }
     * )
     * @ORM\OrderBy({"renderOrder" = "ASC"})
     **/
    protected $pluginWrappers;

    /**
     * @var bool
     */
    public $isDirty = false;

    /**
     * @var array
     */
    protected $wrappersSortedByPageContainer = [];

    /**
     * @var array
     */
    protected $wrappersByRows = [];

    /**
     * Constructor for Page Revision Entity.
     */
    public function __construct()
    {
        $this->pluginWrappers = new ArrayCollection();
        $this->createdDate = new \DateTime();
    }

    /**
     * __clone
     *
     * @return void
     */
    public function __clone()
    {
        if (!$this->revisionId) {
            return;
        }

        $this->revisionId = null;
        $this->createdDate = new \DateTime();

        $this->published = false;
        $this->publishedDate = null;

        /* Clone Plugins */
        $pluginWrappers = $this->pluginWrappers;
        $clonedPluginWrappers = new ArrayCollection();

        /** @var \Rcm\Entity\PluginWrapper $pluginWrapper */
        foreach ($pluginWrappers as $pluginWrapper) {
            $clonedPluginWrapper = clone $pluginWrapper;
            $clonedPluginWrappers->add($clonedPluginWrapper);
        }

        $this->pluginWrappers = $clonedPluginWrappers;
    }

    /*   Start Getters and Setters    */

    /**
     * Gets the PageRevId property
     *
     * @return int PageRevId
     *
     */
    public function getRevisionId()
    {
        return $this->revisionId;
    }

    /**
     * @deprecated Do NOT use
     * Set the ID of the Page Revision.  This was added for unit testing and
     * should not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own.
     *
     * @param int $revisionId Unique Page Revision ID
     *
     * @return null
     *
     */
    public function setRevisionId($revisionId)
    {
        $this->revisionId = $revisionId;
    }

    /**
     * Gets the Author property
     *
     * @return string Author ID
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the Author property
     *
     * @param string $author ID for the Author of revision
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
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Sets the CreatedDate property
     *
     * @param \DateTime $createdDate DateTime Object when revision was created
     *
     * @return null
     *
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
     * Gets the Published Date property
     *
     * @return \DateTime LastPublished
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * Sets the Published Date
     *
     * @param \DateTime $publishedDate Date the page was last published.
     *
     * @return null
     */
    public function setPublishedDate(\DateTime $publishedDate)
    {
        $this->publishedDate = $publishedDate;
    }

    /**
     * getPublishedDateString
     *
     * @param string $format
     *
     * @return null|string
     */
    public function getPublishedDateString($format = \DateTime::ISO8601)
    {
        $date = $this->getPublishedDate();

        if (empty($date)) {
            return null;
        }

        return $date->format($format);
    }

    /**
     * Get Plugin Instances - Assumes we have ordered them by RenderOrder the DB join
     *
     * @return ArrayCollection
     */
    public function getPluginWrappers()
    {
        if ($this->pluginWrappers->count() < 1) {
            return [];
        }

        return $this->pluginWrappers;
    }

    /**
     * Get Plugin Instances
     *
     * @return array
     */
    public function getPluginWrappersByRow($refresh = false)
    {
        if ($this->pluginWrappers->count() < 1) {
            return [];
        }

        // Per request caching
        if (!empty($this->wrappersByRows) && !$refresh) {
            return $this->wrappersByRows;
        }

        $this->wrappersByRows = $this->orderPluginWrappersByRow(
            $this->pluginWrappers->toArray()
        );

        return $this->wrappersByRows;
    }

    /**
     * getPluginWrappersByPageContainerName
     *
     * @param string $containerName
     *
     * @return null
     */
    public function getPluginWrappersByPageContainerName($containerName)
    {
        // check cache
        if (!empty($this->wrappersSortedByPageContainer[$containerName])) {
            return $this->wrappersSortedByPageContainer[$containerName];
        }

        // if value was not in cache and cache is not empty, we dont have it, return null
        if (!empty($this->wrappersSortedByPageContainer)) {
            return null;
        }

        $this->wrappersSortedByPageContainer = $this->orderPluginWrappersByContainerName(
            $this->pluginWrappers->toArray()
        );

        if (empty($this->wrappersSortedByPageContainer[$containerName])) {
            return null;
        }

        return $this->wrappersSortedByPageContainer[$containerName];
    }

    /**
     * Get Plugin Instance Wrapper by Instance Id
     *
     * @param integer $instanceId
     *
     * @return ArrayCollection
     */
    public function getPluginWrapper($instanceId)
    {
        if ($this->pluginWrappers->count() < 1) {
            return null;
        }

        /** @var \Rcm\Entity\PluginWrapper $pluginWrapper */
        foreach ($this->pluginWrappers as $pluginWrapper) {
            if ($pluginWrapper->getInstance()->getInstanceId() == $instanceId) {
                return $pluginWrapper;
            }
        }

        return null;
    }

    /**
     * Add a plugin wrapper to the revision
     *
     * @param PluginWrapper $instanceWrapper Plugin Instance to add to revision.
     *
     * @return null
     */
    public function addPluginWrapper(PluginWrapper $instanceWrapper)
    {
        $this->pluginWrappers->add($instanceWrapper);
    }

    /**
     * Remove Plugin Wrapper from Revision
     *
     * @param PluginWrapper $instance Plugin Wrapper to remove
     *
     * @return void
     */
    public function removeInstance(PluginWrapper $instance)
    {
        $this->pluginWrappers->removeElement($instance);
    }

    /**
     * Publish Revision
     *
     * @return void
     */
    public function publishRevision()
    {
        if (empty($this->publishedDate)) {
            $this->publishedDate = new \DateTime();
        }

        $this->published = true;
    }

    /**
     * Check if revision was ever published
     *
     * @return boolean
     */
    public function wasPublished()
    {
        return $this->published;
    }

    /**
     * Set saved MD5 of save data
     *
     * @param string $md5 MD5 of saved data
     *
     * @return void
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;
    }

    /**
     * Get MD5 of saved data
     *
     * @return string
     */
    public function getMd5()
    {
        return $this->md5;
    }

    //////// SORTING ////////
    /**
     * orderPluginWrappersByRow - Assumes we have ordered them by RenderOrder the DB join
     *
     * @param array $pluginWrappers
     *
     * @return array
     */
    public function orderPluginWrappersByRow($pluginWrappers)
    {
        $wrappersByRows = [];

        /** @var \Rcm\Entity\PluginWrapper $wrapper */
        foreach ($pluginWrappers as $wrapper) {
            $rowNumber = $wrapper->getRowNumber();

            if (!isset($wrappersByRows[$rowNumber])) {
                $wrappersByRows[$rowNumber] = [];
            }

            $wrappersByRows[$rowNumber][] = $wrapper;
        }

        ksort($wrappersByRows);

        return $wrappersByRows;
    }

    /**
     * orderPluginWrappersByContainerName
     *
     * @param array $pluginWrappers
     *
     * @return array
     */
    public function orderPluginWrappersByContainerName($pluginWrappers)
    {
        $wrappersSortedByPageContainer = [];

        /** @var \Rcm\Entity\PluginWrapper $wrapper */
        foreach ($pluginWrappers as $wrapper) {
            $containerName = $wrapper->getLayoutContainer();

            $wrappersSortedByPageContainer[$containerName][] = $wrapper;
        }

        foreach ($wrappersSortedByPageContainer as $containerName => $wrapperContainer) {
            $wrappersSortedByPageContainer[$containerName]
                = $this->orderPluginWrappersByRow($wrapperContainer);
        }

        ksort($wrappersSortedByPageContainer);

        return $wrappersSortedByPageContainer;
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return array
     */
    public function toArray($ignore = ['pluginWrappers'])
    {
        $data = parent::toArray($ignore);
        if (!in_array('pluginWrappers', $ignore)) {
            $data['pluginWrappers'] = $this->modelArrayToArray(
                $this->getPluginWrappers()->toArray(),
                []
            );
        }

        if (!in_array('createdDateString', $ignore)) {
            $data['createdDateString'] = $this->getCreatedDateString();
        }

        if (!in_array('publishedDateString', $ignore)) {
            $data['publishedDateString'] = $this->getPublishedDateString();
        }

        return $data;
    }
}
