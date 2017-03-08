<?php

namespace Rcm\Service;

use Doctrine\ORM\EntityManagerInterface;
use Rcm\Block\Config\Config;
use Rcm\Block\Config\ConfigRepository;
use Rcm\Block\Instance\InstanceRepository;
use Rcm\Block\InstanceWithData\InstanceWithData;
use Rcm\Block\InstanceWithData\InstanceWithDataBasic;
use Rcm\Block\InstanceWithData\InstanceWithDataService;
use Rcm\Block\Renderer\Renderer;
use Rcm\Entity\PluginInstance;
use Rcm\Exception\InvalidPluginException;
use Rcm\Exception\PluginInstanceNotFoundException;
use Rcm\Exception\RuntimeException;
use Rcm\Http\Response;
use Rcm\Plugin\PluginInterface;
use Zend\Cache\Storage\StorageInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Helper\Placeholder\Container;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\ViewEvent;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Rcm Plugin Manager
 *
 * Rcm plugin Manager.  This class handles everything about a CMS plugin.  Please
 * note that this handles the plugins directly and does not do anything in regards
 * to where the plugin renders on the page or in the container.  Positional
 * information is stored within a plugin wrapper and not within the plugin itself.
 * Wrapping the plugin with a positional wrapper helps to make plugins and plugin
 * instances reusable through out the site.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @SuppressWarnings(PHPMD)
 * @todo      - See if we can reduce the amount of dependencies.
 */
class PluginManager
{
    /** @var \Zend\ServiceManager\ServiceLocatorInterface */
    protected $serviceManager;

    /** @var \Zend\Stdlib\RequestInterface */
    protected $request;

    /** @var \Zend\View\Renderer\PhpRenderer */
    protected $renderer;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    protected $entityManager;

    /** @var array */
    protected $config;

    /** @var \Zend\Cache\Storage\StorageInterface */
    protected $cache;

    /** @var  \Zend\EventManager\EventManager */
    protected $eventManager;

    protected $blockRendererService;

    protected $instanceWithDataService;

    protected $blockConfigRepository;

    protected $instanceRepository;

    /**
     * Constructor
     *
     * @param EntityManagerInterface $entityManager Doctrine Entity Manager
     * @param array $config Config Array
     * @param ServiceLocatorInterface $serviceManager Zend Service Manager
     * @param PhpRenderer $renderer Zend Renderer
     * @param RequestInterface $request Zend Request Object
     * @param StorageInterface $cache Zend Cache Manager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        array $config,
        ServiceLocatorInterface $serviceManager,
        PhpRenderer $renderer,
        RequestInterface $request,
        StorageInterface $cache,
        EventManagerInterface $eventManager,
        Renderer $blockRendererService,
        InstanceWithDataService $instanceWithDataService,
        InstanceRepository $instanceRepository,
        ConfigRepository $blockConfigRepository
    ) {
        $this->serviceManager = $serviceManager;
        $this->request = $request;
        $this->renderer = $renderer;
        $this->entityManager = $entityManager;
        $this->config = $config;
        $this->cache = $cache;
        $this->eventManager = $eventManager;
        $this->blockRendererService = $blockRendererService;
        $this->instanceWithDataService = $instanceWithDataService;
        $this->blockConfigRepository = $blockConfigRepository;
        $this->instanceRepository = $instanceRepository;
    }

    /**
     * prepPluginForDisplay
     *
     * @param PluginInstance $instance
     *
     * @return void
     */
    public function prepPluginForDisplay(PluginInstance $instance)
    {
        $cacheId = 'rcmPluginInstance_viewData_' . $instance->getInstanceId();

        if ($this->cache->hasItem($cacheId)) {
            $viewData = $this->cache->getItem($cacheId);
        } else {
            $viewData = $this->getPluginViewData(
                $instance->getPlugin(),
                $instance->getInstanceId()
            );

            if ($viewData['canCache']) {
                $this->cache->setItem($cacheId, $viewData);
            }
        }

        $instance->setRenderedHtml($viewData['html']);
        $instance->setRenderedCss($viewData['css']);
        $instance->setRenderedJs($viewData['js']);
        $instance->setEditJs($viewData['editJs']);
        $instance->setEditCss($viewData['editCss']);
        $instance->setTooltip($viewData['tooltip']);
        $instance->setIcon($viewData['icon']);
        $instance->setCanCache($viewData['canCache']);

        return;
    }

