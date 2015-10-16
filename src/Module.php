<?php

/**
 * Module Config For ZF2
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmAdmin;

use Zend\Console\Request;
use Zend\Mvc\MvcEvent;

/**
 * ZF2 Module Config.  Required by ZF2
 *
 * ZF2 requires a Module.php file to load up all the Module Dependencies.  This
 * file has been included as part of the ZF2 standards.
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class Module
{

    /**
     * Add Listeners to the bootstrap
     *
     * @param MvcEvent $e Event Manager
     *
     * @return null
     */
    public function onBootstrap(MvcEvent $e)
    {

        $serviceManager = $e->getApplication()->getServiceManager();

        $config = $serviceManager->get('config');

        if (empty($config['Rcm']['installed']) && empty($config['Rcm']['defaultDomain'])) {
            return;
        }

        // Don't break console routes
        if ($e->getRequest() instanceof Request) {
            return;
        }

        //Add Domain Checker
        $onDispatchListener = $serviceManager->get(
            'RcmAdmin\EventListener\DispatchListener'
        );

        /** @var \Zend\EventManager\EventManager $eventManager */
        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH,
            [
                $onDispatchListener,
                'getAdminPanel'
            ],
            10001
        );

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
        return include __DIR__ . '/../config/module.config.php';
    }
}
