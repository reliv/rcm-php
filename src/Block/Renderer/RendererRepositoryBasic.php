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
     * @var array
     */
    protected $rendererAliases;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param ConfigRepository   $configRepository
     * @param array              $rendererAliases
     * @param ContainerInterface $container
     */
    public function __construct(
        ConfigRepository $configRepository,
        $rendererAliases,
        $container
    ) {
        $this->configRepository = $configRepository;

        $this->rendererAliases = $rendererAliases;

        $this->container = $container;
    }

    /**
     * getProviderService
     *
     * @param string $blockName
     *
     * @return Renderer|null
     */
    protected function getProviderService($blockName)
    {
        /** @var Config $config */
        $config = $this->configRepository->findById($blockName);

        if (empty($config)) {
            return null;
        }

        $alias = $config->getRenderer();

        if (empty($this->rendererAliases[$alias])) {
            return null;
        }

        $serviceName = $this->rendererAliases[$alias];

        if (empty($serviceName)) {
            return null;
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
