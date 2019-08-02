<?php

namespace RcmUser\Event;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ListenerCollectionFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ListenerCollectionFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return object
     */
    public function __invoke($serviceLocator)
    {
        $listeners = new ListenerCollection(
            $serviceLocator
        );

        $config = $serviceLocator->get('Config');

        $eventListenerConfig = $config['RcmUser']['EventListener\Config'];

        foreach ($eventListenerConfig as $alias => $serviceName) {
            $listeners->addListener($serviceName);
        }

        return $listeners;
    }
}
