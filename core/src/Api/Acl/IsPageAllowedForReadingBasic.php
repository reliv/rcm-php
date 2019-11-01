<?php

namespace Rcm\Api\Acl;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\GetGroupNamesByUserInterface;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Api\GetPsrRequest;
use Rcm\Entity\Page;
use RcmUser\Api\Acl\IsAllowed;
use RcmUser\Api\Authentication\GetIdentity;

class IsPageAllowedForReadingBasic implements IsPageAllowedForReading
{
    protected $resourceName;
    protected $getGroupNamesByUser;
    protected $getIdentity;
    protected $requestContext;

    /**
     * @param ResourceName $resourceName
     * @param IsAllowed $isAllowed
     */
    public function __construct(
        ResourceName $resourceName,
        GetGroupNamesByUserInterface $getGroupNamesByUser,
        GetIdentity $getIdentity,
        ContainerInterface $requestContext
    ) {
        $this->resourceName = $resourceName;
        $this->getGroupNamesByUser = $getGroupNamesByUser;
        $this->getIdentity = $getIdentity;
        $this->requestContext = $requestContext;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Page $page
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        Page $page
    ): bool {
        /* ltrim added for BC */
        $currentPage = $page->getName();
        $siteLoginPage = ltrim($page->getSite()->getLoginPage(), '/');
        $notAuthorizedPage = ltrim($page->getSite()->getNotAuthorizedPage(), '/');
        $notFoundPage = ltrim($page->getSite()->getNotFoundPage(), '/');

        if ($siteLoginPage == $currentPage
            || $notAuthorizedPage == $currentPage
            || $notFoundPage == $currentPage
        ) {
            return true; //BC Support: These 3 page types apperently always have public read access.
        }

        if ($page->allowsPublicReadAccess() === true) {
            return true;
        }

        return $this->currentUserHasReadAccessToPageAccordingToAclSystem($page);
    }

    protected function currentUserHasReadAccessToPageAccordingToAclSystem($page)
    {
        $pageReadAccessGroups = $page->getReadAccessGroups();
        if ($pageReadAccessGroups === null) {
            $pageReadAccessGroups = []; //fix old DB data where this may be null instead of []
        }
        $currentUser = $this->getIdentity->__invoke(GetPsrRequest::invoke());
        $currentUserGroups = $this->getGroupNamesByUser->__invoke($currentUser);
        $userHasAGroupThatPageAllowsReadAccessTo = false;
        foreach ($pageReadAccessGroups as $pageReadAccessGroup) {
            if (in_array($pageReadAccessGroup, $currentUserGroups)) {
                $userHasAGroupThatPageAllowsReadAccessTo = true;
                break;
            }
        }

        return $userHasAGroupThatPageAllowsReadAccessTo
            || $this->currentUserHasReadAccessToAllPagesBecauseTheyAreAdmin();
    }

    protected function currentUserHasReadAccessToAllPagesBecauseTheyAreAdmin()
    {
        /**
         * @var AssertIsAllowed $assertIsAllowed
         */
        $assertIsAllowed = $this->requestContext->get(AssertIsAllowed::class);

        try {
            //Note: This check ideally should include locale. Maybe we add that in the future if issues happen.
            $assertIsAllowed->__invoke(AclActions::READ, ['type' => 'content']);

            //User is a content admin so give them read access even though the page its self says not to
            return true;
        } catch (NotAllowedException $e) {
            //User is not a content admin and the page says they should not have access.
            return false;
        }
    }
}
