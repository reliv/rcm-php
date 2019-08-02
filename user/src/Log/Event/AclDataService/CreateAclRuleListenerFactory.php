<?php

namespace RcmUser\Log\Event\AclDataService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class CreateAclRuleListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class CreateAclRuleListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return CreateAclRuleListener
     */
    public function __invoke($container)
    {
        return new CreateAclRuleListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
