<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rmcnew
 * Date: 10/30/12
 * Time: 11:10 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Rcm\Service;

use Doctrine\ORM\EntityManagerInterface;
use Rcm\Entity\PluginInstance;
use Rcm\Exception\InvalidPluginException;
use Rcm\Exception\PluginInstanceNotFoundException;
use Rcm\Exception\RuntimeException;
use Zend\Cache\Storage\StorageInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Stdlib\RequestInterface;
use Zend\ModuleManager\ModuleManager;
use Rcm\Plugin\PluginInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\RendererInterface;

class PluginManager
{
    protected $sm;
    protected $moduleManager;
    protected $request;
    protected $renderer;
    protected $entityManager;
    protected $config;
    protected $cache;

    public function __construct(
        EntityManagerInterface $em,
        $config,
        ServiceLocatorInterface $sm,
        ModuleManager $moduleManager,
        RendererInterface $renderer,
        RequestInterface $request,
        StorageInterface $cache
    )
    {
        $this->sm = $sm;
        $this->request = $request;

        /** @var \Zend\ModuleManager\ModuleManager moduleManager */
        $this->moduleManager = $moduleManager;
        $this->renderer = $renderer;
        $this->entityManager = $em;
        $this->config = $config;
        $this->cache = $cache;
    }

    public function getNewEntity($pluginName)
    {
        $viewData = $this->getPluginViewData($pluginName, -1, new Request());
        return $viewData;
    }

    public function getPluginByInstanceId($pluginInstanceId)
    {
        $cacheId = 'rcmPluginInstance_' . $pluginInstanceId;

        if ($this->cache->hasItem($cacheId)) {
            $return = $this->cache->getItem($cacheId);
            $return['fromCache'] = true;
            return $return;
        }

        $pluginInstance = $this->getInstanceEntity($pluginInstanceId);

        if (empty($pluginInstance)) {
            throw new PluginInstanceNotFoundException('Plugin for instanceid '
                . $pluginInstanceId . ' not found.');
        }

        $return = $this->getPluginViewData(
            $pluginInstance->getName(), $pluginInstanceId
        );

        if ($pluginInstance->isSiteWide()) {

            $return['siteWide'] = true;

            $displayName = $pluginInstance->getDisplayName();

            if (!empty($displayName)) {
                $return['displayName'] = $displayName;
            }
        }

        $return['md5'] = $pluginInstance->getMd5();

        if ($return['canCache']) {
            $this->cache->setItem($cacheId, $return);
        }

        return $return;
    }

    public function getPluginViewData($pluginName, $pluginInstanceId)
    {

        /** @var \Rcm\Plugin\PluginInterface $controller */
        $controller = $this->getPluginController($pluginName);

        if (!is_a($controller, '\Rcm\Plugin\PluginInterface')) {
            throw new InvalidPluginException('Plugin '.$controller.' must implement the PluginInterface');
        }

        //If the plugin controller can accept a ZF2 request, pass it
        $controller->setRequest($this->request);

        if ($pluginInstanceId < 0) {
            $viewModel = $controller->renderDefaultInstance($pluginInstanceId);
        } else {
            $viewModel = $controller->renderInstance($pluginInstanceId);
        }

        $headlink = $this->renderer->plugin('headlink');
        $headScript = $this->renderer->plugin('headscript');

        $html = $this->renderer->render($viewModel);
        $css = $headlink->getContainer()->getArrayCopy();
        $js = $headScript->getContainer()->getArrayCopy();


        $return = array(
            'html' => $html,
            'css' => $this->getContainerSrc($css),
            'js' => $this->getContainerSrc($js),
            'editJs' => '',
            'editCss' => '',
            'displayName' => '',
            'tooltip' => '',
            'icon' => '',
            'siteWide' => false,
            'md5' => '',
            'fromCache' => false,
            'canCache' => true,
            'pluginName' => $pluginName,
            'pluginInstanceId' => $pluginInstanceId,
        );


        if (isset($this->config['rcmPlugin'][$pluginName]['editJs'])) {
            $return['editJs']
                = $this->config['rcmPlugin'][$pluginName]['editJs'];
        }

        if (isset($this->config['rcmPlugin'][$pluginName]['editCss'])) {
            $return['editCss']
                = $this->config['rcmPlugin'][$pluginName]['editCss'];
        }

        if (isset($this->config['rcmPlugin'][$pluginName]['display'])) {
            $return['displayName']
                = $this->config['rcmPlugin'][$pluginName]['display'];
        }

        if (isset($this->config['rcmPlugin'][$pluginName]['tooltip'])) {
            $return['tooltip']
                = $this->config['rcmPlugin'][$pluginName]['tooltip'];
        }

        if (isset($this->config['rcmPlugin'][$pluginName]['icon'])) {
            $return['icon'] = $this->config['rcmPlugin'][$pluginName]['icon'];
        }

        if (isset($this->config['rcmPlugin'][$pluginName]['canCache'])) {
            $return['canCache']
                = $this->config['rcmPlugin'][$pluginName]['canCache'];
        }

        return $return;

    }

