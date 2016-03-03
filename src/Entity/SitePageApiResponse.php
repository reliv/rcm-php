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

    public function toArray()
    {
        return [
            'siteId' => $this->getSite()->getSiteId(),
            'pageId' => $this->getPageId(),
            'name' => $this->getName(),
            'author' => $this->getAuthor(),
            'createdDate' => $this->getCreatedDate()->format(\DateTime::ISO8601),
            'lastPublished' => $this->getLastPublished()->format(\DateTime::ISO8601),
            'pageLayout' => $this->getPageLayout(),
            'siteLayoutOverride' => $this->getSiteLayoutOverride(),
            'pageTitle' => $this->getPageTitle(),
            'description' => $this->getDescription(),
            'keywords' => $this->getKeywords(),
            'pageType' => $this->getPageType(),
        ];
    }
}
