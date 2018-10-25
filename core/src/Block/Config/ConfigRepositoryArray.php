<?php

namespace Rcm\Block\Config;

use Rcm\Core\Repository\AbstractRepository;

/**
 * Class ConfigRepositoryArray
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
abstract class ConfigRepositoryArray extends AbstractRepository
{
    /**
     * getConfigs
     *
     * @return array of Config
     */
    abstract protected function getConfigs();

    /**
     * search
     *
     * @param array $criteria
     *
     * @return array
     */
    protected function search(array $criteria = [])
    {
        $configs = $this->getConfigs();

        $result = [];

        foreach ($configs as $config) {
            if ($this->filter($config, $criteria)) {
                $result[] = $config;
            }
        }

        return $result;
    }

    /**
     * filter
     *
     * @param Config $config
     * @param array $criteria
     *
     * @return bool
     */
    protected function filter(Config $config, array $criteria = [])
    {
        $count = count($criteria);
        $default = new \stdClass();
        $countResult = 0;
        foreach ($criteria as $key => $value) {
            $configValue = $config->get($key, $default);
            if ($configValue === $value) {
                $countResult++;
            }
        }

        return ($countResult === $count);
    }

    /**
     * find
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return array
     * @throws \Exception
     */
    public function find(array $criteria = [], array $orderBy = null, $limit = null, $offset = null)
    {
        // @todo implement these
        if ($orderBy !== null || $limit !== null || $offset !== null) {
            throw new \Exception('orderBy, limit and offset not yet implemented');
        }

        if (empty($criteria)) {
            return $this->getConfigs();
        }

        return $this->search($criteria);
    }

    /**
     * findOne
     *
     * @param array $criteria
     *
     * @return Config|null
     */
    public function findOne(array $criteria = [])
    {
        $result = $this->search($criteria);

        if (count($result) > 0) {
            return $result[0];
        }

        return null;
    }

    /**
     * @param int $name
     * @return Config|null
     */
    public function findById($id)
    {
        return $this->findOne(['name' => $id]);
    }
}
