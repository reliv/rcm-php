<?php

namespace RcmUser\Api\Acl;

use Interop\Container\ContainerInterface;
use RcmUser\Acl\Service\AclDataService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetRulesByResourcesBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return GetRulesByResourcesBasic
     */
    public function __invoke($serviceContainer)
    {
        return new GetRulesByResourcesBasic(
            $serviceContainer->get(AclDataService::class)
        );
    }
}
