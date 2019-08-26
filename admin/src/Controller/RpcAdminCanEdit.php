<?php


namespace RcmAdmin\Controller;

use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Acl2\SecurityPropertyConstants;
use Rcm\RequestContext\RequestContext;
use Rcm\Service\CurrentSite;
use Rcm\View\Model\ApiJsonModel;
use RcmUser\Service\RcmUserService;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;

class RpcAdminCanEdit extends ApiAdminBaseController
{
    /**
     * @TODO change client to allow people to go into edit mode but warn them they cannot save
     *
     * @return mixed|JsonModel
     */
    public function create($data)
    {

        $currentSite = $this->getServiceLocator()->get(CurrentSite::class);

        /** @oldControllerAclAccessCheckReplaced */

        /**
         * @var AssertIsAllowed $assertIsAllowed
         */
        $assertIsAllowed = $this->getServiceLocator()->get(RequestContext::class)
            ->get(AssertIsAllowed::class);

        try {
            $assertIsAllowed->__invoke(
                AclActions::UPDATE,
                [
                    'type' => SecurityPropertyConstants::TYPE_CONTENT,
                    'country' => $currentSite->getCountryIso3(),
                    SecurityPropertyConstants::CONTENT_TYPE_PAGE
                ]
            );
            $canEdit = true;
        } catch (NotAllowedException $e) {
            $canEdit = false;
        }

        return new ApiJsonModel(['canEdit' => $canEdit]);
    }
}
