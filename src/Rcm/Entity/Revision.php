<?php
/**
 * Page Revision Information Entity
 *
 * This is a Doctrine 2 definition file for Page Revisions.  This file
 * is used for any module that needs to know about page revisions.
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
class Revision
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
     **/
    protected $pluginWrappers;

    public $isDirty = false;

    protected $wrappersSortedByPageContainer = array();

    /**
     * Constructor for Page Revision Entity.
     */
    public function __construct()
    {
        $this->pluginWrappers = new ArrayCollection();
        $this->createdDate = new \DateTime();
    }

    public function __clone()
    {
        if (!$this->revisionId) {
            return;
        }

        $this->revisionId = null;
        $this->createdDate = new \DateTime();

        if (!empty($this->publishedDate)) {
            $this->publishedDate = new \DateTime();
        }

        /* Clone Plugins */
        $pluginWrappers = $this->pluginWrappers;
        $clonedPluginWrappers = array();

        /** @var \Rcm\Entity\PluginWrapper $pluginWrapper */
        foreach ($pluginWrappers as $pluginWrapper) {
            $clonedPluginWrapper = clone $pluginWrapper;
            $clonedPluginWrappers[] = $clonedPluginWrapper;
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
     * Get Plugin Instances
     *
     * @return Array
     */
    public function getPluginWrappers()
    {
        if (empty($this->pluginWrappers)) {
            return array();
        }

        $wrappers = array();

        /** @var \Rcm\Entity\PluginWrapper $wrapper */
        foreach($this->pluginWrappers as $wrapper) {
            $orderNumber = $wrapper->getRenderOrderNumber();

            if (!is_int($orderNumber)) {
                $orderNumber = count($wrappers);
            }

            if (!empty($wrappers[$orderNumber])) {
                $orderNumber++;
            }

            $wrappers[$orderNumber] = $wrapper;
        }

        ksort($wrappers);

        return $wrappers;
    }

    public function getPluginWrappersByPageContainerName($containerName)
    {
        if (empty($this->wrappersSortedByPageContainer)) {
            /** @var \Rcm\Entity\PluginWrapper $wrapper */
            foreach($this->pluginWrappers as $wrapper) {

                $renderOrder = $wrapper->getRenderOrderNumber();

                if (!empty($this->wrappersSortedByPageContainer[$wrapper->getLayoutContainer()][$wrapper->getRenderOrderNumber()])) {
                    $renderOrder++;
                }

                $this->wrappersSortedByPageContainer[$wrapper->getLayoutContainer()][$renderOrder] = $wrapper;
            }

            foreach ($this->wrappersSortedByPageContainer as $containerNameKey => $value) {
                ksort($this->wrappersSortedByPageContainer[$containerNameKey]);
            }
        }

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
        if (empty($this->pluginWrappers)) {
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
        $this->pluginWrappers[] = $instanceWrapper;
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
}
