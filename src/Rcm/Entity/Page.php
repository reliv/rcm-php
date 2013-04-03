<?php
/**
 * Page Information Entity
 *
 * This is a Doctorine 2 definition file for Page info.  This file
 * is used for any module that needs to know page information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace Rcm\Entity;

use Zend\XmlRpc\Value\Integer;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Page Information Entity
 *
 * This object contains a list of pages for use with the content managment
 * system.
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_pages")
 */

class Page
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
     * @var \Rcm\Entity\PageRevision Integer Current Page Revision ID
     *
     * @ORM\OneToOne(targetEntity="PageRevision")
     * @ORM\JoinColumn(name="pub_id", referencedColumnName="pageRevId")
     */
    protected $currentRevision;

    /**
     * @var \Rcm\Entity\PageRevision Integer Staged Revision ID
     *
     * @ORM\OneToOne(targetEntity="PageRevision")
     * @ORM\JoinColumn(name="stage_id", referencedColumnName="pageRevId")
     */
    protected $stagedRevision;

    /**
     * @var string Page Type n=Normal, p=Product, b=Blog, t=Template, z=System (do not use)
     *
     * @ORM\Column(type="string")
     */
    protected $pageType='n';

    /**
     * @var \Rcm\Entity\Site
     *
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="pages")
     * @ORM\JoinColumn(name="siteId", referencedColumnName="siteId")
     **/
    protected $site;
    
    /**
     * @var array Array of page revisions
     *
     * @ORM\OneToMany(
     *     targetEntity="PageRevision",
     *     mappedBy="page",
     *     indexBy="pageRevId",
     *     cascade={"persist", "remove"}
     * )
     */
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
     * Function to return an array representation of the object.
     *
     * @return array Array representation of the object
     */
    public function toArray()
    {
        $return = array(
            'pageId'          => $this->getPageId(),
            'name'            => $this->getName(),
            'author'          => $this->getAuthor(),
            'createdDate'     => $this->createdDate,
            'lastPublished'   => $this->getLastPublished(),
            'currentRevision' => $this->getCurrentRevision(),
            'site'           => $this->getSite(),
            'revisions'       => $this->getRevisions(),
        );

        return $return;
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
     * with Doctrine and allow Doctrine to set this on it's own,
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
     * @return \Rcm\Entity\PageRevision
     */
    public function getPublishedRevision()
    {
        return $this->currentRevision;
    }

    /**
     * Set the current published revision for the page
     *
     * @param \Rcm\Entity\PageRevision $revision Revision object to add
     *
     * @return null
     */
    public function setPublishedRevision (
        \Rcm\Entity\PageRevision $revision
    ) {
        $revision->publishRevision();
        $this->currentRevision = $revision;
    }

    /**
     * Gets the CurrentRevision property
     *
     * @return \Rcm\Entity\PageRevision CurrentRevision
     */
    public function getCurrentRevision()
    {
        return $this->getPublishedRevision();
    }

    /**
     * Sets the CurrentRevision property
     *
     * @param \Rcm\Entity\PageRevision $currentRevision Revision object
     *                                                          to add
     *
     * @return null
     */
    public function setCurrentRevision(
        \Rcm\Entity\PageRevision $currentRevision
    ) {
        $this->setPublishedRevision($currentRevision);
    }

    /**
     * Gets the Current Staged property
     *
     * @return \Rcm\Entity\PageRevision CurrentRevision
     */
    public function getStagedRevision()
    {
        return $this->stagedRevision;
    }

    /**
     * Sets the current staged property
     *
     * @param \Rcm\Entity\PageRevision $revision Revision object
     *                                                          to add
     *
     * @return null
     */
    public function setStagedRevision(
        \Rcm\Entity\PageRevision $revision
    ) {
        $revision->stageRevision();
        $this->stagedRevision = $revision;
    }

    public function removedStagedRevistion() {
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
    ) {
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

    public function getRevisionById($revId) {
        return $this->revisions[$revId];
    }

    /**
     * Set Page Revision
     *
     * @param \Rcm\Entity\PageRevision $revision Revision object to add
     *
     * @return void
     */
    public function addPageRevision (
        \Rcm\Entity\PageRevision $revision
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

    public function setTemplate()
    {
        $this->setPageType('t');
    }

    /**
     * @return boolean
     */
    public function isTemplate()
    {
        return $this->checkPageType('t');
    }

    public function setProductPage() {
        $this->setPageType('p');
    }

    public function isProductPage() {
        return $this->checkPageType('p');
    }


    public function checkPageType($pageType) {
        if ($this->pageType == $pageType) {
            return true;
        }

        return false;
    }

    public function setPageType($type) {
        if ($type != 'n'
            && $type != 'p'
            && $type != 'b'
            && $type != 't'
            && $type != 'z'
        ) {
            throw new \Exception('Invalid Product Type Passed');
        }

        $this->pageType = $type;
    }

    public function getPageType() {
        return $this->pageType;
    }

    /**
     * Return the last draft saved.
     *
     * @return \Rcm\Entity\PageRevision
     */
    public function getLastSavedRevision()
    {
        $current = $this->currentRevision;
        $staged = $this->stagedRevision;

        /** @var \Rcm\Entity\PageRevision $revision */
        foreach ($this->revisions as $revision) {

            if (!empty($current)
                && $revision->getPageRevId() == $current->getPageRevId()
            ) {
                continue;
            }

            if ( !empty($staged)
                && $revision->getPageRevId() == $staged->getPageRevId()
            ) {
                continue;
            }

            $sorted[$revision->getPageRevId()] = $revision;
        }

        if (empty($sorted)) {
            return null;
        }

        ksort($sorted, SORT_NUMERIC);

        $return = array_pop($sorted);

        return $return;

    }
}