    /**
     * Get a plugin instance rendered view.
     *
     * @param string $pluginName Plugin name
     * @param integer $pluginInstanceId Plugin Instance Id
     * @param null $forcedAlternativeInstanceConfig Not normally used. Useful for previewing changes
     *
     * @return array
     * @throws \Rcm\Exception\InvalidPluginException
     * @throws \Rcm\Exception\PluginReturnedResponseException
     */
    public function getPluginViewData(
        $pluginName,
        $pluginInstanceId,
        $forcedAlternativeInstanceConfig = null
    ) {
        $request = ServerRequestFactory::fromGlobals();

        $blockConfig = $this->blockConfigRepository->findById($pluginName);

        if ($pluginInstanceId < 0) {
            $instanceWithData = new InstanceWithDataBasic(
                $pluginInstanceId,
                $pluginName,
                $blockConfig->getDefaultConfig(),
                [] //@TODO run the dataprovider here instead of returning empty array
            );
        } else {
            $instanceWithData = $this->instanceWithDataService->__invoke($pluginInstanceId, $request);
        }

        if ($forcedAlternativeInstanceConfig !== null) {
            $instanceWithData = new InstanceWithDataBasic(
                $instanceWithData->getId(),
                $instanceWithData->getName(),
                $forcedAlternativeInstanceConfig,
                //@TODO we should have got the data from the data provider with the forced instance config as an input
                $instanceWithData->getData()
            );
        }

        $html = $this->blockRendererService->__invoke($instanceWithData);

        /**
         * @var $blockConfig Config
         */

        $return = [
            'html' => $html,
            'css' => [],
            'js' => [],
            'editJs' => '',
            'editCss' => '',
            'displayName' => $blockConfig->getLabel(),
            'tooltip' => $blockConfig->getDescription(),
            'icon' => $blockConfig->getIcon(),
            'siteWide' => false,
            'md5' => '',
            'fromCache' => false,
            'canCache' => $blockConfig->getCache(),
            'pluginName' => $blockConfig->getName(),
            'pluginInstanceId' => $pluginInstanceId,
        ];

        return $return;
    }

    /**
     * Get an instantiated plugin controller
     *
     * @param string $pluginName Plugin Name
     *
     * @return PluginInterface
     * @throws \Rcm\Exception\InvalidPluginException
     * @throws \Rcm\Exception\RuntimeException
     */
    public function getPluginController($pluginName)
    {
        /*
         * Deprecated.  All controllers should come from the controller manager
         * now and not the service manager.
         *
         * @todo Remove if statement once plugins have been converted.
         */
        if ($this->serviceManager->has($pluginName)) {
            $serviceManager = $this->serviceManager;
        } else {
            $serviceManager = $this->serviceManager->get('ControllerLoader');
        }

        if (!$serviceManager->has($pluginName)) {
            throw new InvalidPluginException(
                "Plugin $pluginName is not loaded or configured. Check
            config/application.config.php"
            );
        }

        //Load the plugin controller
        try {
            $pluginController = $serviceManager->get($pluginName);
        } catch (\Exception $e) {
            throw new RuntimeException(
                'Unable to get instance of plugin: ' . $pluginName,
                1,
                $e
            );
        }

        //Plugin controllers must implement this interface
        if (!$pluginController instanceof PluginInterface) {
            throw new InvalidPluginException(
                'Class "' . get_class($pluginController) . '" for plugin "'
                . $pluginName . '" does not implement '
                . '\Rcm\Plugin\PluginInterface'
            );
        }

        return $pluginController;
    }

    /**
     * getInstanceConfigForPlugin
     *
     * @param $pluginInstanceId
     *
     * @return array
     */
    public function getInstanceConfig($pluginInstanceId)
    {
        $blockInstance = $this->instanceRepository->findById($pluginInstanceId);

        if (empty($blockInstance)) {
            throw new PluginInstanceNotFoundException(
                'Block for instance id ' . $pluginInstanceId . ' not found.'
            );
        }

        return $instanceConfig = $blockInstance->getConfig();
    }

    /**
     * Default instance configs are NOT required anymore
     *
     * @param string $pluginName the plugins module name
     *
     * @return array
     */
    public function getDefaultInstanceConfig($pluginName)
    {
        return $this->blockConfigRepository->findById($pluginName)->getDefaultConfig();
    }

    /**
     * Returns an array the represents the available plugins
     *
     * @return array
     */
    public function listAvailablePluginsByType()
    {
        $list = [];
        foreach ($this->config['rcmPlugin'] as $name => $data) {
            $displayName = $name;
            $type = 'Misc';
            $icon = $this->config['Rcm']['defaultPluginIcon'];
            if (isset($data['type'])) {
                $type = $data['type'];
            }
            if (isset($data['display'])) {
                $displayName = $data['display'];
            }
            if (isset($data['icon']) && !empty($data['icon'])) {
                $icon = $data['icon'];
            }
            $list[$type][$name] = [
                'name' => $name,
                'displayName' => $displayName,
                'icon' => $icon,
                'siteWide' => false
            ];
        }

        return $list;
    }
}
