<?php

namespace Rcm\Block;

use Interop\Container\ContainerInterface;

/**
 * Interface RendererProviderRepository
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RendererProviderRepository
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $defaultRenderServiceName;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param array              $renderProviderConfig
     * @param string             $defaultRenderServiceName
     * @param ContainerInterface $container
     */
    public function __construct(
        $renderProviderConfig,
        $defaultRenderServiceName,
        $container
    ) {
        $this->config = $renderProviderConfig;

        $this->defaultRenderServiceName = $defaultRenderServiceName;

        $this->container = $container;
    }

    /**
     * getProviderService
     *
     * @param string $pluginName
     *
     * @return Renderer
     */
    protected function getProviderService($pluginName)
    {
        $serviceName = $this->defaultRenderServiceName;
        if (array_key_exists($pluginName, $this->config)) {
            $serviceName = $this->config[$pluginName];
        }

        return $this->container->get($serviceName);
    }

    /**
     * findOne
     *
     * @param $pluginName
     *
     * @return Renderer
     */
    public function findById($pluginName)
    {
        return $this->getProviderService($pluginName);
    }
}
