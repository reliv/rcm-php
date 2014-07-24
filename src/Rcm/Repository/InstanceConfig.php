<?php
namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;

class InstanceConfig extends EntityRepository implements PluginStorageRepoInterface
{
    /**
     * @param $instanceId
     *
     * @return array
     */
    public function select($instanceId)
    {
        /**
         * @var \Rcm\Entity\InstanceConfig
         */
        $entity = $this->selectEntity($instanceId);
        if (!is_object($entity)) {
            return array();
        }
        return $entity->getConfig();
    }

    /**
     * @param $instanceId
     * @param $configData
     */
    public function insert($instanceId, $configData)
    {
        $entity = new \Rcm\Entity\InstanceConfig();
        $entity->setInstanceId($instanceId);
        $entity->setConfig($configData);
        $this->_em->persist($entity);
        $this->_em->flush($entity);
    }

    /**
     * @param $instanceId
     */
    public function delete($instanceId)
    {
        $entity = $this->selectEntity($instanceId);
        if (is_object($entity)) {
            $this->_em->remove($entity);
            $this->_em->flush();
        }
    }

    /**
     * @param $instanceId
     *
     * @return mixed
     */
    public function selectEntity($instanceId)
    {
        $instanceConfig = $this
            ->findOneBy(array('instanceId' => $instanceId));
        return $instanceConfig;
    }
}
