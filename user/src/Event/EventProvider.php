<?php

namespace RcmUser\Event;

use Zend\EventManager\EventManagerInterface;

/**
 * Class EventProvider
 *
 * EventProvider
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class EventProvider
{
    /**
     * @var EventManagerInterface $events
     */
    protected $eventManager;

    /**
     * EventProvider constructor.
     *
     * @param EventManagerInterface $eventManager
     */
    public function __construct(EventManagerInterface $eventManager)
    {
        $this->setEventManager($eventManager);
    }

    /**
     * setEventManager - Set the event manager instance used by this context
     *
     * @param EventManagerInterface $eventManager events
     *
     * @return $this
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $identifiers = [
            __CLASS__,
            get_called_class()
        ];

        $eventManager->addIdentifiers($identifiers);
        //$eventManager->setIdentifiers($identifiers);
        $this->eventManager = $eventManager;

        return $this;
    }

    /**
     * getEventManager
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
}
