<?php

namespace Rcm\Block;

use Rcm\Core\Cache\Cache;
use Rcm\Core\Repository\AbstractRepository;
use Rcm\Core\Repository\Repository;

/**
 * @GammaRelease
 * Class ConfigRepositoryJson
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigRepositoryJson extends AbstractRepository implements Repository
{
    /**
     * @var array
     */
    protected $registryConfig;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @param       $registryConfig
     * @param Cache $cache
     */
    public function __construct(
        $registryConfig,
        Cache $cache
    ) {
        $this->registryConfig = $registryConfig;
        $this->cache = $cache;
    }

    protected function getArray()
    {

    }

    public function getConfig()
    {
        $pluginConfigs = $this->readConfigs(
            $this->registryConfig
        );

        $zfConfig['omega-block-adaptor']['plugins'] = [];
        foreach ($pluginConfigs as $pluginConfig) {
            $zfConfig['omega-block-adaptor']['plugins'][$pluginConfig['name']] = $pluginConfig;
            $zfConfig = array_merge_recursive($zfConfig, $this->omegaSinglePluginConfigToZfConfig($pluginConfig));
        }

        return $zfConfig;
    }

    public static function omegaSinglePluginConfigToZfConfig($pluginConfig)
    {
        $config = [
            'rcmPlugin' => [],
            'service_manager' => []
        ];
        $config['rcmPlugin'][$pluginConfig['name']] = [
            'type' => $pluginConfig['category'],
            'display' => $pluginConfig['label'],
            'tooltip' => $pluginConfig['description'],
            'canCache' => $pluginConfig['cache'],
            'defaultInstanceConfig'
            => self::fieldsToConfig($pluginConfig['fields'])
        ];
        $config['service_manager']['factories'][$pluginConfig['name']]
            = OmegaAdaptorPluginControllerFactory::class;

        return $config;
    }

    public static function fieldsToConfig($instanceConfigFields)
    {
        $instanceConfig = [];
        foreach ($instanceConfigFields as $field) {
            $instanceConfig[$field['name']] = $field['default'];
        }

        return $instanceConfig;
    }

    public static function readConfigs($blockPaths)
    {
        $pluginConfigs = [];

        foreach ($blockPaths as $blockPath) {
            $pluginDir = $blockPath;
            $configFileName = $pluginDir . '/block.json';
            $configFileContents = file_get_contents($configFileName);
            $config = json_decode($configFileContents, true, 512, JSON_BIGINT_AS_STRING);
            $config['dir'] = $pluginDir;
            $config['template']['path'] = 'template';
            $pluginConfigs[$config['name']] = $config;
        }

        return $pluginConfigs;
    }

    public function find(array $criteria = [], array $orderBy = null, $limit = null, $offset = null)
    {
        // @todo Honor criteria


    }
}
