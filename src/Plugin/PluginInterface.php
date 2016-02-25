<?php

namespace Rcm\Plugin;

use Zend\EventManager\EventInterface as Event;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;

/**
 * Plugin Controller Interface
 *
 * Is the contract for plugin controllers
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
interface PluginInterface
{
    /**
     * Reads a plugin instance from persistent storage returns a view model for
     * it
     *
     * @param int   $instanceId     plugin instance id
     * @param array $instanceConfig Instance Config
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderInstance($instanceId, $instanceConfig);

    /**
     * Set zend request object
     *
     * @param RequestInterface $request
     *
     * @return mixed
     */
    public function setRequest(RequestInterface $request);

    /**
     * Set zend response object
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function setResponse(ResponseInterface $response);

    /**
     * Get the current event or return a new event
     *
     * @return Event
     */
    public function getEvent();

    /**
     * @param \Zend\EventManager\Event $event
     *
     * @return void
     */
    public function setEvent(Event $event);
}
