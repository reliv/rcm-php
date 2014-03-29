<?php

/**
 * Module Config For ZF2
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

namespace Rcm;

use Rcm\Controller\IndexController;
use Rcm\EventListener\RcmDispatchListener;
use Rcm\EventListener\RcmRouteListener;
use Rcm\Model\PhoneModel;
use Rcm\Model\UserManagement\DoctrineUserManager;
use Rcm\Service\ContainerManager;
use Rcm\Service\DomainManager;
use Rcm\Service\LayoutManager;
use Rcm\Service\PageManager;
use Rcm\Service\PluginManager;
use Rcm\Service\SiteManager;
use Rcm\View\Helper\OutOfDateBrowserWarning;
use Zend\Log\Logger;
use Zend\Log\Writer\Null;
use Zend\Log\Writer\Stream;
use \Zend\Session\SessionManager;
use \Zend\Session\Container;
use \Rcm\Controller\StateApiController;
use \Zend\Cache\StorageFactory;
use \Rcm\Model\Logger as RcmLogger;

/**
 * ZF2 Module Config.  Required by ZF2
 *
 * ZF2 requires a Module.php file to load up all the Module Dependencies.  This
 * file has been included as part of the ZF2 standards.
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 */
class Module
{

    public function onBootstrap($e)
    {
        $this->bootstrapSession($e);
        $sm = $e->getApplication()->getServiceManager();

        //Add Domain Checker
        $onRouteListener = $sm->get('rcmRouteListener');
        $onDispatchListener = $sm->get('rcmDispatchListener');

        /** @var \Zend\EventManager\EventManager $eventManager */
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($onRouteListener, 'checkDomain'), 10000);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($onRouteListener, 'checkRedirect'), 9999);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($onDispatchListener, 'setSiteLayout'), 10000);

    }

    public function bootstrapSession($e)
    {
        if (!empty($_GET['logout'])) {
            $e->getApplication()->getServiceManager()->get('rcmUserMgr')
                ->logout();
            header('Location: /');
            exit;
        }
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

                'doctrine.cache.doctrine_cache' => function ($sm) {
                    $zendCache = $sm->get('rcmCache');
                    $cache
                        = new \DoctrineModule\Cache\ZendStorageCache($zendCache);
                    return $cache;
                },

                'cypher' => function ($serviceMgr) {
                    $config = $serviceMgr->get('config');
                    $config = $config['encryption']['blockCypher'];
                    $cypher = \Zend\Crypt\BlockCipher::factory(
                        'mcrypt',
                        array('algo' => $config['algo'])
                    );

                    $cypher->setKey($config['key']);
                    return $cypher;
                },

                'rcmRouteListener' => function ($serviceMgr) {
                    $listener = new RcmRouteListener(
                        $serviceMgr->get('rcmDomainManager')
                    );

                    return $listener;
                },

                'rcmDispatchListener' => function ($serviceMgr) {
                    $listener = new RcmDispatchListener(
                        $serviceMgr->get('rcmLayoutManager'),
                        $serviceMgr->get('rcmSiteManager'),
                        $serviceMgr->get('viewHelperManager')
                    );

                    return $listener;
                },

                'rcmContainerManager' => function ($serviceMgr) {
                    $manager = new ContainerManager(
                        $serviceMgr->get('rcmSiteManager'),
                        $serviceMgr->get('rcmPluginManager'),
                        $serviceMgr->get('em'),
                        $serviceMgr->get('rcmCache')
                    );
                    return $manager;
                },

                'rcmPluginManager' => function ($serviceMgr) {
                    $manager = new PluginManager(
                        $serviceMgr->get('em'),
                        $serviceMgr->get('config'),
                        $serviceMgr,
                        $serviceMgr->get('moduleManager'),
                        $serviceMgr->get('ViewRenderer'),
                        $serviceMgr->get('request'),
                        $serviceMgr->get('rcmCache')
                    );
                    return $manager;
                },

                'rcmLayoutManager' => function ($serviceMgr) {
                    $manager = new LayoutManager(
                        $serviceMgr->get('rcmSiteManager'),
                        $serviceMgr->get('config')
                    );
                    return $manager;
                },

                'rcmDomainManager' => function ($serviceMgr) {
                    $manager = new DomainManager(
                        $serviceMgr->get('em'),
                        $serviceMgr->get('rcmCache')
                    );
                    return $manager;
                },

                'rcmSiteManager' => function ($serviceMgr) {
                    $manager = new SiteManager(
                        $serviceMgr->get('rcmDomainManager'),
                        $serviceMgr->get('em'),
                        $serviceMgr->get('rcmCache')
                    );

                    return $manager;
                },

                'rcmPageManager' => function ($serviceMgr) {
                    $pageManager = new PageManager(
                        $serviceMgr->get('rcmSiteManager'),
                        $serviceMgr->get('rcmPluginManager'),
                        $serviceMgr->get('em'),
                        $serviceMgr->get('rcmCache')
                    );

                    return $pageManager;
                },

                'rcmPhoneModel' => function ($serviceMgr) {
                    return new PhoneModel($serviceMgr->get('rcmSiteManager')->getCurrentSiteCountry());
                },

                'rcmUserMgr' => function ($serviceMgr) {
                    $service = new DoctrineUserManager(
                        $serviceMgr->get('cypher'),
                        $serviceMgr->get('rcmSessionMgr')
                    );
                    $service->setEm($serviceMgr->get('em'));
                    return $service;
                },

                'em' => function ($serviceMgr) {
                    return $serviceMgr->get(
                        'doctrineormentitymanager'
                    );
                },

                'rcmIpInfo' => function () {
                    return new \Rcm\Model\IpInfo();
                },

                'rcmCache' => function ($serviceMgr) {
                    $config = $serviceMgr->get('config');

                    $cache = StorageFactory::factory(
                        array(
                            'adapter' => array(
                                'name' => $config['rcmCache']['adapter'],
                                'options' => $config['rcmCache']['options'],
                            ),
                            'plugins' => $config['rcmCache']['plugins'],
                        )
                    );

                    return $cache;
                },

                'RcmLogger' => function ($serviceManager) {
                    $zendLogger = $serviceManager->get('rcmZendLogger');
                    $logger = new RcmLogger($zendLogger);
                    return $logger;
                },

                'rcmZendLogger' => function ($serviceManager) {
                    $config = $serviceManager->get('config');

                    if (empty($config['rcmLogger']['writer'])) {
                        $writer = $serviceManager->get('rcmWriterStub');
                    } else {
                        $writer = $serviceManager->get(
                            $config['rcmLogger']['writer']
                        );
                    }

                    $logger = new Logger();
                    $logger->addWriter($writer);

                    return $logger;
                },

                'rcmLogWriter' => function ($serviceManager) {
                    $config = $serviceManager->get('config');

                    $path = $config['rcmLogWriter']['logPath'];

                    $writer = new Stream($path);

                    return $writer;
                },

                'rcmLogWriterStub' => function () {
                    return new Null();
                },

                'rcmSessionMgr' => function ($sm) {
                    $config = $sm->get('config');
                    if (isset($config['session'])) {
                        $session = $config['session'];

                        $sessionConfig = null;
                        if (isset($session['config'])) {
                            $class = isset($session['config']['class'])
                                ? $session['config']['class']
                                : 'Zend\Session\Config\SessionConfig';
                            $options = isset($session['config']['options'])
                                ? $session['config']['options'] : array();
                            $sessionConfig = new $class();
                            $sessionConfig->setOptions($options);
                        }

                        $sessionStorage = null;
                        if (isset($session['storage'])) {
                            $class = $session['storage'];
                            $sessionStorage = new $class();
                        }

                        $sessionSaveHandler = null;
                        if (isset($session['save_handler'])) {
                            // class should be fetched from service manager since it will require constructor arguments
                            $sessionSaveHandler = $sm->get(
                                $session['save_handler']
                            );
                        }

                        $sessionManager
                            = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);

                        if (isset($session['validator'])) {
                            $chain = $sessionManager->getValidatorChain();
                            foreach ($session['validator'] as $validator) {
                                $validator = new $validator();
                                $chain->attach(
                                    'session.validate',
                                    array($validator, 'isValid')
                                );

                            }
                        }
                    } else {
                        $sessionManager = new SessionManager();
                    }
                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                },

            ),
        );
    }

    function getControllerConfig()
    {
        return array(
            'factories' => array(

                'rcmIndexController' => function ($controllerMgr) {
                        $serviceMgr = $controllerMgr->getServiceLocator();
                        $controller = new IndexController(
                            $serviceMgr->get('rcmPageManager'),
                            $serviceMgr->get('rcmLayoutManager')
                        );
                        return $controller;
                    },

                'rcmPluginProxyController' => function ($controllerMgr) {
                        $serviceMgr = $controllerMgr->getServiceLocator();
                        $controller = new \Rcm\Controller\PluginProxyController(
                            $serviceMgr->get('rcmUserMgr'),
                            $serviceMgr->get('rcmPluginManager'),
                            $serviceMgr->get('em'),
                            $serviceMgr->get('viewRenderer'),
                            $serviceMgr->get('config')
                        );
                        return $controller;
                    },

                'rcmStateApiController' => function ($controllerMgr) {
                        $serviceMgr = $controllerMgr->getServiceLocator();
                        $controller = new StateApiController(
                            $serviceMgr->get('em')
                        );
                        return $controller;
                    },


            )
        );
    }

    function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                // the array key here is the name you will call the view helper by in your view scripts
                'rcmOutOfDateBrowserWarning' => function ($viewServiceMgr) {
                    $serviceMgr = $viewServiceMgr->getServiceLocator();
                    return new OutOfDateBrowserWarning();
                },

                'rcmContainer' => function ($viewServiceMgr) {
                    $serviceMgr = $viewServiceMgr->getServiceLocator();
                    $helper = new \Rcm\View\Helper\Container(
                        $serviceMgr->get('rcmContainerManager')
                    );

                    return $helper;
                },
            )
        );
    }
}
