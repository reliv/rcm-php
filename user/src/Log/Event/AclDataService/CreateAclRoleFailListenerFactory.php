<?php

namespace RcmUser\Log\Event\AclDataService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class CreateAclRoleFailListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class CreateAclRoleFailListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return CreateAclRoleFailListener
     */
    public function __invoke($container)
    {
        return new CreateAclRoleFailListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
