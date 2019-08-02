<?php

namespace RcmUser\Api\Acl;

use RcmUser\Acl\Entity\AclResource;
use RcmUser\Acl\Service\AclDataService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetRulesByResourcesBasic implements GetRulesByResources
{
    protected $aclDataService;

    /**
     * @param AclDataService $aclDataService
     */
    public function __construct(
        AclDataService $aclDataService
    ) {
        $this->aclDataService = $aclDataService;
    }

    /**
     * @param array $resources
     *
     * @return AclResource[]
     */
    public function __invoke(
        array $resources
    ): array {
        $result = $this->aclDataService->getRulesByResources($resources);

        return $result->getData();
    }
}
