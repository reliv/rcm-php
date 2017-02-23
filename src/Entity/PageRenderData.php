<?php

namespace Rcm\Entity;

use Rcm\Service\PageStatus;

/**
 * Class PageRenderData
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageRenderData extends AbstractApiModel implements ApiModelInterface
{
    /**
     * @var Site|null
     */
    protected $site = null;

    /**
     * @var Page|null
     */
    protected $page = null;

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
     * @return Site|null
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param Site $site
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
    }

    /**
     * @return Page|null
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param Page $page
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
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
