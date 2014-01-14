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

use Rcm\Model\PhoneModel;
use Rcm\View\Helper\OutOfDateBrowserWarning;
use Zend\Log\Logger;
use Zend\Log\Writer\Null;
use Zend\Log\Writer\Stream;
use \Zend\ModuleManager\ModuleManager;
use \Zend\Session\SessionManager;
use \Zend\Session\Container;
use \Rcm\Controller\StateApiController;
use \Rcm\Factory\DoctrineInjector;
use \Zend\Cache\StorageFactory;

/**
 * ZF2 Module Config.  Required by ZF2
 *
 * ZF2 requires a Module.php file to load up all the Module Dependencies.  This
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

    public function onBootstrap($e)
    {
        $this->bootstrapSession($e);
        $sm = $e->getApplication()->getServiceManager();
        $em = $sm->get('doctrine.entitymanager.orm_default');
        $dem = $em->getEventManager();
        $dem->addEventListener(array(\Doctrine\ORM\Events::postLoad), new DoctrineInjector($sm));
    }

    public function bootstrapSession($e)
    {
        if (!empty($_GET['logout'])) {
            $e->getApplication()->getServiceManager()->get('rcmUserMgr')->logout();
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
                    $cache = new \DoctrineModule\Cache\ZendStorageCache($zendCache);
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

                'rcmSite' => function ($serviceMgr) {
                    $appConfig = $serviceMgr->get('config');
                    $siteFactory = $serviceMgr->get('Rcm\Model\SiteFactory');
                    try {
                        $site = $siteFactory->getSite(
                            $_SERVER['HTTP_HOST'] //, $language
                        );
                    } catch (\Rcm\Exception\SiteNotFoundException $e) {
                        $site = $siteFactory->getSite(
                            $appConfig['reliv']['defaultDomain'] //, $language
                        );
                    }
                    return $site;
                },
                'rcmPhoneModel' => function ($serviceMgr) {
                    return new PhoneModel($serviceMgr->get('rcmSite')
                            ->getCountry()
                    );
                },
                'Rcm\Model\SiteFactory' =>
                function ($serviceMgr) {
                    $object = new \Rcm\Model\SiteFactory(
                        $serviceMgr->get('em')
                    );
                    return $object;
                },

                'Rcm\Model\PageFactory' =>
                function ($serviceMgr) {
                    $object = new \Rcm\Model\PageFactory(
                        $serviceMgr->get('em')
                    );
                    return $object;
                },

                'rcmPluginManager' => function ($serviceMgr) {
                    return new \Rcm\Model\PluginManager(
                        $serviceMgr->get('modulemanager'),
                        $serviceMgr->get('config'),
                        $serviceMgr
                    );
                },

                'rcmUserMgr' => function ($serviceMgr) {
                    $service = new \Rcm\Model\UserManagement\DoctrineUserManager(
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

                'rcmLogger' => function($serviceManager) {
                    $config = $serviceManager->get('config');

                    if (empty($config['rcmLogger']['writer'])) {
                        $writer = $serviceManager->get('rcmWriterStub');
                    } else {
                        $writer = $serviceManager->get($config['rcmLogger']['writer']);
                    }

                    $logger = new Logger();
                    $logger->addWriter($writer);

                    return $logger;
                },

                'rcmLogWriter' => function($serviceManager) {
                    $config = $serviceManager->get('config');

                    $path = $config['rcmLogWriter']['logPath'];

                    $writer = new Stream($path);

                    return $writer;
                },

                'rcmLogWriterStub' => function() {
                    return new Null();
                },

                'rcmSessionMgr' => function ($sm) {
                    $config = $sm->get('config');
                    if (isset($config['session'])) {
                        $session = $config['session'];

                        $sessionConfig = null;
                        if (isset($session['config'])) {
                            $class = isset($session['config']['class']) ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                            $options = isset($session['config']['options']) ? $session['config']['options'] : array();
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
                            $sessionSaveHandler = $sm->get($session['save_handler']);
                        }

                        $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);

                        if (isset($session['validator'])) {
                            $chain = $sessionManager->getValidatorChain();
                            foreach ($session['validator'] as $validator) {
                                $validator = new $validator();
                                $chain->attach('session.validate', array($validator, 'isValid'));

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
                    $controller = new \Rcm\Controller\IndexController(
                        $serviceMgr->get('rcmUserMgr'),
                        $serviceMgr->get('rcmPluginManager'),
                        $serviceMgr->get('em'),
                        $serviceMgr->get('viewRenderer'),
                        $serviceMgr->get('config')
                    );
                    return $controller;
                },
                'rcmAdminController' => function ($controllerMgr) {
                    $serviceMgr = $controllerMgr->getServiceLocator();
                    $controller = new \Rcm\Controller\AdminController(
                        $serviceMgr->get('rcmUserMgr'),
                        $serviceMgr->get('rcmPluginManager'),
                        $serviceMgr->get('em'),
                        $serviceMgr->get('viewRenderer'),
                        $serviceMgr->get('config')
                    );
                    return $controller;
                },
                'rcmPageSearchApiController' => function ($controllerMgr) {
                    $serviceMgr = $controllerMgr->getServiceLocator();
                    $controller = new \Rcm\Controller\PageSearchApiController(
                        $serviceMgr->get('rcmUserMgr'),
                        $serviceMgr->get('rcmPluginManager'),
                        $serviceMgr->get('em'),
                        $serviceMgr->get('viewRenderer'),
                        $serviceMgr->get('config')
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
                'rcmInstallController' => function ($controllerMgr) {
                    $serviceMgr = $controllerMgr->getServiceLocator();
                    $controller =
                        new \Rcm\Controller\InstallController(
                            $serviceMgr->get('em'),
                            $serviceMgr->get('rcmPluginManager')
                        );
                    return $controller;
                },
                'rcmStateApiController' => function ($controllerMgr) {
                    $serviceMgr = $controllerMgr->getServiceLocator();
                    $controller =
                        new StateApiController(
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

    public function init(\Zend\ModuleManager\ModuleManager $moduleManager)
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

        if (is_subclass_of(
            $object,
            __NAMESPACE__ . '\Controller\BaseController'
        )
        ) {
            $object->init();
        }
    }
}
