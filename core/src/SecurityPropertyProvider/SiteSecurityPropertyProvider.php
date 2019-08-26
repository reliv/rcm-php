<?php

namespace Rcm\SecurityPropertyProvider;

use Rcm\Acl\SecurityPropertiesProviderInterface;
use Rcm\Acl2\SecurityPropertyConstants;

class SiteSecurityPropertyProvider implements SecurityPropertiesProviderInterface
{
    public function findSecurityProperties($data): array
    {
        if (!array_key_exists('countryIso3', $data)) {
            throw new NotAllowedBySecurityPropGenerationFailure('countryIso3 not passed.');
        }

        return [
            'type' => SecurityPropertyConstants::TYPE_CONTENT,
            SecurityPropertyConstants::CONTENT_TYPE_KEY => SecurityPropertyConstants::CONTENT_TYPE_SITE,
            'country' => $data['countryIso3']
        ];
    }
}
