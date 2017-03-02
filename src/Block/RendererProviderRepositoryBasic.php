<?php

namespace Rcm\Block;

use Interop\Container\ContainerInterface;
use Rcm\Core\Repository\AbstractRepository;
use Rcm\Core\Repository\Repository;

/**
 * @GammaRelease
 * Interface RendererProviderRepositoryBasic
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RendererProviderRepositoryBasic extends AbstractRepository implements RendererProviderRepository
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
        $config = $this->configRepository->findOne(['name' => $blockName]);

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
     * findOne
     *
     * @param $blockName
     *
     * @return Renderer
     */
    public function findById($blockName)
    {
        return $this->getProviderService($blockName);
    }
}
