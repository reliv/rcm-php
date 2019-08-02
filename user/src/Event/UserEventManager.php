<?php

namespace RcmUser\Event;

use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * Class UserEventManager
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class UserEventManager extends \Zend\EventManager\EventManager implements EventManager
{
    /**
     * @var ListenerCollection
     */
    protected $listeners;

    /**
     * @var bool
     */
    protected $listenersPrepared = false;

    /**
     * UserEventManager constructor.
     *
     * @param array|int|null|string|\Traversable $identifiers
     * @param ListenerCollection                 $listeners
     */
    public function __construct(
        $identifiers,
        ListenerCollection $listeners
    ) {
        $this->listeners = $listeners;
        parent::__construct($identifiers);
    }

    /**
     * triggerListeners
     *
     * @param string         $event
     * @param EventInterface $e
     * @param null           $callback
     *
     * @return \Zend\EventManager\ResponseCollection
     */
    protected function triggerListeners($event, EventInterface $e, $callback = null)
    {
        $this->buildListeners();

        return parent::triggerListeners($event, $e, $callback);
    }

    /**
     * buildListeners
     *
     * @return void
     */
    protected function buildListeners()
    {
        if ($this->listenersPrepared) {
            return;
        }

        $listeners = $this->listeners->getListeners();

        /** @var ListenerAggregateInterface $listener */
        foreach ($listeners as $listener) {
            $listener->attach($this);
        }

        $this->listenersPrepared = true;
    }
}
