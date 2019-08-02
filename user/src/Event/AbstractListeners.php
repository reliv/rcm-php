<?php

namespace RcmUser\Event;

use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * Class AbstractListeners
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AbstractListeners implements ListenerAggregateInterface
{
    /**
     * @var array
     */
    protected $listeners = [];
    /**
     * @var string
     */
    protected $id = ''; //\RcmUser\Service\RcmUserService::class;
    /**
     * @var string
     */
    protected $event = null;
    /**
     * @var int
     */
    protected $priority = 0;

    /**
     * attach
     *
     * @param EventManagerInterface $events events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();
        $this->listeners[] = $sharedEvents->attach(
            $this->id,
            $this->event,
            [
                $this,
                'onEvent'
            ],
            $this->priority
        );
    }

    /**
     * detach
     *
     * @param EventManagerInterface $events events
     *
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * onEvent
     *
     * @param Event $e e
     *
     * @return void
     */
    public function onEvent($e)
    {
        var_dump($e);
    }
}
