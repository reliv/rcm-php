<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rmcnew
 * Date: 10/30/12
 * Time: 11:10 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Rcm\Model;

class PluginManager
{

    protected $moduleManager;

    protected $config;

    /**
     * Using the service locator inside this class does violate "inversion of
     * control" but it is needed in order to load plugin controllers. These
     * controllers can not be injected into this class because only this class
     * knows which ones need to be loaded.
     * @var $serviceLocator \Zend\ServiceManager\ServiceManager
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ModuleManager\ModuleManager   $moduleManager
     * @param array                               $config
     * @param \Zend\ServiceManager\ServiceManager $serviceLocator
     */
    function __construct(
        \Zend\ModuleManager\ModuleManager $moduleManager,
        $config,
        \Zend\ServiceManager\ServiceManager $serviceLocator
    ) {
        $this->moduleManager = $moduleManager;
        $this->config = $config;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @param \Rcm\Entity\PluginInstance $instance
     * @param                            $action
     * @param array                      $dataToPass
     * @param \Zend\EventManager\Event   $event
     *
     * @return mixed
     */
    public function callPlugin(
        \Rcm\Entity\PluginInstance $instance,
        $action,
        $dataToPass = array(),
        $event=null
    )
    {
        $pluginName = $instance->getName();

        $this->ensurePluginIsValid($pluginName);

        $pluginController = $this->getPluginController($pluginName);

        //If the plugin controller can accept a ZF2 event, pass it
        if (!empty($event) && method_exists($pluginController, 'setEvent')) {
            $pluginController->setEvent($event);
        }

        if (empty($dataToPass)) {
            return $pluginController->{$action}($instance->getInstanceId());
        }

        return $pluginController->{$action}(
            $instance->getInstanceId(), $dataToPass
        );
    }

    /**
     * Gets cached plugin controller. Creates one if not in cache
     * @param $pluginName
     *
     * @return mixed
     * @throws \Exception
     */
    function getPluginController($pluginName)
    {
        //Load the plugin controller
        $pluginController = $this->serviceLocator->get($pluginName);

        //Plugin controllers must implement this interface
        if (!$pluginController instanceof \Rcm\Plugin\PluginInterface) {
            throw new \Exception(
                'Class "' . get_class($pluginController) . '" for plugin "'
                    . $pluginName . '" does not implement '
                    . '\Rcm\Plugin\PluginInterface'
            );
        }

        return $pluginController;
    }

    /**
     * @param $pluginName
     *
     * @return bool
     * @throws \Exception
     */
    function ensurePluginIsValid($pluginName)
    {
        $loadedModules = $this->moduleManager->getLoadedModules();

        if(!isset($loadedModules[$pluginName])){
            throw new \Exception(
                "Plugin $pluginName is not loaded or configured. Check
                config/application.config.php"
            );
        }

        return true;
    }

    public function loadPlugin(
        \Rcm\Entity\PluginInstance $instance,
        \Zend\EventManager\Event $event
    )
    {
        if ($instance->getInstanceId() < 0) {
            $view = $this->callPlugin(
                $instance,
                'renderDefaultInstance',
                $event
            );
        } else {
            $view = $this->callPlugin(
                $instance,
                'renderInstance',
                $event
            );
        }

        $instance->setViewModel($view);
    }

    public function savePlugin(
        \Rcm\Entity\PluginInstance $instance,
        $dataToSave
    )
    {
        //We don't want to save the asset array, it is for core us only
        unset($dataToSave['assets']);

        $this->callPlugin(
            $instance,
            'saveInstance',
            $dataToSave
        );
    }

    /**
     * Prep a plugin instance to be passed to the View layer
     *
     * @param \Rcm\Entity\PluginInstance $instance plugin Instance
     *
     * @return \Rcm\Entity\PluginInstance $instance plugin Instance
     * @throws \Exception
     */
    public function prepPluginInstance(
        \Rcm\Entity\PluginInstance $instance,
        \Zend\EventManager\Event $event
    )
    {
        /** @var \Zend\Cache\Storage\StorageInterface $cache  */
        $cache = $this->serviceLocator->get('rcmCache');

        if ($cache->hasItem($instance->getInstanceId())) {
            print "Has Item";
            exit;
        }

        $this->loadPlugin($instance,$event);

        $pluginName = $instance->getName();

        $config=$this->config;

        if (isset($config['rcmPlugin'][$pluginName]['editJs'])) {
            $instance->setAdminEditJs(
                $config['rcmPlugin'][$pluginName]['editJs']
            );
        }

        if (isset($config['rcmPlugin'][$pluginName]['editCss'])) {
            $instance->setAdminEditCss(
                $config['rcmPlugin'][$pluginName]['editCss']
            );
        }

        if (isset($config['rcmPlugin'][$pluginName]['display'])
            && !$instance->isSiteWide()
        ) {
            $instance->setDisplayName(
                $config['rcmPlugin'][$pluginName]['display']
            );
        }

        if (isset($config['rcmPlugin'][$pluginName]['tooltip'])) {
            $instance->setTooltip($config['rcmPlugin'][$pluginName]['tooltip']);
        }

        if (isset($config['rcmPlugin'][$pluginName]['icon'])) {
            $instance->setIcon($config['rcmPlugin'][$pluginName]['icon']);
        }

        $cache->addItem($instance->getInstanceId(), $instance);
        return $instance;
    }
}
