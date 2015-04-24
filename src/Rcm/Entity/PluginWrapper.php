<?php
/**
 * Plugin Wrapper Entity
 *
 * Plugin Wrapper Entity.  Used for positioning within containers.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plugin Wrapper Entity
 *
 * Plugin Wrapper Entity.  Used for positioning within containers.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity(repositoryClass="Rcm\Repository\PluginWrapper")
 * @ORM\Table(name="rcm_plugin_wrappers")
 */
class PluginWrapper implements \JsonSerializable, \IteratorAggregate
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $pluginWrapperId;

    /**
     * @var integer Layout Placement.  This is used only for Page Containers.
     *
     * @ORM\Column(type="string")
     */
    protected $layoutContainer = null;

    /**
     * @var integer Order of Layout Placement
     *
     * @ORM\Column(type="integer")
     */
    protected $renderOrder = 0;

    /**
     * @var integer Order of Layout Placement
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $height = null;

    /**
     * @var integer Order of Layout Placement
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $width = null;

    /**
     * @var int Row Number
     *
     * @ORM\Column(type="integer", options={"default":0})
     */
    protected $rowNumber = 0;

    /**
     * @var string Column CSS Class
     *
     * @ORM\Column(type="string", options={"default":"col-sm-12"})
     */
    protected $columnClass = 'col-sm-12';

    /**
     * @var integer Order of Layout Placement
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $divFloat = 'left';

    /**
     * @var \Rcm\Entity\PluginInstance
     *
     * @ORM\ManyToOne(
     *     targetEntity="PluginInstance",
     *     fetch="EAGER",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(
     *      name="pluginInstanceId",
     *      referencedColumnName="pluginInstanceId",
     *      onDelete="CASCADE"
     * )
     **/
    protected $instance;

    /**
     * __clone
     *
     * @return void
     */
    public function __clone()
    {
        if (!$this->pluginWrapperId) {
            return;
        }

        $this->pluginWrapperId = null;

        if (!$this->instance->isSiteWide()) {
            $pluginInstance = clone $this->instance;
            $this->instance = $pluginInstance;
        }
    }

    /**
     * Set the Plugin Wrapper ID.  This was added for unit testing and
     * should not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own.
     *
     * @param int $pluginWrapperId Plugin Wrapper ID
     *
     * @return void
     */
    public function setPluginWrapperId($pluginWrapperId)
    {
        $this->pluginWrapperId = $pluginWrapperId;
    }

    /**
     * Get the Plugin Wrapper ID
     *
     * @return int
     */
    public function getPluginWrapperId()
    {
        return $this->pluginWrapperId;
    }

    /**
     * Get Instance layout container
     *
     * @return int
     */
    public function getLayoutContainer()
    {
        return $this->layoutContainer;
    }

    /**
     * Set Layout Container.. Or Container that this plugin should display in.
     * These are defined in the PageLayouts and displayed using a view helper.
     *
     * @param int $container The container ID number to display in.
     *
     * @return null
     */
    public function setLayoutContainer($container)
    {
        $this->layoutContainer = $container;
    }

    /**
     * Get Order number to render instances that have the same container
     *
     * @return int Order to render Plugin Instance
     */
    public function getRenderOrderNumber()
    {
        return (int) $this->renderOrder;
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
        $this->renderOrder = (int) $order;
    }

    /**
     * Set the plugin instance to be wrapped
     *
     * @param PluginInstance $instance Instance to wrap
     *
     * @return void
     */
    public function setInstance(PluginInstance $instance)
    {
        $this->instance = $instance;
    }

    /**
     * Get the Wrapped Plugin Instance
     *
     * @return \Rcm\Entity\PluginInstance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @deprecated
     * Set the height of the plugin html container
     *
     * @param int $height Height for HTML Container
     *
     * @return void
     */
    public function setHeight($height)
    {
        $this->height = round($height);
    }

    /**
     * @deprecated
     * Get the height for the HTML plugin container
     *
     * @return int
     */
    public function getHeight()
    {
        if (empty($this->height)) {
            return null;
        }

        return $this->height . 'px';
    }

    /**
     * @deprecated
     * Set the width for the html plugin container.
     *
     * @param int $width Width for the Html Plugin Container
     *
     * @return void
     */
    public function setWidth($width)
    {
        $this->width = round($width);
    }

    /**
     * @deprecated
     * Get the width for the html plugin container
     *
     * @return int
     */
    public function getWidth()
    {
        if (empty($this->width)) {
            return null;
        }

        return $this->width . 'px';
    }

    /**
     * @deprecated
     * Set the float of the HTML plugin container
     *
     * @param string $divFloat Float left, right, none
     *
     * @return void
     */
    public function setDivFloat($divFloat)
    {
        $this->divFloat = $divFloat;
    }

    /**
     * @deprecated
     * Get the float for the HTML plugin container
     *
     * @return string
     */
    public function getDivFloat()
    {
        return $this->divFloat;
    }

    /**
     * setRowNumber
     *
     * @param int $rowNumber
     *
     * @return void
     */
    public function setRowNumber($rowNumber)
    {
        $this->rowNumber = (int) $rowNumber;
    }

    /**
     * getRowNumber
     *
     * @return int
     */
    public function getRowNumber()
    {
        return (int) $this->rowNumber;
    }

    /**
     * setColumnClass
     *
     * @param string $columnClass
     *
     * @return void
     */
    public function setColumnClass($columnClass)
    {
        $this->columnClass = trim((string) $columnClass);
    }

    /**
     * getColumnClass
     *
     * @return string
     */
    public function getColumnClass()
    {
        return trim((string) $this->columnClass);
    }

    /**
     * populate
     *
     * @param array $data
     *
     * @return void
     */
    public function populate($data)
    {
        if (isset($data['layoutContainer'])) {
            $this->setLayoutContainer($data['layoutContainer']);
        }

        if (isset($data['renderOrder'])) {
            $this->setRenderOrderNumber($data['renderOrder']);
        }

        if (isset($data['rowNumber'])) {
            $this->setRowNumber($data['rowNumber']);
        }

        if (isset($data['columnClass'])) {
            $this->setColumnClass($data['columnClass']);
        }

        if (isset($data['instance']) && $data['instance'] instanceof PluginInstance) {
            $this->setInstance($data['instance']);
        }
    }

    /**
     * jsonSerialize
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * getIterator
     *
     * @return array|Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
