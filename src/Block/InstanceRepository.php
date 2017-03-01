<?php

namespace Rcm\Block;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\PluginInstance;

/**
 * Class BlockRepository
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class InstanceRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $pluginInstanceRepository;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->pluginInstanceRepository = $entityManager->getRepository(PluginInstance::class);
    }

    /**
     * getNew
     *
     * @param $id
     * @param $config
     * @param $data
     *
     * @return InstanceBasic
     */
    public function getNew($id, $config, $data)
    {
        return new InstanceBasic($id, $config, $data);
    }

    /**
     * findById
     *
     * @param $id
     *
     * @return null|InstanceBasic
     */
    public function findById($id)
    {
        /** @var PluginInstance $pluginInstance */
        $pluginInstance = $this->pluginInstanceRepository->find($id);

        if (empty($pluginInstance)) {
            return null;
        }

        $config = $pluginInstance->getInstanceConfig();

        $blockInstance = $this->getNew(
            $id,
            $config,
            []
        );

        return $blockInstance;
    }
}
