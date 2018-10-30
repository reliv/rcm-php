<?php

namespace Rcm\ImmutableHistory\Site;

class SiteIdToDomainName
{
    public function __invoke(string $siteId): string
    {
        //@TODO implement and return actual user full name and do per-request caching
        return 'DOMAIN_NAME_UNKNOWN';
    }
}
