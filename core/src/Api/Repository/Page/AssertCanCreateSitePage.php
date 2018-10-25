<?php

namespace Rcm\Api\Repository\Page;

use Rcm\Api\Repository\Site\FindSite;
use Rcm\Exception\PageException;
use Rcm\Exception\SiteNotFoundException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AssertCanCreateSitePage
{
    protected $allowDuplicateForPageType;
    protected $findSite;
    protected $pageExists;

    /**
     * @param AllowDuplicateForPageType $allowDuplicateForPageType
     * @param FindSite                  $findSite
     * @param PageExists                $pageExists
     */
    public function __construct(
        AllowDuplicateForPageType $allowDuplicateForPageType,
        FindSite $findSite,
        PageExists $pageExists
    ) {
        $this->allowDuplicateForPageType = $allowDuplicateForPageType;
        $this->findSite = $findSite;
        $this->pageExists = $pageExists;
    }

    /**
     * @param int|string $siteId
     * @param string     $pageName
     * @param string     $pageType
     *
     * @return void
     * @throws PageException
     */
    public function __invoke(
        $siteId,
        $pageName,
        $pageType
    ) {
        $allowDuplicates = $this->allowDuplicateForPageType->__invoke($pageType);

        if ($allowDuplicates) {
            return;
        }

        $site = $this->findSite->__invoke(
            $siteId
        );

        if (empty($site)) {
            throw new SiteNotFoundException(
                'Site not found: ' . $siteId
            );
        }

        $pageExists = $this->pageExists->__invoke(
            $siteId,
            $pageName,
            $pageType
        );

        if (!$pageExists) {
            return;
        }

        throw new PageException(
            "Cannot create page: ({$pageName}). " .
            "Duplicate page names not allowed for name: ({$pageName}) type: ({$pageType}) siteId: " .
            "({$siteId})"
        );
    }
}
