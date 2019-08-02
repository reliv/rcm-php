<?php

namespace RcmUser\Log\Event\AclDataService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class DeleteAclRuleListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DeleteAclRuleListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DeleteAclRuleListener
     */
    public function __invoke($container)
    {
        return new DeleteAclRuleListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
