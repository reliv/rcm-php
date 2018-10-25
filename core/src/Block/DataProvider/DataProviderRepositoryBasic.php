<?php

namespace Rcm\Block\DataProvider;

use Interop\Container\ContainerInterface;
use Rcm\Block\Config\Config;
use Rcm\Block\Config\ConfigRepository;

/**
 * @GammaRelease
 * Interface DataProviderRepositoryBasic
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DataProviderRepositoryBasic implements DataProviderRepository
{
    /**
     * @var array
     */
    protected $configRepository;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param ConfigRepository   $configRepository
     * @param ContainerInterface $container
     */
    public function __construct(
        ConfigRepository $configRepository,
        $container
    ) {
        $this->configRepository = $configRepository;
        $this->container = $container;
    }

    /**
     * getProviderService
     *
     * @param string $blockName
     *
     * @return DataProvider
     */
    protected function getProviderService($blockName)
    {
        /** @var Config $config */
        $config = $this->configRepository->findOne(['name' => $blockName]);

        if (empty($config)) {
            return new DataProviderNoop();
        }
        $serviceName = $config->getDataProvider();

        if (empty($serviceName)) {
            return new DataProviderNoop();
        }

        return $this->container->get($serviceName);
    }

    /**
     * findByName
     *
     * @param $blockName
     *
     * @return DataProvider
     */
    public function findByName($blockName)
    {
        return $this->getProviderService($blockName);
    }
}
