<?php
/**
 * Plugin Asset Entity
 *
 * This is a Doctrine 2 definition file for Plugin Instances. This file is used
 * to track what images, links, ect. that a plugin instance makes reference to
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace Rcm\Entity;

use Zend\Filter\Boolean;

use Zend\XmlRpc\Value\Integer;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Plugin Asset Entity
 *
 * This is a Doctrine 2 definition file for Plugin Instances. This file is used
 * to track what images, links, ect. that a plugin instance makes reference to
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="rcm_plugin_assets",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="url", columns={"url"})}
 * )
 */

class PluginAsset
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $assetId;

    /**
     * @ORM\ManyToMany(targetEntity="PluginInstance")
     * @ORM\JoinTable(
     *     name="rcm_plugin_instances_assets",
     *     joinColumns={@ORM\JoinColumn(
     *         name="assetId", referencedColumnName="assetId")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(
     *             name="instanceId",
     *             referencedColumnName="instanceId"
     *         )
     *     }
     * )
     */
    protected $pluginInstances;

    /**
     * @var string the asset's url
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * Constructor
     *
     * @param string $url asset's url
     * 
     * @return null
     */
    function __construct($url){
        $this->pluginInstances = new ArrayCollection();
        $this->setUrl($url);
    }

    /**
     * @param PluginInstance $pluginInstance plugin instance to add
     *
     * @return null
     */
    function addPluginInstance($pluginInstance){
        if(!$this->pluginInstances->contains($pluginInstance)){
            $this->pluginInstances[] = $pluginInstance;
        }
    }

    /**
     * Get the plugin instances that are using this asset
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPluginInstances()
    {
        return $this->pluginInstances;
    }

    /**
     * Sets the Url property
     *
     * @param string $url
     *
     * @return null
     *
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Gets the Url property
     *
     * @return string Url
     *
     */
    public function getUrl()
    {
        return $this->url;
    }


}