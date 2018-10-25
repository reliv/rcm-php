<?php

namespace Rcm\Api;

use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetSiteIdByRequestFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetSiteIdByRequest
     */
    public function __invoke($serviceContainer)
    {
        return new GetSiteIdByRequest(
            $serviceContainer->get(GetSiteByRequest::class)
        );
    }
}
