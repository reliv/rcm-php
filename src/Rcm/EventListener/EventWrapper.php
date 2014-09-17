<?php
/**
 * Rcm Event Wrapper
 *
 * RCM Event Wrapper will listen for events and fire the correct CMS
 * action for that event
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace Rcm\EventListener;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\ViewEvent;

/**
 * Rcm Event Wrapper
 *
 * RCM Event Wrapper will listen for events and fire the correct CMS
 * action for that event
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class EventWrapper
{
    /** @var ServiceLocatorInterface  */
    protected $serviceLocator;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function routeEvent(MvcEvent $event)
    {
        /** @var \Rcm\EventListener\RouteListener $routeListener */
        $routeListener = $this->serviceLocator->get(
            'Rcm\EventListener\RouteListener'
        );

        $return = $routeListener->checkRedirect($event);

        if (!empty($return)) {
            return $return;
        }

        $return = $routeListener->checkDomain($event);

        if (!empty($return)) {
            return $return;
        }

        $return = $routeListener->addLocale($event);

        if (!empty($return)) {
            return $return;
        }

        return null;
    }

    public function dispatchEvent(MvcEvent $event)
    {
        $matchRoute = $event->getRouteMatch();

        if (empty($matchRoute)){
            return null;
        }

        /** @var \Rcm\EventListener\DispatchListener $dispatchListener */
        $dispatchListener
            = $this->serviceLocator->get('Rcm\EventListener\DispatchListener');

        $return = $dispatchListener->setSiteLayout($event);

        if (!empty($return)) {
            return $return;
        }

        return null;
    }

    public function finishEvent(MvcEvent $event) {
        $matchRoute = $event->getRouteMatch();

        if (empty($matchRoute)){
            return null;
        }

        /** @var \Rcm\EventListener\EventFinishListener $eventFinishListener */
        $eventFinishListener
            = $this->serviceLocator->get('Rcm\EventListener\EventFinishListener');

        $return = $eventFinishListener->processRcmResponses($event);

        if (!empty($return)) {
            return $return;
        }

        return null;
    }

    public function viewResponseEvent(ViewEvent $event)
    {
        /** @var \Rcm\EventListener\ViewEventListener $viewEventListener */
        $viewEventListener
            = $this->serviceLocator->get('Rcm\EventListener\ViewEventListener');

        $return = $viewEventListener->processRcmResponses($event);

        if (!empty($return)) {
            return $return;
        }

        return null;

    }
}