<?php

namespace Rcm\Block;

/**
 * @GammaRelease
 * Class ConfigsServiceBc
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ConfigsService
{
    /**
     * @var ConfigProvider
     */
    protected $configProvider;

    /**
     * Constructor.
     *
     * @param ConfigProvider $configProvider
     */
    public function __construct(
        ConfigProvider $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    /**
     * getConfigs
     *
     * @return ConfigsBasic
     */
    public function getConfigs()
    {
        $configs = $this->configProvider->__invoke();

        return new ConfigsBasic(
            $configs
        );
    }
}
