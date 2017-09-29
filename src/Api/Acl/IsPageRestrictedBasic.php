<?php

namespace Rcm\Api\Acl;

use Rcm\Acl\ResourceName;
use RcmUser\Acl\Service\AclDataService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsPageRestrictedBasic implements IsPageRestricted
{
    protected $resourceName;
    protected $aclDataService;

    /**
     * @param ResourceName   $resourceName
     * @param AclDataService $aclDataService
     */
    public function __construct(
        ResourceName $resourceName,
        AclDataService $aclDataService
    ) {
        $this->resourceName = $resourceName;
        $this->aclDataService = $aclDataService;
    }

    /**
     * @param int|string  $siteId
     * @param string      $pageType
     * @param string      $pageName
     * @param string|null $privilege
     *
     * @return bool
     */
    public function __invoke(
        $siteId,
        string $pageType,
        string $pageName,
        $privilege
    ):bool {
        $resourceId = $this->resourceName->get(
            ResourceName::RESOURCE_SITES,
            $siteId,
            ResourceName::RESOURCE_PAGES,
            $pageType,
            $pageName
        );

        //getting all set rules by resource Id
        $rules = $this->aclDataService->getRulesByResourcePrivilege(
            $resourceId,
            $privilege
        )->getData();

        if (empty($rules)) {
            return false;
        }

        return true;
    }
}
