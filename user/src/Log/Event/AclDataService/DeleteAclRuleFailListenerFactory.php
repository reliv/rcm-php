<?php

namespace RcmUser\Log\Event\AclDataService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class DeleteAclRuleFailListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DeleteAclRuleFailListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DeleteAclRuleFailListener
     */
    public function __invoke($container)
    {
        return new DeleteAclRuleFailListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
