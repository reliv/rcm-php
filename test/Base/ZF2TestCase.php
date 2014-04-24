<?php

namespace RcmTest\Base;

use Zend\Config\Config;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class Zf2TestCase extends \PHPUnit_Framework_TestCase
{

    protected $modules = array();
    protected $moduleSerchPath = array();
    protected $moduleConfig = array();

    protected $config;
    protected $serviceManager;
    protected $renderer;

    protected $autoLoaderStarted = false;

    public function setUp()
    {
        $this->initAutoLoader();
        $this->setupConfig();
        $this->startZf2();
    }

    public function addModule($module)
    {
        if (in_array($module, $this->modules)) {
            return;
        }

        $this->modules[] = $module;
    }

    public function addModuleSearchPath($path)
    {
        if (!is_dir($path)) {
            throw new \InvalidArgumentException('Module Search Path '.$path.' not found');
        }

        if (in_array($path, $this->moduleSerchPath)) {
            return;
        }

        $this->moduleSerchPath[] = $path;
    }

    /**
     * Add config to application config.  This is done in reverse order due to the way inheritance works.  Be careful,
     * this is a gotcha.
     *
     * @param array $config
     */
    public function addApplicationConfig(Array $config)
    {
        $this->initAutoLoader();
        $newConfig = new Config($config);
        $currentConfig = $this->getExtraConfig();
        $currentConfig->merge($newConfig);
    }

    /**
     * Returns the running ZF2 config
     *
     * @return \Zend\Config\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Starts the ZF2 Autoloader for tests
     */
    protected function initAutoLoader()
    {
        if ($this->autoLoaderStarted) {
            return;
        }

        chdir(__DIR__ . '/../../../../../');
        include 'init_autoloader.php';

        $this->autoLoaderStarted = true;
    }

    /**
     * Gets the extra config object.  If not an object it will set this.
     *
     * @return array|Config
     */
    protected function getExtraConfig()
    {
        if (!is_a($this->moduleConfig, 'Zend\Config\Config')) {
            $this->moduleConfig = new Config(array());
        }

        return $this->moduleConfig;
    }

    /**
     * Used during setup process to create the needed ZF configs for controllers
     */
    protected function setupConfig()
    {
        $mainConfig = include __DIR__ . '/application.test.config.php';
        $config = new Config($mainConfig);

        $extraConfig = $this->getExtraConfig();
        $config->merge($extraConfig);

        $moreConfig = array();

        if (!empty($this->modules) && is_array($this->modules)) {
            $moreConfig['modules'] = $this->modules;
        }

        if (!empty($this->moduleSerchPath)
            && is_array(
                $this->moduleSerchPath
            )
        ) {
            $moreConfig['module_listener_options']
                = array('module_paths' => $this->moduleSerchPath);
        }

        if (!empty($moreConfig)) {
            $moreZfConfig = new Config($moreConfig);
            $config->merge($moreZfConfig);
        }

        $this->config = $config;
    }

    /**
     * Used during setup to start the ZF2 environment
     */
    protected function startZf2()
    {
        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $this->getConfig());
        $serviceManager->get('ModuleManager')->loadModules();

        $this->serviceManager = $serviceManager;
    }

    /*
     * Get the default Renderer
     */
    public function getRenderer()
    {
        if (empty($this->renderer)
            || !is_object($this->renderer)
            || !is_a($this->renderer, '\Zend\View\Renderer\RendererInterface')
        ) {
            /** @var \Zend\ServiceManager\ServiceManager $sm */
            $sm = $this->getServiceManager();

            $resolver = $sm->get('ViewResolver');

            $render = new \Zend\View\Renderer\PhpRenderer();
            $render->setResolver($resolver);

            $basePath = $render->plugin('basepath');
            $basePath->setBasePath('/');

            $this->renderer = $render;
        }

        return $this->renderer;
    }
}