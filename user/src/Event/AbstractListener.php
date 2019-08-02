<?php

namespace RcmUser\Event;

/**
 * Class AbstractListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
abstract class AbstractListener implements Listener
{
    /**
     * @var string|array
     */
    protected $identifier = null;

    /**
     * @var string
     */
    protected $event = null;

    /**
     * @var int
     */
    protected $priority = 0;

    /**
     * getIdentifier
     *
     * @return string|array $id Identifier(s) for event emitting component(s)
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * getEvent
     *
     * @return string Event name
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * getPriority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * withPriority - Immutable priority setter
     *
     * @param int $priority
     *
     * @return mixed
     */
    public function withPriority($priority)
    {
        $priority = (int)$priority;
        $new = clone($this);
        $new->priority = $priority;

        return $new;
    }
}
