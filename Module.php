<?php

/**
 * Module Config For ZF2
*
* PHP version 5.3
*
* LICENSE: No License yet
*
* @category  Reliv
* @package   ContentManager\ZF2
* @author    Westin Shafer <wshafer@relivinc.com>
* @copyright 2012 Reliv International
* @license   License.txt New BSD License
* @version   GIT: <git_id>
* @link      http://ci.reliv.com/confluence
*/

namespace Rcm;

use \Zend\ModuleManager\ModuleManager;

/**
 * ZF2 Module Config.  Required by ZF2
 *
 * ZF2 reqires a Module.php file to load up all the Module Dependencies.  This
 * file has been included as part of the ZF2 standards.
 *
 * @category  Reliv
 * @package   ContentManager\ZF2
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 */
class Module
{
    /**
     * getAutoloaderConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
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
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * getServiceConfiguration is used by the ZF2 service manager in order
     * to create new objects.
     *
     * @return object Returns an object.
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'cypher'=>function($serviceMgr){
                    $config=$serviceMgr->get('config');
                    $config=$config['encryption']['blockCypher'];
                    $cypher = \Zend\Crypt\BlockCipher::factory(
                        'mcrypt',
                        array('algo' => $config['algo'])
                    );

                    $cypher->setKey($config['key']);
                    return $cypher;
                },
                'Rcm\Model\SiteFactory' =>
                function($serviceMgr)
                {
                    $object = new \Rcm\Model\SiteFactory(
                        $serviceMgr->get('em')
                    );
                    return $object;
                },

                'Rcm\Model\PageFactory' =>
                function($serviceMgr)
                {
                    $object = new \Rcm\Model\PageFactory(
                        $serviceMgr->get('em')
                    );
                    return $object;
                },

                'rcmPluginManager' => function($serviceMgr){
                    return new \Rcm\Model\PluginManager(
                        $serviceMgr->get('modulemanager'),
                        $serviceMgr->get('config'),
                        $serviceMgr
                    );
                },

                'rcmUserManager' =>
                function($serviceMgr)
                {
                    $service = new \Rcm\UserManagement\DoctrineUserManager(
                        $serviceMgr->get('cypher')
                    );
                    $service->setEm($serviceMgr->get('em'));
                    return $service;
                },

                'em' => function($serviceMgr){
                    return $serviceMgr->get(
                        'doctrine.entitymanager.ormdefault'
                    );
                }
            ),
        );
    }

    function getControllerConfig(){
        return array(
            'factories' => array(
                'rcmIndexController' => function($controllerMgr) {
                        $serviceMgr=$controllerMgr->getServiceLocator();
                        $controller = new \Rcm\Controller\IndexController(
                            $serviceMgr->get('rcmUserManager'),
                            $serviceMgr->get('rcmPluginManager'),
                            $serviceMgr->get('em')
                        );
                    return $controller;
                },
                'rcmAdminController' => function($controllerMgr) {
                    $serviceMgr=$controllerMgr->getServiceLocator();
                    $controller = new \Rcm\Controller\AdminController(
                        $serviceMgr->get('rcmUserManager'),
                        $serviceMgr->get('rcmPluginManager'),
                        $serviceMgr->get('em')
                    );
                    return $controller;
                },
            )
        );
    }

    /**
     * New Init process for ZF2.
     *
     * @param ModuleManager $moduleManager ZF2 Module Manager.  Passed in
     *                                     from the service manager.
     *
     * @return null
     */

    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach(
            'Rcm',
            'dispatch',
            array($this, 'baseControllerInit'),
            12
        );

    }

    /**
     * Event Listener for the Base Controller.
     *
     * @param \Zend\EventManager\Event $event ZF2 Called Event
     *
     * @return null
     */
    public function baseControllerInit($event)
    {

        $object = $event->getTarget();

        if ( is_subclass_of(
            $object,
            __NAMESPACE__.'\Controller\BaseController'
        )) {
            $object->init();
        }
    }
}
