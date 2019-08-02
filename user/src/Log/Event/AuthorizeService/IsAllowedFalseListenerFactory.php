<?php

namespace RcmUser\Log\Event\AuthorizeService;

use Interop\Container\ContainerInterface;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;

/**
 * Class IsAllowedFalseListenerFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class IsAllowedFalseListenerFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return IsAllowedFalseListener
     */
    public function __invoke($container)
    {
        return new IsAllowedFalseListener(
            $container->get(Logger::class),
            $container->get(RcmUserService::class)
        );
    }
}
