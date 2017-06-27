<?php

namespace Rcm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Page\PageTypes\PageTypes;
use Rcm\Tracking\Model\Tracking;
use Reliv\RcmApiLib\Model\ApiPopulatableInterface;

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
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="rcm_pages",
 *     indexes={
 *         @ORM\Index(name="page_name", columns={"name"})
 *     }
 * )
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Page extends ContainerAbstract implements ApiModelInterface, \IteratorAggregate, Tracking
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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $publishedRevisionId;

    /**
     * @var Revision Integer Staged Revision ID
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
     * @var string Page Type n=Normal, t=Template, z=System, deleted-{originalPageType}
     *
     * @ORM\Column(type="string", length=32)
     */
    protected $pageType = PageTypes::NORMAL;

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="pages")
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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $parentId;

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
        if (!$this->pageId) {
            return clone($this);
        }
        /** @var static $new */
        $new = parent::newInstance(
            $createdByUserId,
            $createdReason
        );

        $new->pageId = null;
        $new->name = null;
        $new->parent = null;

        return $new;
    }

    /**
     * Gets the Name property
     *
     * @return string Name
     *
     */
    public function getName()
    {
        return strtolower($this->name);
    }

    /**
     * Sets the Name property
     *
     * @param string $name Name of Page.  Should be URL friendly and should not
     *                     included spaces.
     *
     * @return void
     *
     * @throws InvalidArgumentException Exception thrown if name contains spaces.
     */
    public function setName($name)
    {
        $name = strtolower($name);

        //Check for everything except letters and dashes.  Throw exception if any are found.
        if (preg_match("/[^a-z\-0-9\.]/", $name)) {
            throw new InvalidArgumentException(
                'Page names can only contain letters, numbers, dots, and dashes.'
                . ' No spaces. This page name is invalid: ' . $name
            );
        }

        $this->name = $name;
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
     * @deprecated This should not be used
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
        if (strlen($type) > 32) {
            throw new InvalidArgumentException(
                'Page type can not exceed 32 character'
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
     * @return void
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

        $this->parentId = $parent->getPageId();
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
     * getParentId
     *
     * @return int|null
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * populate
     *
     * @param array $data
     * @param array $ignore
     *
     * @return void
     */
    public function populate(array $data, array $ignore = ['createdByUserId', 'createdDate', 'createdReason'])
    {
        if (isset($data['site']) && $data['site'] instanceof Site
            && !in_array(
                'site',
                $ignore
            )
        ) {
            $this->setSite($data['site']);
        }

        if (isset($data['name']) && !in_array('name', $ignore)) {
            $this->setName($data['name']);
        }

        // @bc support
        if (isset($data['pageId']) && !in_array('pageId', $ignore)) {
            $this->setPageId($data['pageId']);
        }

        if (isset($data['pageTitle']) && !in_array('pageTitle', $ignore)) {
            $this->setPageTitle($data['pageTitle']);
        }

        if (isset($data['pageType']) && !in_array('pageType', $ignore)) {
            $this->setPageType($data['pageType']);
        }

        if (isset($data['description']) && !in_array('description', $ignore)) {
            $this->setDescription($data['description']);
        }

        if (isset($data['keywords']) && !in_array('keywords', $ignore)) {
            $this->setKeywords($data['keywords']);
        }

        if (isset($data['author']) && !in_array('author', $ignore)) {
            $this->setAuthor($data['author']);
        }

        if (isset($data['lastPublished'])
            && $data['lastPublished'] instanceof \DateTime
            && !in_array('lastPublished', $ignore)
        ) {
            $this->setLastPublished($data['lastPublished']);
        }

        if (isset($data['createdDate']) && $data['createdDate'] instanceof \DateTime
            && !in_array('createdDate', $ignore)
        ) {
            $this->setLastPublished($data['createdDate']);
        }

        if (isset($data['pageLayout']) && !in_array('pageLayout', $ignore)) {
            $this->setPageLayout($data['pageLayout']);
        }

        if (isset($data['siteLayoutOverride'])
            && !in_array(
                'siteLayoutOverride',
                $ignore
            )
        ) {
            $this->setSiteLayoutOverride($data['siteLayoutOverride']);
        }

        $parent = null;

        if (isset($data['parent']) && !in_array('parent', $ignore)) {
            $parent = $data['parent'];
        }

        if ($parent instanceof Page) {
            $this->setParent($parent);
        }
    }

    /**
     * populateFromObject
     *
     * @param ApiPopulatableInterface $object
     * @param array                   $ignore
     *
     * @return void
     */
    public function populateFromObject(
        ApiPopulatableInterface $object,
        array $ignore = []
    ) {
        if ($object instanceof Page) {
            $this->populate($object->toArray(), $ignore);
        }
    }

    /**
     * jsonSerialize
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray(
            ['parent', 'site', 'revisions']
        );
    }

    /**
     * getIterator
     *
     * @return array|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray(['parent', 'site', 'revisions']));
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return mixed
     */
    public function toArray(
        $ignore
        = [
            'parent',
            'site',
            'revisions',
        ]
    ) {
        $data = parent::toArray($ignore);

        return $data;
    }

    /**
     * <tracking>
     *
     * @return void
     *
     * @ORM\PrePersist
     */
    public function assertHasTrackingData()
    {
        parent::assertHasTrackingData();
    }

    /**
     * <tracking>
     *
     * @return void
     *
     * @ORM\PreUpdate
     */
    public function assertHasNewModifiedData()
    {
        parent::assertHasNewModifiedData();
    }
}
