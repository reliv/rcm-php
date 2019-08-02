<?php

namespace RcmUser\Acl\Event;

use Interop\Container\ContainerInterface;

/**
 * Class IsAllowedErrorExceptionFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class IsAllowedErrorExceptionListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return IsAllowedErrorExceptionListener
     */
    public function __invoke($container)
    {
        return new IsAllowedErrorExceptionListener();
    }
}
