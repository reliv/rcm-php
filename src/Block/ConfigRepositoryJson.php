<?php

namespace Rcm\Block;

use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Rcm\Core\Cache\Cache;
use Rcm\Core\Repository\AbstractRepository;

/**
 * @GammaRelease
 * Class ConfigRepositoryJson
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigRepositoryJson extends AbstractRepository implements ConfigRepository
{
    const CACHE_KEY = 'ConfigRepositoryJson';
    /**
     * @var array
     */
    protected $registryConfig;

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
     * Constructor.
     *
     * @param array        $registryConfig
     * @param Cache        $cache
     * @param ConfigFields $configFields
     */
    public function __construct(
        $registryConfig,
        Cache $cache,
        ConfigFields $configFields
    ) {
        $this->registryConfig = $registryConfig;
        $this->cache = $cache;
        $this->configFields = $configFields;
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

        $pluginConfigs = $this->readConfigs(
            $this->registryConfig
        );

        $configs = [];

        foreach ($pluginConfigs as $pluginConfig) {
            $config = $this->configFields->prepare(
                $pluginConfig
            );

            $configs[] = new ConfigBasic($config);
        }

        $this->setCache($configs);

        return $configs;
    }

    /**
     * readConfigs
     *
     * @param array $blockPaths
     *
     * @return array
     */
    protected function readConfigs(array $blockPaths)
    {
        $pluginConfigs = [];

        foreach ($blockPaths as $blockPath) {
            $pluginDir = $blockPath;
            $configFileName = $pluginDir . '/block.json';
            $configFileContents = file_get_contents($configFileName);
            $config = json_decode($configFileContents, true, 512, JSON_BIGINT_AS_STRING);
            $config['directory'] = $pluginDir;
            $config['template']['path'] = 'template';
            $pluginConfigs[$config['name']] = $config;
        }

        return $pluginConfigs;
    }

    /**
     * search
     *
     * @param array $criteria
     *
     * @return array
     */
    protected function search(array $criteria = [])
    {
        $configs = $this->getConfigs();

        $result = [];

        foreach ($configs as $config) {
            if ($this->filter($config, $criteria)) {
                $result[] = $config;
            }
        }

        return $result;
    }

    /**
     * filter
     *
     * @param Config $config
     * @param array  $criteria
     *
     * @return bool
     */
    protected function filter(Config $config, array $criteria = [])
    {
        $count = count($criteria);
        $default = new stdClass();
        $countResult = 0;
        foreach ($criteria as $key => $value) {
            $configValue = $config->get($key, $default);
            if ($configValue === $value) {
                $countResult++;
            }
        }

        return ($countResult === $count);
    }

    /**
     * find
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     *
     * @return array|mixed
     */
    public function find(array $criteria = [], array $orderBy = null, $limit = null, $offset = null)
    {
        if (empty($criteria)) {
            return $this->getConfigs();
        }

        return $this->search($criteria);
    }

    /**
     * findOne
     *
     * @param array $criteria
     *
     * @return Config|null
     */
    public function findOne(array $criteria = [])
    {
        $result = $this->search($criteria);

        if (count($result) > 0) {
            return $result[0];
        }

        return null;
    }

}
