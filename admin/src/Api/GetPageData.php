<?php

namespace RcmAdmin\Api;

use Rcm\Entity\Page;
use Rcm\Entity\Site;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetPageData
{
    /**
     * @param Page $page
     * @param Site $site
     * @param array $requestedPageData
     *
     * @return array
     */
    public function __invoke(
        $page,
        $site,
        $requestedPageData
    ) {
        $revisionId = $this->getCurrentRevision($page);
        $siteId = $this->getSiteId($site);
        $siteTitle = $this->getSiteTitle($site);

        return [
            'site' => [
                'id' => $siteId,
                'title' => $siteTitle,
            ],
            'page' => [
                'revision' => $revisionId,
                'type' => $this->getPageType($page),
                'name' => $this->getPageName($page),
                'id' => $this->getPageId($page),
                'title' => $this->getPageTitle($page),
                'keywords' => $this->getKeywords($page),
                'description' => $this->getPageDescription($page),
                'siteId' => $siteId,
                'siteLayoutOverride' => $page->getSiteLayoutOverride()
            ],
            'requestedPage' => $requestedPageData,
        ];
    }

    /**
     * @param Page $page
     *
     * @return null|int
     */
    protected function getCurrentRevision($page)
    {
        if (empty($page)) {
            return null;
        }
        $revision = $page->getCurrentRevision();
        if ($revision instanceof \Rcm\Entity\Revision) {
            return $revision->getRevisionId();
        }

        return null;
    }

    /**
     * @param Site $site
     *
     * @return null|int
     */
    protected function getSiteId($site)
    {
        if (empty($site)) {
            return null;
        }

        return $site->getSiteId();
    }

    /**
     * @param Site $site
     *
     * @return null|string
     */
    protected function getSiteTitle($site)
    {
        if (empty($site)) {
            return null;
        }

        return $site->getSiteTitle();
    }

    /**
     * @param Page $page
     *
     * @return null|string
     */
    protected function getPageType($page)
    {
        if (empty($page)) {
            return null;
        }

        return $page->getPageType();
    }

    /**
     * @param Page $page
     *
     * @return null|string
     */
    protected function getPageName($page)
    {
        if (empty($page)) {
            return null;
        }

        return $page->getName();
    }

    /**
     * @param Page $page
     *
     * @return null|int
     */
    protected function getPageId($page)
    {
        if (empty($page)) {
            return null;
        }

        return $page->getPageId();
    }

    /**
     * @param Page $page
     *
     * @return null|string
     */
    protected function getPageTitle($page)
    {
        if (empty($page)) {
            return null;
        }

        return $page->getPageTitle();
    }

    /**
     * @param Page $page
     *
     * @return null|string
     */
    protected function getKeywords($page)
    {
        if (empty($page)) {
            return null;
        }

        return $page->getKeywords();
    }

    /**
     * @param Page $page
     *
     * @return null|string
     */
    protected function getPageDescription($page)
    {
        if (empty($page)) {
            return null;
        }

        return $page->getDescription();
    }
}
