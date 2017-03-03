<?php

namespace Rcm\Page\PageData;

/**
 * Class PageData
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface PageData
{
    /**
     * getSiteId
     *
     * @return int
     */
    public function getSiteId();

    /**
     * setSiteId
     *
     * @param int $siteId
     *
     * @return void
     */
    public function setSiteId($siteId);

    /**
     * getPageId
     *
     * @return int
     */
    public function getPageId();

    /**
     * setPageId
     *
     * @param int $pageId
     *
     * @return void
     */
    public function setPageId($pageId);

    /**
     * getRevisionId
     *
     * @return int
     */
    public function getRevisionId();

    /**
     * setRevisionId
     *
     * @param int $revisionId
     *
     * @return void
     */
    public function setRevisionId($revisionId);

    /**
     * getTitle
     *
     * @return string
     */
    public function getTitle();

    /**
     * setTitle
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title);

    /**
     * getTheme
     *
     * @return string
     */
    public function getTheme();

    /**
     * setTheme
     *
     * @param string $theme
     *
     * @return void
     */
    public function setTheme($theme);

    /**
     * @return int
     */
    public function getHttpStatus();

    /**
     * @param int $httpStatus
     */
    public function setHttpStatus($httpStatus);

    /**
     * @return array
     */
    public function getRequestedPage();

    /**
     * @param array $requestedPage
     */
    public function setRequestedPage(array $requestedPage);

    /**
     * getRequestedPageName
     *
     * @return string|null
     */
    public function getRequestedPageName();

    /**
     * getRequestedPageType
     *
     * @return string|null
     */
    public function getRequestedPageType();

    /**
     * getRequestedPageRevision
     *
     * @return string|null
     */
    public function getRequestedPageRevision();

    /**
     * @return array
     */
    public function getBlocks();

    /**
     * @param array $blocks
     */
    public function setBlocks(array $blocks);
}
