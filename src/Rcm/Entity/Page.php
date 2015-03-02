<?php
/**
 * Page Information Entity
 *
 * This is a Doctrine 2 definition file for Page info.  This file
 * is used for any module that needs to know page information.
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
use Rcm\Exception\InvalidArgumentException;

/**
 * Page Information Entity
 *
 * This object contains a list of pages for use with the content managment
 * system.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity (repositoryClass="Rcm\Repository\Page")
 * @ORM\Table(name="rcm_pages",
 *     indexes={
 *         @ORM\Index(name="page_name", columns={"name"})
 *     }
 * )
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Page extends ContainerAbstract implements ApiInterface
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $pageId;

    /**
     * @var string Page name
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
     * @var string Page Layout
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $pageLayout = null;

    /**
     * @var string Default Site Layout
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $siteLayoutOverride = null;

    /**
     * @var string Page Title
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $pageTitle = 'My Page';

    /**
     * @var string Page Description
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description = 'My Page';

    /**
     * @var string Meta Keywords
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $keywords;

    /**
     * @var Revision Integer Current Page Revision ID
     *
     * @ORM\OneToOne(targetEntity="Revision")
     * @ORM\JoinColumn(name="publishedRevisionId", referencedColumnName="revisionId")
     */
    protected $publishedRevision;

    /**
     * @var Revision Integer Staged Revision ID
     *
     * @ORM\OneToOne(targetEntity="Revision")
     * @ORM\JoinColumn(name="stagedRevisionId", referencedColumnName="revisionId")
     */
    protected $stagedRevision;

    /**
     * @var string Page Type n=Normal, t=Template, z=System
     *
     * @ORM\Column(type="string", length=1)
     */
    protected $pageType = 'n';

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="pages")
     * @ORM\JoinColumn(name="siteId", referencedColumnName="siteId")
     **/
    protected $site;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="Revision",
     *     indexBy="revisionId",
     *     cascade={"persist"}
     * )
     * @ORM\JoinTable(
     *     name="rcm_pages_revisions",
     *     joinColumns={
     *         @ORM\JoinColumn(
     *             name="pageId",
     *             referencedColumnName="pageId",
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
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Page")
     * @ORM\JoinColumn(
     *     name="parentId",
     *     referencedColumnName="pageId",
     *     onDelete="CASCADE",
     *     nullable=true
     * )
     */
    protected $parent;

    /**
     * Constructor for Page Entity.
     */
    public function __construct()
    {
        $this->revisions = new ArrayCollection();
        $this->createdDate = new \DateTime();
    }

    /**
     * Clone the page
     */
    public function __clone()
    {
        if (!$this->pageId) {
            return;
        }

        $this->pageId = null;
        $this->name = null;
        $this->parent = null;
        parent::__clone();
    }

    /**
     * Get the current Page ID
     *
     * @return int Unique page ID number
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Set the ID of the Page.  This was added for unit testing and should
     * not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own.
     *
     * @param int $pageId Unique Page ID
     *
     * @return void
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * Set the type of page
     *
     * @param string $type Type to set
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function setPageType($type)
    {
        if (strlen($type) != 1) {
            throw new InvalidArgumentException(
                'Page type can not exceed 1 character'
            );
        }

        $this->pageType = strtolower($type);
    }

    /**
     * Get type of page.
     *
     * @return string
     */
    public function getPageType()
    {
        return $this->pageType;
    }

    /**
     * Set the Title for the page.
     *
     * @param string $pageTitle Page title
     *
     * @return void
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * Get the title for the page
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Set the Description Meta for the page
     *
     * @param string $description Description Meta for the Page
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get the Description Meta for the page
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the Keyword Meta for the page
     *
     * @param string $keywords Comma Separated List of Keywords
     *
     * @return string
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Get the Keyword Meta for the page
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set the zend page template to use for the page
     *
     * @param string $pageLayout page template to use.
     *
     * @return void
     */
    public function setPageLayout($pageLayout)
    {
        $this->pageLayout = $pageLayout;
    }

    /**
     * Get the zend page template to use for the page
     *
     * @return string
     */
    public function getPageLayout()
    {
        return $this->pageLayout;
    }

    /**
     * Override the sites layout template
     *
     * @param string $layout page template to use.
     *
     * @return void
     */
    public function setSiteLayoutOverride($layout)
    {
        if ($layout === 'default') {
            $layout = null;
        }

        $this->siteLayoutOverride = $layout;
    }

    /**
     * Get the site layout override for this page
     *
     * @return string
     */
    public function getSiteLayoutOverride()
    {
        return $this->siteLayoutOverride;
    }

    /**
     * Set the parent page.  Used to generate breadcrumbs or navigation
     *
     * @param Page $parent Parent Page Entity
     *
     * @return void
     */
    public function setParent(Page $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get the parent page
     *
     * @return Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * populate
     *
     * @param array $data
     *
     * @return void
     */
    public function populate($data)
    {
        if (isset($data['site']) && $data['site'] instanceof Site) {
            $this->setSite($data['site']);
        }

        if (isset($data['name'])) {
            $this->setName($data['name']);
        }

        if (isset($data['pageId'])) {
            $this->setPageId($data['pageId']);
        }

        if (isset($data['pageTitle'])) {
            $this->setPageTitle($data['pageTitle']);
        }

        if (isset($data['pageType'])) {
            $this->setPageType($data['pageType']);
        }

        if (isset($data['description'])) {
            $this->setDescription($data['description']);
        }

        if (isset($data['keywords'])) {
            $this->setKeywords($data['keywords']);
        }

        if (isset($data['author'])) {
            $this->setAuthor($data['author']);
        }

        if (isset($data['lastPublished']) && $data['lastPublished'] instanceof \DateTime) {
            $this->setLastPublished($data['lastPublished']);
        }

        if (isset($data['createdDate']) && $data['createdDate'] instanceof \DateTime) {
            $this->setLastPublished($data['createdDate']);
        }

        if (isset($data['pageLayout'])) {
            $this->setPageLayout($data['pageLayout']);
        }

        if (isset($data['siteLayoutOverride'])) {
            $this->setSiteLayoutOverride($data['siteLayoutOverride']);
        }

        if (isset($data['parent'])) {
            $parent = $data['parent'];
        } else {
            $parent = null;
        }

        if ($parent instanceof Page) {
            $this->setParent($parent);
        }
    }

    /**
     * populateFromObject
     *
     * @param Page|ApiInterface $object
     *
     * @return void
     */
    public function populateFromObject(ApiInterface $object)
    {
        if ($object instanceof Page) {
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
        return $this->toArray();
    }

    /**
     * getIterator
     *
     * @return array|Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
