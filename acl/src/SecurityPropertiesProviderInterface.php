<?php

namespace Rcm\Acl;

interface SecurityPropertiesProviderInterface
{
    /**
     * @param Object|array $resourceData
     * @return array
     */
    public function findSecurityProperties($resourceData): array;
}
