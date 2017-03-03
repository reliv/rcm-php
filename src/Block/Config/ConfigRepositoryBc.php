<?php

namespace Rcm\Block\Config;

use Rcm\Core\Cache\Cache;

/**
 * @GammaRelease
 * Class ConfigRepositoryBc
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigRepositoryBc extends ConfigRepositoryArray implements ConfigRepository
{
    const CACHE_KEY = 'ConfigRepositoryBc';

    /**
     * @var array
     */
    protected $pluginConfig;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var ConfigFields
     */
    protected $configFields;

    /**
     * @var array
     */
    protected $configs = [];

    /**
     * @var ConfigRepositoryJson
     */
    protected $configRepositoryJson;

    /**
     * Constructor.
     *
     * @param                      $pluginConfig
     * @param Cache                $cache
     * @param ConfigFields         $configFields
     * @param ConfigRepositoryJson $configRepositoryJson
     */
    public function __construct(
        $pluginConfig,
        Cache $cache,
        ConfigFields $configFields,
        ConfigRepositoryJson $configRepositoryJson
    ) {
        $this->pluginConfig = $pluginConfig;
        $this->cache = $cache;
        $this->configFields = $configFields;
        $this->configRepositoryJson = $configRepositoryJson;
    }

    /**
     * hasCache
     *
     * @return bool
     */
    protected function hasCache()
    {
        return ($this->cache->hasItem(self::CACHE_KEY));
    }

    /**
     * getCache
     *
     * @return mixed
     */
    protected function getCache()
    {
        return $this->cache->getItem(self::CACHE_KEY);
    }

    /**
     * setCache
     *
     * @param array $configs
     *
     * @return void
     */
    protected function setCache($configs)
    {
        $this->cache->setItem(self::CACHE_KEY, $configs);
    }

    /**
     * getConfigs
     *
     * @return array|mixed
     */
    protected function getConfigs()
    {
        if ($this->hasCache()) {
            return $this->getCache();
        }

        $pluginConfigs = $this->pluginConfig;

        $configs = [];

        foreach ($pluginConfigs as $name => $pluginConfig) {
            $config = $this->configFields->convertBc(
                $name,
                $pluginConfig
            );

            $configs[] = new ConfigBasic($config);
        }

        $newConfigs = $this->configRepositoryJson->find();

        $configs = array_merge($configs, $newConfigs);

        $this->setCache($configs);

        return $configs;
    }
}
