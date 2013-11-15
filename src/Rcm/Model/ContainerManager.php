<?php

namespace Rcm\Model;


use Doctrine\ORM\EntityManager;
use Rcm\Exception\PluginInstanceNotFoundException;
use Rcm\Interfaces\PluginManagerInterface;
use RcmDoctrineJsonPluginStorage\Exception\PluginDataNotFoundException;
use Zend\Cache\Storage\StorageInterface;

class ContainerManager
{

    protected $entityManager;

    /**
     * @var \Rcm\Interfaces\PluginManagerInterface
     */
    protected $pluginManager;

    /**
     * @var \Zend\Cache\Storage\StorageInterface
     */
    protected $cache;

    public function __construct(
        EntityManager $em,
        PluginManagerInterface $pluginManager,
        StorageInterface $cache
    ) {
        $this->entityManager = $em;
        $this->pluginManager = $pluginManager;
        $this->cache = $cache;
    }

    public function getPageContainer($containerId)
    {
        $cacheId = 'rcmContainer_'.$containerId;

        if ($this->cache->hasItem($cacheId)) {
            $return = $this->cache->getItem($cacheId);
            $return['fromCache'] = true;
            return $return;
        }

        $containerData = $this->getContainerPlugins($containerId);

        if (empty($containerData['containerPlugins']))
        {
            return array(
                'containerPlugins' => array(),
                'fromCache' => false,
                'canCache' => false,
            );
        }

        $canCache = true;

        foreach ($containerData['containerPlugins'] as $key => $containerPlugin)
        {
            // Untestable code.  DB should always require a plugin instance ID, but because it comes from an outside
            // data store we need to make sure it's here.
            // @codeCoverageIgnoreStart
            if (empty($containerPlugin['plugin_instance_id'])) {
                throw new PluginInstanceNotFoundException('No Plugin Instance ID found for container Plugin '
                    .$containerPlugin['containerPluginId']
                );
            }
            // @codeCoverageIgnoreEnd

            $pluginData = $this->pluginManager->getPluginByInstanceId(
                $containerPlugin['plugin_instance_id']
            );

            if (empty($pluginData)) {
                throw new PluginDataNotFoundException('No data for plugin returned from the Plugin Manager'
                    .'for container plugin'
                    .$containerPlugin['containerPluginId']
                );
            }

            if (!$pluginData['canCache']) {
                $canCache = false;
            }

            $containerData['containerPlugins'][$key]['pluginData'] = $pluginData;
        }

        $container = array(
            'containerPlugins' => $containerData['containerPlugins'],
            'fromCache' => false,
            'canCache' => $canCache
        );

        if ($canCache) {
            $this->cache->setItem($cacheId, $container);
        }

        return $container;
    }

    public function getContainerPlugins($containerId)
    {

        $cacheId = 'rcmContainerPluginWrapper_'.$containerId;

        if ($this->cache->hasItem($cacheId)) {
            $return = $this->cache->getItem($cacheId);
            $return['fromCache'] = true;
            return $return;
        }

        $query = $this->entityManager->createQuery('
            SELECT cp.containerPluginId,
              cp.container,
              cp.renderOrder,
              cp.height,
              cp.width,
              cp.divFloat,
              ip.instanceId as plugin_instance_id
            FROM \Rcm\Entity\ContainerPlugin cp
            JOIN cp.instance ip
            WHERE cp.container = :containerId
            ORDER BY cp.renderOrder
        ');

        $query->setParameter('containerId', $containerId);

        $result = $query->getArrayResult();

        if (empty($result)) {
            return array(
                'containerPlugins' => array(),
                'fromCache' => false
            );
        }

        $fixedReturn = array(
            'containerPlugins' => $result,
            'fromCache' => false,
        );

        $this->cache->setItem($cacheId, $fixedReturn);

        return $fixedReturn;
    }

    /**
     * Used for Unit testing to swap out the plugin manager with different mock objects
     *
     * @param PluginManagerInterface $pluginManager
     */
    public function setPluginManager(PluginManagerInterface $pluginManager)
    {
        $this->pluginManager = $pluginManager;
    }
}