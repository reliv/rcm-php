<?php

namespace RcmUser\Acl\Event;

use Interop\Container\ContainerInterface;
use RcmUser\Acl\Config;

/**
 * Class AclListenersFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AclListenersFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return AclListeners
     */
    public function __invoke($container)
    {
        $aclConfig = $container->get(Config::class);

        return new AclListeners(
            $container,
            $aclConfig->get(AclListeners::class, [])
        );
    }
}
