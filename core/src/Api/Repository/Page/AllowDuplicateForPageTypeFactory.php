<?php

namespace Rcm\Api\Repository\Page;

use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AllowDuplicateForPageTypeFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return AllowDuplicateForPageType
     */
    public function __invoke($serviceContainer)
    {
        return new AllowDuplicateForPageType();
    }
}
