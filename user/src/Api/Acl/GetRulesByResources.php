<?php

namespace RcmUser\Api\Acl;

use RcmUser\Acl\Entity\AclResource;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetRulesByResources
{
    /**
     * @param array $resources
     *
     * @return AclResource[]
     */
    public function __invoke(
        array $resources
    ): array;
}
