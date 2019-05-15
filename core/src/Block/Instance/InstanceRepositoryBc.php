<?php

namespace Rcm\Block\Instance;

use Doctrine\ORM\EntityManager;
use Rcm\Block\Config\ConfigRepository;
use Rcm\Core\Repository\AbstractRepository;
use Rcm\Entity\PluginInstance;

/**
 * @GammaRelease
 * Class BlockRepository
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class InstanceRepositoryBc extends AbstractRepository implements InstanceRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $pluginInstanceRepository;

    /**
     * @var ConfigRepository
     */
    protected $blockConfigRepository;

    /**
     * @var InstanceConfigMerger
     */
    protected $instanceConfigMerger;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager,
        ConfigRepository $blockConfigRepository,
        InstanceConfigMerger $instanceConfigMerger
    ) {
        $this->pluginInstanceRepository = $entityManager->getRepository(PluginInstance::class);
        $this->blockConfigRepository = $blockConfigRepository;
        $this->instanceConfigMerger = $instanceConfigMerger;
    }

    /**
     * getNew
     *
     * @param $id
     * @param $config
     * @param $data // NOT USED
     *
     * @return Instance
     */
    public function getNew($id, $name, $config, $data)
    {
        return new InstanceBasic($id, $name, $config);
    }

    /**
     * @param int $id
     *
     * @return null|Instance|\stdClass
     * @throws \Exception
     */
    public function findById($id)
    {
        /** @var PluginInstance $pluginInstance */
        $pluginInstance = $this->pluginInstanceRepository->find($id);

        if (empty($pluginInstance)) {
            return null;
        }

        $configFromDb = $pluginInstance->getInstanceConfig();

        $blockConfig = $this->blockConfigRepository->findById($pluginInstance->getPlugin());

        if (empty($blockConfig)) {
            $pluginInstance->setPlugin('MissingBlock');
            $blockConfig = $this->blockConfigRepository->findById('MissingBlock');
        }

        $defaultConfig = $blockConfig->getDefaultConfig();
        $mergedConfig = $this->instanceConfigMerger->__invoke($defaultConfig, $configFromDb);

        $blockInstance = $this->getNew(
            $id,
            $pluginInstance->getPlugin(),
            $mergedConfig,
            []
        );

        return $blockInstance;
    }
}
