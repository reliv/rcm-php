<?php

namespace RcmUser\Event;

use Zend\EventManager\Event;

/**
 * Class Listener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface Listener
{
    /**
     * __invoke
     *
     * @param Event $event
     *
     * @return bool true to stop event propagation
     */
    public function __invoke(Event $event);

    /**
     * getIdentifier
     *
     * @return string|array $id Identifier(s) for event emitting component(s)
     */
    public function getIdentifier();

    /**
     * getEvent
     *
     * @return string Event name
     */
    public function getEvent();

    /**
     * getPriority
     *
     * @return int
     */
    public function getPriority();

    /**
     * withPriority - Immutable priority setter
     *
     * @param int $priority
     *
     * @return Listener
     */
    public function withPriority($priority);
}
