<?php

namespace Rcm\Api;

use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetSiteCountryIso3ByRequestFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetSiteCountryIso3ByRequest
     */
    public function __invoke($serviceContainer)
    {
        return new GetSiteCountryIso3ByRequest(
            $serviceContainer->get(GetSiteByRequest::class)
        );
    }
}
