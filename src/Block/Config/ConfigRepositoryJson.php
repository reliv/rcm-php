<?php

namespace Rcm\Block\Config;

use Rcm\Core\Cache\Cache;

/**
 * @GammaRelease
 * Class ConfigRepositoryJson
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigRepositoryJson extends ConfigRepositoryArray implements ConfigRepository
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
        $cache,
        ConfigFields $configFields
    ) {
        $this->registryConfig = $registryConfig;
        $this->cache = $cache;
        $this->configFields = $configFields;
    }

    /**
     * @return bool
     * @throws \Zend\Cache\Exception\ExceptionInterface
     */
    protected function hasCache()
    {
        return ($this->cache->hasItem(self::CACHE_KEY));
    }

    /**
     * @return mixed
     * @throws \Zend\Cache\Exception\ExceptionInterface
     */
    protected function getCache()
    {
        return $this->cache->getItem(self::CACHE_KEY);
    }

    /**
     * @param $configs
     *
     * @return void
     * @throws \Zend\Cache\Exception\ExceptionInterface
     */
    protected function setCache($configs)
    {
        $this->cache->setItem(self::CACHE_KEY, $configs);
    }

    /**
     * @return array|mixed
     * @throws \Zend\Cache\Exception\ExceptionInterface
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
     * @param array $blockPaths
     *
     * @return array
     * @throws \Exception
     */
    protected function readConfigs(array $blockPaths)
    {
        $pluginConfigs = [];

        foreach ($blockPaths as $blockPath) {
            $pluginDir = $blockPath;
            $configFileName = $pluginDir . '/block.json';
            $this->assertPathValid($configFileName);
            $configFileContents = file_get_contents($configFileName);
            $config = json_decode($configFileContents, true, 512, JSON_BIGINT_AS_STRING);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Received invalid JSON for: ' . $configFileName);
            }
            $config['directory'] = realpath($pluginDir);
            $pluginConfigs[$config['name']] = $config;
        }

        return $pluginConfigs;
    }

    /**
     * @param string $path
     *
     * @return void
     * @throws \Exception
     */
    protected function assertPathValid(string $path)
    {
        if(empty(realpath($path))) {
            throw new \Exception(
                'Path is not valid: ' . $path
            );
        }
    }
}
