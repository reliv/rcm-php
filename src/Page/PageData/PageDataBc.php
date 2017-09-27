<?php

namespace Rcm\Page\PageData;

use Rcm\Core\Model\ApiModel;
use Rcm\Core\Model\ApiModelAbstract;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Page\PageStatus\PageStatus;

/**
 * Class PageDataBc
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageDataBc extends ApiModelAbstract implements ApiModel, PageData
{
    /**
     * @BC
     * @var Site|null
     */
    protected $site = null;

    /**
     * @BC
     * @var Page|null
     */
    protected $page = null;

    /**
     * @var int
     */
    protected $siteId;

    /**
     * @var $pageId
     */
    protected $pageId;

    /**
     * @var int
     */
    protected $revisionId;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $theme;

    /**
     * @var int
     */
    protected $httpStatus = PageStatus::STATUS_OK;

    /**
     * @var array
     */
    protected $requestedPage
        = [
            'name' => null,
            'type' => null,
            'revision' => null,
        ];

    /**
     * @var array
     */
    protected $blocks = [];

    /**
     * __get
     *
     * @param $name
     *
     * @return null
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return null;
    }

    /**
     * @BC
     * @return Site|null
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @BC
     *
     * @param Site $site
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
        $this->setSiteId($site->getSiteId());
        $this->setTheme($site->getTheme());
    }

    /**
     * @BC
     * @return Page|null
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @BC
     *
     * @param Page $page
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
        $revision = $page->getCurrentRevision();
        if (empty($revision)) {
            $revision = $page->getPublishedRevision();
        }
        $revisionId = null;
        if (!empty($revision)) {
            $revisionId = $revision->getRevisionId();
        }
        $this->setRevisionId($revisionId);
        $this->setTitle($page->getPageTitle());
        $this->setPageId($page->getPageId());
    }

    /**
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param int $siteId
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * @return mixed
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * @param mixed $pageId
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * @return int
     */
    public function getRevisionId()
    {
        return $this->revisionId;
    }

    /**
     * @param int $revisionId
     */
    public function setRevisionId($revisionId)
    {
        $this->revisionId = $revisionId;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return int
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    /**
     * @param int $httpStatus
     */
    public function setHttpStatus($httpStatus)
    {
        $this->httpStatus = (int)$httpStatus;
    }

    /**
     * @return array
     */
    public function getRequestedPage()
    {
        return $this->requestedPage;
    }

    /**
     * @param array $requestedPage
     */
    public function setRequestedPage(array $requestedPage)
    {
        $this->requestedPage = array_merge($this->requestedPage, $requestedPage);
    }

    /**
     * getRequestedPageName
     *
     * @return string|null
     */
    public function getRequestedPageName()
    {
        if (array_key_exists('name', $this->requestedPage)) {
            return $this->requestedPage['name'];
        }

        return null;
    }

    /**
     * getRequestedPageType
     *
     * @return string|null
     */
    public function getRequestedPageType()
    {
        if (array_key_exists('type', $this->requestedPage)) {
            return $this->requestedPage['type'];
        }

        return null;
    }

    /**
     * getRequestedPageRevision
     *
     * @return string|null
     */
    public function getRequestedPageRevision()
    {
        if (array_key_exists('revision', $this->requestedPage)) {
            return $this->requestedPage['revision'];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param array $blocks
     */
    public function setBlocks(array $blocks)
    {
        $this->blocks = $blocks;
    }
}
