<?php

namespace Rcm\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\ResourceName;
use Rcm\Entity\Page;
use RcmUser\Api\Acl\IsAllowed;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsPageAllowedForReadingBasic implements IsPageAllowedForReading
{
    protected $resourceName;
    protected $isAllowed;

    /**
     * @param ResourceName $resourceName
     * @param IsAllowed    $isAllowed
     */
    public function __construct(
        ResourceName $resourceName,
        IsAllowed $isAllowed
    ) {
        $this->resourceName = $resourceName;
        $this->isAllowed = $isAllowed;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Page                   $page
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        Page $page
    ):bool {
        $resourceId = $this->resourceName->get(
            ResourceName::RESOURCE_SITES,
            $page->getSiteId(),
            ResourceName::RESOURCE_PAGES,
            $page->getPageType(),
            $page->getName()
        );

        $allowed = $this->isAllowed->__invoke(
            $request,
            $resourceId,
            'read'
        );

        /* ltrim added for BC */
        $currentPage = $page->getName();
        $siteLoginPage = ltrim($page->getSite()->getLoginPage(), '/');
        $notAuthorizedPage = ltrim($page->getSite()->getNotAuthorizedPage(), '/');
        $notFoundPage = ltrim($page->getSite()->getNotFoundPage(), '/');

        if ($siteLoginPage == $currentPage
            || $notAuthorizedPage == $currentPage
            || $notFoundPage == $currentPage
        ) {
            $allowed = true;
        }

        return $allowed;
    }
}
