<?php

namespace Rcm\Block;

use Interop\Container\ContainerInterface;

/**
 * Interface BlockDataProviderRepository
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ProviderRepository
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param array              $pluginProviderConfig
     * @param ContainerInterface $container
     */
    public function __construct(
        $pluginProviderConfig,
        $container
    ) {
        $this->config = $pluginProviderConfig;
        $this->container = $container;
    }

    /**
     * getProviderService
     *
     * @param string $pluginName
     *
     * @return Provider
     */
    protected function getProviderService($pluginName)
    {
        if (!array_key_exists($pluginName, $this->config)) {
            return new ProviderNoop();
        }

        return $this->container->get($this->config[$pluginName]);
    }

    /**
     * findOne
     *
     * @param $pluginName
     *
     * @return Provider
     */
    public function findById($pluginName)
    {
        return $this->getProviderService($pluginName);
    }
}
