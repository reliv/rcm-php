<?php

namespace RcmUser\Event;

use Interop\Container\ContainerInterface;
use RcmUser\Exception\RcmUserException;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * Class AbstractConfigurableListeners
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
abstract class AbstractConfigurableListeners implements ListenerAggregateInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $listenerConfig
        = [
            /* EXAMPLE *
            {ListenerServiceName} => {$priority}
            /* */
        ];

    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     * @param array              $listenerConfig
     */
    public function __construct(
        $container,
        array $listenerConfig
    ) {
        $this->container = $container;
        $this->listenerConfig = $listenerConfig;
    }

    /**
     * attach
     *
     * @param UserEventManager|EventManagerInterface $userEventManager
     *
     * @return void
     * @throws RcmUserException
     */
    public function attach(EventManagerInterface $userEventManager)
    {
        $sharedEvents = $userEventManager->getSharedManager();

        foreach ($this->listenerConfig as $serviceName => $priority) {
            $listener = $this->container->get($serviceName);

            if (!$listener instanceof Listener) {
                throw new RcmUserException("Service {$serviceName} must be an instance of " . Listener::class);
            }

            $listener = $listener->withPriority($priority);

            $this->listeners[$serviceName] = $sharedEvents->attach(
                $listener->getIdentifier(),
                $listener->getEvent(),
                $listener,
                $listener->getPriority()
            );
        }
    }

    /**
     * detach
     *
     * @param EventManagerInterface $userEventManager events
     *
     * @return void
     */
    public function detach(EventManagerInterface $userEventManager)
    {
        foreach ($this->listeners as $serviceName => $listener) {
            if ($userEventManager->detach($listener)) {
                unset($this->listeners[$serviceName]);
            }
        }
    }
}
