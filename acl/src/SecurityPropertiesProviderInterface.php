<?php

namespace Rcm\Acl;

interface SecurityPropertiesProviderInterface
{
    /**
     * @param Object|array $site
     * @return array
     */
    public function findSecurityProperties($site): array;

    /**
     * @param Object|array $data
     * @return array
     */
    public function findSecurityPropertiesFromCreationData($data): array;
}
