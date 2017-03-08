<?php

namespace Rcm\Block\Renderer;

use Interop\Container\ContainerInterface;
use Rcm\Block\Config\Config;
use Rcm\Block\Config\ConfigRepository;

/**
 * @GammaRelease
 * Interface RendererRepositoryBasic
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RendererRepositoryBasic implements RendererRepository
{
    /**
     * @var array
     */
    protected $configRepository;

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
     * @param ConfigRepository $configRepository
     * @param                  $defaultRenderServiceName
     * @param                  $container
     */
    public function __construct(
        ConfigRepository $configRepository,
        $defaultRenderServiceName,
        $container
    ) {
        $this->configRepository = $configRepository;

        $this->defaultRenderServiceName = $defaultRenderServiceName;

        $this->container = $container;
    }

    /**
     * getProviderService
     *
     * @param string $blockName
     *
     * @return Renderer
     */
    protected function getProviderService($blockName)
    {
        /** @var Config $config */
        $config = $this->configRepository->findById($blockName);

        if (empty($config)) {
            return $this->container->get($this->defaultRenderServiceName);
        }

        $serviceName = $config->getRenderer();

        if (empty($serviceName)) {
            return $this->container->get($this->defaultRenderServiceName);
        }

        return $this->container->get($serviceName);
    }

    /**
     * findByName
     *
     * @param $blockName
     *
     * @return Renderer
     */
    public function findByName($blockName)
    {
        return $this->getProviderService($blockName);
    }
}
