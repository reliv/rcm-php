<?php

namespace RcmUser\Log\Event\AclDataService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class DeleteAclRuleSuccessListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DeleteAclRuleSuccessListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DeleteAclRuleSuccessListener
     */
    public function __invoke($container)
    {
        return new DeleteAclRuleSuccessListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
