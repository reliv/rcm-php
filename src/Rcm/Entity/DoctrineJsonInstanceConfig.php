<?php

/**
 * Content database Entity
 *
 * This is a Doctrine 2 entity for generic Content
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Content Config base Entity
 *
 * This is a Doctrine 2 entity for generic Content
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_plugin_instance_configs")
 */
class DoctrineJsonInstanceConfig
{
    /**
     * @var integer Plugin instanceId for this content
     *
     * @ORM\Id @ORM\Column(type="integer")
     */
    protected $instanceId;

    /**
     * @var string config that will be stored in the DB as JSON
     *
     * @ORM\Column(type="text")
     */
    protected $config;

    /**
     * Gets the $instanceId property
     *
     * @return string $instanceId
     *
     */
    public function getInstanceId()
    {
        return $this->instanceId;
    }

    /**
     * Gets the config that will be stored in the DB as JSON
     *
     * @return array
     *
     */

    public function getConfig()
    {
        return json_decode($this->config, true);
    }

    /**
     * Sets the $instanceId property
     *
     * @param string $instanceId new value
     *
     * @return null
     *
     */
    public function setInstanceId($instanceId)
    {
        $this->instanceId = $instanceId;
    }

    /**
     * Sets the config that will be stored in the DB as JSON
     *
     * @param array $config new value test
     *
     * @return null
     *
     */
    public function setConfig($config)
    {
        $this->config = json_encode($config);
    }
}
