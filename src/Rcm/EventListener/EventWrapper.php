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
    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    /**
     * Construct
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Route event
     *
     * @param MvcEvent $event
     *
     * @return null|\Zend\Http\Response
     */
    public function routeEvent(MvcEvent $event)
    {
        $this->doLogout($event);

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

    /**
     * Dispatch Event
     *
     * @param MvcEvent $event
     *
     * @return null
     */
    public function dispatchEvent(MvcEvent $event)
    {
        $matchRoute = $event->getRouteMatch();

        if (empty($matchRoute)) {
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

    /**
     * Finish Event
     *
     * @param MvcEvent $event
     *
     * @return null
     */
    public function finishEvent(MvcEvent $event)
    {
        $matchRoute = $event->getRouteMatch();

        if (empty($matchRoute)) {
            return null;
        }

        /** @var \Rcm\EventListener\EventFinishListener $eventFinishListener */
        $eventFinishListener
            = $this->serviceLocator->get(
            'Rcm\EventListener\EventFinishListener'
        );

        $return = $eventFinishListener->processRcmResponses($event);

        if (!empty($return)) {
            return $return;
        }

        return null;
    }

    /**
     * View Response Event
     *
     * @param ViewEvent $event
     *
     * @return null
     */
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

    /**
     * doLogout
     *
     * @param MvcEvent $event event
     *
     * @return void
     */
    public function doLogout(MvcEvent $event)
    {
        $application = $event->getApplication();
        $sm = $application->getServiceManager();

        /** @var $request \Zend\Http\Request */
        $request = $sm->get('request');
        $logout = (bool)$request->getQuery('logout', 0);

        if ($logout) {
            session_destroy();
            $request = explode('?', $_SERVER['REQUEST_URI']);
            header('Location: //' . $_SERVER['HTTP_HOST'] . $request[0]);
            die;
        }
    }
}