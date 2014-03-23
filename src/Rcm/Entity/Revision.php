<?php
/**
 * Page Revision Information Entity
 *
 * This is a Doctorine 2 definition file for Page Revisions.  This file
 * is used for any module that needs to know about page revisions.
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
 * Page Revision Information Entity
 *
 * This object contains a list of page revisions for use with the
 * content managment system.
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
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
     * @var string Page Layout
     *
     * @ORM\Column(type="boolean")
     */
    protected $staged = false;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="PluginWrapper",
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
    protected $pluginInstances;

    protected $isDirty = false;

    /**
     * Constructor for Page Revision Entity.
     */
    public function __construct()
    {
        $this->pluginInstances = new ArrayCollection();
    }


    public function __clone()
    {
        if ($this->revisionId) {
            $this->revisionId = null;

            $currentInstance = $this->getRawPluginInstances();
            $clonedInstances = array();

            /** @var \Rcm\Entity\PluginWrapper $pluginWrapper */
            foreach ($currentInstance as $pluginWrapper) {
                $clonedInstances[] = clone $pluginWrapper;
            }

            $this->pluginInstances = new ArrayCollection($clonedInstances);
        }
    }

    /**
     * Function to return an array representation of the object.
     *
     * @return array Array representation of the object
     */
    public function toArray()
    {
        $return = array(
            'revisionId' => $this->getRevisionId(),
            'author' => $this->getAuthor(),
            'createdDate' => $this->getCreatedDate(),
            'page' => $this->getPage(),
            'pageTitle' => $this->getPageTitle(),
            'description' => $this->getDescription(),
            'keywords' => $this->getKeywords(),
            'pageLayout' => $this->getPageLayout(),
            'pluginInstances' => $this->getPluginInstances()
        );

        return $return;
    }

    /**
     * Get a sorted list of instances
     *
     * @return null|array Sorted Plugin Instances
     */
    public function getInstancesForDisplay()
    {
        if ($this->pluginInstances->count() < 1) {
            return null;
        }

        /** @var \Rcm\Entity\PluginWrapper $instance */
        foreach ($this->pluginInstances->toArray() as $instance) {

            $container = $instance->getLayoutContainer();
            $position = $instance->getRenderOrderNumber();

            $sorted[$container][$position] = $instance;
        }

        ksort($sorted);

        $keysToSort = array_keys($sorted);

        foreach ($keysToSort as $container) {
            ksort($sorted[$container]);
        }

        return $sorted;

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
     * with Doctrine and allow Doctrine to set this on it's own,
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
     * Get the page entity the revision belongs to.
     *
     * @return \Rcm\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Add a page to the revision.
     *
     * @param \Rcm\Entity\Page $page Page to add revision to.
     *
     * @return void
     */
    public function setPage(\Rcm\Entity\Page $page)
    {
        $this->page = $page;
    }

    /**
     * Gets the PageTitle property
     *
     * @return string PageTitle
     *
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Sets the Meta Tag Title property
     *
     * @param string $pageTitle Meta Title for the page revision.
     *
     * @return null
     *
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * Gets the Meta Tag Description of the page
     *
     * @return string Description
     *
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the Meta Tag Description of the page.
     *
     * @param string $description Description to use for the Meta Description
     *
     * @return null
     *
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Gets the Meta Keywords property
     *
     * @return string Keywords
     *
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Sets the Meta Keywords property
     *
     * @param string $keywords Meta Keywords for page revision
     *
     * @return null
     *
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Gets the PageLayout name for revision.  This should be set via config.
     * More info can be found in the documentation for the Base Controller, or
     * in the application docs.
     *
     * @return string PageLayout
     *
     */
    public function getPageLayout()
    {
        return $this->pageLayout;
    }

    /**
     * Sets the PageLayout property.  This should be set using the key set
     * within the application config. More info can be found in the
     * documentation for the Base Controller, or in the application docs.
     *
     * @param string $pageLayout Layout Name to use for revision
     *
     * @return null
     *
     */
    public function setPageLayout($pageLayout)
    {
        $this->pageLayout = $pageLayout;
    }

    /**
     * Get Plugin Instances
     *
     * @return array
     */
    public function getPluginInstances()
    {
        return $this->pluginInstances->toArray();
    }

    /**
     * Add a plugin instance or a list of plugin instances to revision
     *
     * @param mixed $instances Plugin Instance or Array of plugin instances to
     *                         add
     *
     * @return null
     *
     * @throws \Rcm\Exception\RuntimeException
     */
    public function addInstances($instances)
    {
        if (!is_array($instances)
            && is_a($instances, '\Rcm\Entity\PluginWrapper')
        ) {
            $this->addInstance($instances);
            return;
        } elseif (!is_array($instances)
            && !is_a($instances, '\Rcm\Entity\PluginWrapper')
        ) {
            throw new \Rcm\Exception\RuntimeException(
                'Passed instance must be an array or a single '
                . 'of \Rcm\Entity\PluginWrapper'
            );
        }

        foreach ($instances as $instance) {
            $this->addInstance($instance);
        }
    }

    /**
     * Add instance to Entity
     *
     * @param \Rcm\Entity\PluginWrapper $instance     Plugin Instance to
     *                                                     add to revision.
     *
     * @return null
     */
    public function addInstance(PluginWrapper $instance)
    {
        $this->pluginInstances[] = $instance;
    }

    public function getInstanceById($instanceId)
    {
        /** @var \Rcm\Entity\PluginWrapper $instance */
        foreach ($this->pluginInstances as $instance) {
            if ($instance->getInstanceId() == $instanceId) {
                return $instance;
            }
        }

        return false;

    }

    /**
     * Get Raw Plugin Instances.  Use only for unit tests
     *
     * @return \Doctrine\Common\Collections\ArrayCollection Doctrine Array
     *                                                      Collection.
     */
    public function getRawPluginInstances()
    {
        return $this->pluginInstances;
    }

    public function removeInstance(
        PluginWrapper $instance
    )
    {
        $this->pluginInstances->removeElement($instance);
    }

    public function publishRevision()
    {
        $this->staged = false;
        $this->published = true;
    }

    public function wasPublished()
    {
        return $this->published;
    }

    public function stageRevision()
    {
        $this->staged = true;
    }

    public function unStageRevision()
    {
        $this->staged = false;
    }

    public function isStaged()
    {
        return $this->staged;
    }

    public function setIsDirty($isDirty)
    {
        $this->isDirty = $isDirty;
    }

    public function getIsDirty()
    {
        return $this->isDirty;
    }

    /**
     * @param string $md5
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;
    }

    /**
     * @return string
     */
    public function getMd5()
    {
        return $this->md5;
    }

    public function clearPluginInstances()
    {
        $this->pluginInstances = new ArrayCollection();
    }
}