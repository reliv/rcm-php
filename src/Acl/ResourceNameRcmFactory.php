<?php

namespace Rcm\Acl;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResourceNameRcmFactory
{
    /**
     * @param ServiceManager|ContainerInterface $serviceContainer
     *
     * @return ResourceNameRcm
     */
    public function __invoke($serviceContainer)
    {
        return new ResourceNameRcm();
    }
}
