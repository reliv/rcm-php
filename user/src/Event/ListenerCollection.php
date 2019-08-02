<?php

namespace RcmUser\Event;

use Interop\Container\ContainerInterface;

/**
 * Class ListenerCollection
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ListenerCollection
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * Listeners constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * hasListeners
     *
     * @return bool
     */
    public function hasListeners()
    {
        return !empty($this->listeners);
    }

    /**
     * addListener
     *
     * @param string $serviceName
     *
     * @return void
     */
    public function addListener($serviceName)
    {
        $this->listeners[$serviceName] = $serviceName;
    }

    /**
     * getListeners
     *
     * @return array
     */
    public function getListeners()
    {
        $listeners = [];

        foreach ($this->listeners as $serviceName) {
            $listeners[$serviceName] = $this->container->get($serviceName);
        }

        return $listeners;
    }
}
