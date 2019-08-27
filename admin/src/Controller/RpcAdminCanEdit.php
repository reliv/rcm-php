<?php


namespace RcmAdmin\Controller;

use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Acl2\SecurityPropertyConstants;
use Rcm\RequestContext\RequestContext;
use Rcm\SecureRepo\PageSecureRepo;
use Rcm\Service\CurrentSite;
use Rcm\View\Model\ApiJsonModel;
use RcmUser\Service\RcmUserService;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;

class RpcAdminCanEdit extends ApiAdminBaseController
{
    /**
     * @possibleFutureImprovement the client ideally should warn the user they can edit but not save when this
     * this returns ['canEdit' => true, 'canSave' => false]
     *
     * @return mixed|JsonModel
     */
    public function create($data)
    {
        $currentSite = $this->getServiceLocator()->get(CurrentSite::class);

        /** @oldControllerAclAccessCheckReplaced */

        /**
         * @var PageSecureRepo $assertIsAllowed
         */
        $pageSecureRepo = $this->getServiceLocator()->get(RequestContext::class)
            ->get(PageSecureRepo::class);

        $canRead = true;
        $canUpdate = true;
        try {
            $pageSecureRepo->assertIsAllowed(
                AclActions::READ,
                ['siteId' => $currentSite->getSiteId()]
            );
        } catch (NotAllowedException $e) {
            $canRead = false;
        }

        try {
            $pageSecureRepo->assertIsAllowed(
                AclActions::UPDATE,
                ['siteId' => $currentSite->getSiteId()]
            );
        } catch (NotAllowedException $e) {
            $canUpdate = false;
        }

        return new ApiJsonModel(['canEdit' => $canRead, 'canSave' => $canUpdate]);
    }
}
