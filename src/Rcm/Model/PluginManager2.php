<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rmcnew
 * Date: 10/30/12
 * Time: 11:10 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Rcm\Model;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\PluginInstance;
use Rcm\Exception\InvalidPluginException;
use Rcm\Exception\PluginInstanceNotFoundException;
use Rcm\Interfaces\PluginManagerInterface;
use Zend\Cache\Storage\StorageInterface;
use Zend\Config\Config;
use Zend\Http\Request;
use Zend\ModuleManager\ModuleManager;
use \Rcm\Plugin\PluginInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Renderer\RendererInterface;

class PluginManager2 implements PluginManagerInterface
{
    protected $sm;
    protected $moduleManager;
    protected $request;
    protected $renderer;
    protected $entityManager;
    protected $config;
    protected $cache;

    public function __construct(
        EntityManager $em,
        $config,
        ServiceManager $sm,
        ModuleManager $moduleManager,
        RendererInterface $renderer,
        Request $request,
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

    public function getPluginByInstanceId($instanceId)
    {
        $cacheId = 'rcmPluginInstance_' . $instanceId;

        if ($this->cache->hasItem($cacheId)) {
            $return = $this->cache->getItem($cacheId);
            $return['fromCache'] = true;
            return $return;
        }

        $pluginInstance = $this->getInstanceEntity($instanceId);

        if (empty($pluginInstance)) {
            throw new PluginInstanceNotFoundException('Plugin for instanceid '
                . $instanceId . ' not found.');
        }

        $return = $this->getPluginViewData(
            $pluginInstance->getName(), $instanceId
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

    public function getPluginViewData($pluginName, $instanceId)
    {

        /** @var \Rcm\Plugin\PluginInterface $controller */
        $controller = $this->getPluginController($pluginName);

        //If the plugin controller can accept a ZF2 request, pass it
        $controller->setRequest($this->request);

        if ($instanceId < 0) {
            $viewModel = $controller->renderDefaultInstance($instanceId);
        } else {
            $viewModel = $controller->renderInstance($instanceId);
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
            'instanceId' => $instanceId,
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

    public function savePlugin($instanceId, $saveData)
    {
        $pluginInstance = $this->getInstanceEntity($instanceId);

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

    public function deletePluginInstance($instanceId)
    {
        $query = $this->entityManager->createQuery(
            '
                        DELETE FROM \Rcm\Entity\PluginInstance pi
                        WHERE pi.instanceId = :instanceId
                    '
        );

        $query->setParameter('instanceId', $instanceId);

        $query->execute();

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

        foreach ($container as $item) {
            if ($item->type == 'text/css') {
                $return[] = $item->href;
            } elseif ($item->type == 'text/javascript') {
                $return[] = $item->attributes['src'];
            }
        }

        return $return;
    }

    public function getPluginController($pluginName)
    {
        $this->ensurePluginIsValid($pluginName);

        //Load the plugin controller
        $pluginController = $this->sm->get($pluginName);

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
     * @param $instanceId
     *
     * @return \Rcm\Entity\PluginInstance
     */
    private function getInstanceEntity($instanceId)
    {
        return $this->entityManager
            ->getRepository("\Rcm\Entity\PluginInstance")
            ->findOneBy(array('instanceId' => $instanceId));
    }
}
