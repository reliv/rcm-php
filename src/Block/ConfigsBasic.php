<?php

namespace Rcm\Block;

/**
 * @GammaRelease
 * Class ConfigsBasic
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigsBasic implements Configs
{
    /**
     * @var array
     */
    protected $configs = [];

    /**
     * @var Config
     */
    protected $defaultConfig;

    /**
     * Constructor.
     *
     * @param array $configs
     */
    public function __construct(
        array $configs
    ) {
        $this->configs = $configs;
    }

    /**
     * setConfigs
     *
     * @param array $configs
     *
     * @return void
     */
    protected function setConfigs(array $configs)
    {
        foreach ($configs as $config) {
            $this->add($config);
        }
    }

    /**
     * add
     *
     * @param Config $config
     *
     * @return void
     */
    protected function add(Config $config)
    {
        $this->configs[$config->getName()] = $config;
    }

    /**
     * get
     *
     * @param string $blockName
     * @param null   $default
     *
     * @return mixed|null
     */
    public function get($blockName, $default = null)
    {
        if (!array_key_exists($blockName, $this->configs)) {
            return $default;
        }

        return $this->configs[$blockName];
    }

    /**
     * getAll
     *
     * @return array
     */
    public function getAll()
    {
        return $this->configs;
    }
}
