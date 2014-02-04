<?php
/**
 * Plugin Instances Entity
 *
 * This is a Doctorine 2 definition file for Plugin Instances  This file
 * is used for any module that needs to know about plugin instances.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use \Rcm\Entity\PluginInstance;

/**
 * Plugin Instances Entity
 *
 * This object contains a list of plugin instances.  See documentation for
 * how our plugins work.
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_container_plugin_instances")
 */
class ContainerPlugin
{

    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $containerPluginId;

    /**
     * @var integer Layout Placement
     *
     * @ORM\Column(type="string")
     */
    protected $container;

    /**
     * @var integer Order of Layout Placement
     *
     * @ORM\Column(type="integer")
     */
    protected $renderOrder;

    /**
     * @var integer Order of Layout Placement
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $height;

    /**
     * @var integer Order of Layout Placement
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $width;

    /**
     * @var integer Order of Layout Placement
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $divFloat = 'left';

    /**
     * fetch="EAGER" here is very important for optimizing the number of queries
     *
     * @var \Rcm\Entity\PluginInstance
     *
     * @ORM\ManyToOne(
     *     targetEntity="PluginInstance",
     *     fetch="EAGER",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(
     *      name="instance_id",
     *      referencedColumnName="instanceId",
     *      onDelete="SET NULL"
     * )
     **/
    protected $instance;

    /**
     * Get Instance layout container
     *
     * @return int Layout Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set Layout Container.. Or Container that this plugin should display in.
     * These are defined in the PageLayouts and displayed using a view helper.
     *
     * @param int $container The container ID number to display in.
     *
     * @return null
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * Get Order number to render instances that have the same container
     *
     * @return int Order to render Plugin Instance
     */
    public function getRenderOrderNumber()
    {
        return $this->renderOrder;
    }

    /**
     * Set the order number to render instances that have the same container.
     *
     * @param int $order Order to display in.
     *
     * @return null
     */
    public function setRenderOrderNumber($order)
    {
        $this->renderOrder = $order;
    }

    public function setInstance(PluginInstance $instance)
    {
        $this->instance = $instance;
    }

    /**
     * @return \Rcm\Entity\PluginInstance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param int $containerPluginId
     */
    public function setContainerPluginId($containerPluginId)
    {
        $this->containerPluginId = $containerPluginId;
    }

    /**
     * @return int
     */
    public function getContainerPluginId()
    {
        return $this->containerPluginId;
    }

    /**
     * @return int
     */
    public function getInstanceId()
    {
        return $this->instance->getInstanceId();
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height . 'px';
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width . 'px';
    }

    /**
     * @param string $divFloat
     */
    public function setDivFloat($divFloat)
    {
        $this->divFloat = $divFloat;
    }

    /**
     * @return string
     */
    public function getDivFloat()
    {
        return $this->divFloat;
    }

}