<?php

namespace Rcm\Acl;

interface SecurityPropertiesProviderInterface
{
    /**
     * @param Object|array $site
     * @return array
     */
    public function findSecurityProperties($site): array;
}
