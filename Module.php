<?php

/**
 * Module Config For ZF2
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
 * @link      https://github.com/reliv
 */

namespace Rcm;

use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\MvcEvent;
use Zend\View\ViewEvent;

/**
 * ZF2 Module Config.  Required by ZF2
 *
 * ZF2 requires a Module.php file to load up all the Module Dependencies.  This
 * file has been included as part of the ZF2 standards.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class Module
{

    /**
     * Bootstrap For RCM.
     *
     * @param MvcEvent $event Zend MVC Event
     *
     * @return null
     */
    public function onBootstrap(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        $request = $serviceManager->get('request');

        if ($request instanceof ConsoleRequest) {
            return;
        }

        //Add Domain Checker
        $eventWrapper = $serviceManager->get(
            'Rcm\EventListener\EventWrapper'
        );

        /** @var \Zend\EventManager\EventManager $eventManager */
        $eventManager = $event->getApplication()->getEventManager();

        // Check for redirects from the CMS
        $eventManager->attach(
            MvcEvent::EVENT_ROUTE,
            [$eventWrapper, 'routeEvent'],
            10000
        );

        // Set the sites layout.
        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH,
            [$eventWrapper, 'dispatchEvent'],
            10000
        );

        // Set the custom http response checker
        $eventManager->attach(
            MvcEvent::EVENT_FINISH,
            [$eventWrapper, 'finishEvent'],
            10000
        );

        $viewEventManager = $serviceManager->get('ViewManager')
            ->getView()
            ->getEventManager();

        // Set the plugin response over-ride
        $viewEventManager->attach(
            ViewEvent::EVENT_RESPONSE,
            [$eventWrapper, 'viewResponseEvent'],
            -10000
        );

        /** @var \Zend\Session\SessionManager $session */
        $session = $serviceManager->get('Rcm\Service\SessionMgr');
        $session->start();
    }

    /**
     * getAutoloaderConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    /**
     * getConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