    public function savePlugin($pluginInstanceId, $saveData)
    {
        $pluginInstance = $this->getInstanceEntity($pluginInstanceId);

        if ($pluginInstance->getMd5() == md5(serialize($saveData))) {
            return $pluginInstance;
        }

        $newPluginInstance = $this->saveNewInstance(
            $pluginInstance->getName(),
            $saveData,
            $pluginInstance->isSiteWide(),
            $pluginInstance->getDisplayName()
        );


        return $newPluginInstance;
    }

    public function deletePluginInstance($pluginInstanceId)
    {
        $pluginInstanceEntity = $this->getInstanceEntity($pluginInstanceId);

        if (empty($pluginInstanceEntity)) {
            throw new PluginInstanceNotFoundException(
                'No plugin found to delete'
            );
        }

        /** @var \Rcm\Plugin\PluginInterface $controller */
        $controller = $this->getPluginController($pluginInstanceEntity->getName());
        $controller->deleteInstance($pluginInstanceEntity->getInstanceId());

        $this->entityManager->remove($pluginInstanceEntity);
        $this->entityManager->flush();
    }

    public function saveNewInstance(
        $pluginName, $saveData, $siteWide = false, $displayName = ''
    )
    {
        $pluginInstance = $this->getNewPluginInstanceEntity($pluginName);

        if ($siteWide) {
            $pluginInstance->setSiteWide();

            if (!empty($displayName)) {
                $pluginInstance->setDisplayName($displayName);
            }
        }

        $pluginInstance->setMd5(md5(serialize($saveData)));

        $this->entityManager->persist($pluginInstance);
        $this->entityManager->flush();

        /** @var \Rcm\Plugin\PluginInterface $controller */
        $controller = $this->getPluginController($pluginName);

        $controller->saveInstance($pluginInstance->getInstanceId(), $saveData);

        return $pluginInstance;
    }

    /**
     * @param string $pluginName
     *
     * @return PluginInstance
     */
    public function getNewPluginInstanceEntity($pluginName)
    {
        $pluginInstance = new PluginInstance();
        $pluginInstance->setPlugin($pluginName);

        if (isset($this->config['rcmPlugin'][$pluginName]['display'])) {
            $pluginInstance->setDisplayName(
                $this->config['rcmPlugin'][$pluginName]['display']
            );
        }

        $this->entityManager->persist($pluginInstance);
        $this->entityManager->flush();

        return $pluginInstance;
    }

    public function getContainerSrc($container)
    {
        if (empty($container) || !is_array($container)) {
            return array();
        }

        $return = array();

        $return = serialize($container);

        $check = unserialize($return);
        print_r($check);
        exit;

        foreach ($container as $item) {

            if ($item->type == 'text/css') {
                $return[] = $item->href;
            } elseif ($item->type == 'text/javascript') {
                if (!empty($item->attributes['src'])) {
                    $return[] = $item->attributes['src'];
                } else {
                    $serialized = serialize($item);
                    $unserialized = unserialize($serialized);

                    print_r($unserialized);
                    exit;
                }

            }
        }

        return $return;
    }

    public function getPluginController($pluginName)
    {
        $this->ensurePluginIsValid($pluginName);

        //Load the plugin controller
        try {
            $pluginController = $this->sm->get($pluginName);
        } catch (\Exception $e) {
            throw new RuntimeException('Unable to get instance of plugin: '.$pluginName, 1, $e);
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
     * @param $pluginName
     *
     * @return bool
     * @throws \Exception
     */
    public function ensurePluginIsValid($pluginName)
    {
        $loadedModules = $this->moduleManager->getLoadedModules();

        if (!isset($loadedModules[$pluginName])) {
            throw new InvalidPluginException(
                "Plugin $pluginName is not loaded or configured. Check
                config/application.config.php"
            );
        }

        return true;
    }

    /**
     * @param $pluginInstanceId
     *
     * @return \Rcm\Entity\PluginInstance
     */
    private function getInstanceEntity($pluginInstanceId)
    {
        return $this->entityManager
            ->getRepository('\Rcm\Entity\PluginInstance')
            ->findOneBy(array('pluginInstanceId' => $pluginInstanceId));
    }
}
