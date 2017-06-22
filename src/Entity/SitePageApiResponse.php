<?php

namespace RcmAdmin\Entity;

use Rcm\Entity\Page;

/**
 * Class SitePageApiResponse
 *
 * SitePage API Response Model
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class SitePageApiResponse extends Page
{
    /**
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->populate(
            $page->toArray(),
            [
                'createdDate',
                'createdByUserId',
                'modifiedByUserId'
            ]
        );

        $this->createdDate = $page->getCreatedDate();
        $this->createdByUserId = $page->getCreatedByUserId();
        $this->createdReason = $page->getCreatedReason();
        $this->modifiedDate = $page->getModifiedDate();
        $this->modifiedByUserId = $page->getModifiedByUserId();
        $this->modifiedReason = $page->getModifiedReason();
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return array
     */
    public function toArray($ignore = [])
    {
        return [
            'siteId' => $this->getSiteId(),
            'pageId' => $this->getPageId(),
            'name' => $this->getName(),
            'author' => $this->getAuthor(),
            'createdDate' => $this->getCreatedDateString(\DateTime::ISO8601),
            'lastPublished' => $this->getLastPublishedString(\DateTime::ISO8601),
            'pageLayout' => $this->getPageLayout(),
            'siteLayoutOverride' => $this->getSiteLayoutOverride(),
            'pageTitle' => $this->getPageTitle(),
            'description' => $this->getDescription(),
            'keywords' => $this->getKeywords(),
            'pageType' => $this->getPageType(),
        ];
    }
}
