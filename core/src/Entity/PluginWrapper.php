<?php

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rcm\Tracking\Model\Tracking;

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
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="rcm_plugin_wrappers")
 */
class PluginWrapper extends ApiModelTrackingAbstract implements \JsonSerializable, \IteratorAggregate, Tracking
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
     * @ORM\Column(type="string", nullable=true)
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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $pluginInstanceId;

    /**
     * @deprecated This was sometimes-inaccurate system was replaced by the immutably history system
     *
     * @var \DateTime Date object was first created
     *
     */
    protected $createdDate;

    /**
     * @deprecated This was sometimes-inaccurate system was replaced by the immutably history system
     *
     * @var string User ID of creator
     *
     */
    protected $createdByUserId;

    /**
     * @deprecated This was sometimes-inaccurate system was replaced by the immutably history system
     *
     * @var string Short description of create reason
     *
     */
    protected $createdReason = Tracking::UNKNOWN_REASON;

    /**
     * @deprecated This was sometimes-inaccurate system was replaced by the immutably history system
     *
     * @var \DateTime Date object was modified
     *
     */
    protected $modifiedDate;

    /**
     * @deprecated This was sometimes-inaccurate system was replaced by the immutably history system
     *
     * @var string User ID of modifier
     *
     */
    protected $modifiedByUserId;

    /**
     * @deprecated This was sometimes-inaccurate system was replaced by the immutably history system
     *
     * @var string Short description of create reason
     *
     */
    protected $modifiedReason = Tracking::UNKNOWN_REASON;

    /**
     * @param string $createdByUserId <tracking>
     * @param string $createdReason   <tracking>
     */
    public function __construct(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        parent::__construct($createdByUserId, $createdReason);
    }

    /**
     * Get a clone with special logic
     *
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return PluginWrapper
     */
    public function newInstance(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        /** @var PluginWrapper $new */
        $new = parent::newInstance(
            $createdByUserId,
            $createdReason
        );

        // if no id, then it has not been save and can be returned
        if (empty($new->pluginWrapperId)) {
            return $new;
        }

        $new->pluginWrapperId = null;

        // @deprecated <deprecated-site-wide-plugin>
        //if (!$new->instance->isSiteWide()) {
            $pluginInstance = $new->instance->newInstance(
                $createdByUserId,
                $createdReason
            );
            $new->instance = $pluginInstance;
        //}

        return $new;
    }

    /**
     * @deprecated Don NOT use this
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
     * @return void
     */
    public function setLayoutContainer($container)
    {
        $this->layoutContainer = $container;
    }

    /**
     * Get Order number to render instances that have the same container
     *
     * @return int
     */
    public function getRenderOrder()
    {
        return (int)$this->renderOrder;
    }

    /**
     * Set the order number to render instances that have the same container.
     *
     * @param $renderOrder
     *
     * @return void
     */
    public function setRenderOrder($renderOrder)
    {
        $this->renderOrder = (int)$renderOrder;
    }

    /**
     * @deprecated Use getRenderOrder()
     * Get Order number to render instances that have the same container
     *
     * @return int Order to render Plugin Instance
     */
    public function getRenderOrderNumber()
    {
        return $this->getRenderOrder();
    }

    /**
     * @deprecated Use setRenderOrder()
     * Set the order number to render instances that have the same container.
     *
     * @param int $order Order to display in.
     *
     * @return void
     */
    public function setRenderOrderNumber($order)
    {
        $this->setRenderOrder($order);
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
        $this->pluginInstanceId = $instance->getInstanceId();
    }

    /**
     * Get the Wrapped Plugin Instance
     *
     * @return PluginInstance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * getInstanceId
     *
     * @return int|null
     */
    public function getPluginInstanceId()
    {
        return $this->pluginInstanceId;
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
        $this->rowNumber = (int)$rowNumber;
    }

    /**
     * getRowNumber
     *
     * @return int
     */
    public function getRowNumber()
    {
        return (int)$this->rowNumber;
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
        $this->columnClass = trim((string)$columnClass);
    }

    /**
     * getColumnClass
     *
     * @return string
     */
    public function getColumnClass()
    {
        return trim((string)$this->columnClass);
    }

    /**
     * populate
     *
     * @param array $data
     * @param array $ignore
     *
     * @return void
     */
    public function populate(array $data, array $ignore = ['createdByUserId', 'createdDate', 'createdReason'])
    {
        if (isset($data['layoutContainer'])
            && !in_array(
                'layoutContainer',
                $ignore
            )
        ) {
            $this->setLayoutContainer($data['layoutContainer']);
        }

        // @bc
        if (isset($data['renderOrder']) && !in_array('renderOrder', $ignore)) {
            $this->setRenderOrder($data['renderOrder']);
        }

        if (isset($data['rowNumber']) && !in_array('rowNumber', $ignore)) {
            $this->setRowNumber($data['rowNumber']);
        }

        if (isset($data['columnClass']) && !in_array('columnClass', $ignore)) {
            $this->setColumnClass($data['columnClass']);
        }

        if (isset($data['instance']) && $data['instance'] instanceof PluginInstance
            && !in_array('instance', $ignore)
        ) {
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
     * @return array|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return array
     */
    public function toArray($ignore = [])
    {
        $data = parent::toArray($ignore);

        return $data;
    }

    /**
     * <tracking>
     *
     * @return void
     *
     * @ORM\PrePersist
     */
    public function assertHasTrackingData()
    {
        parent::assertHasTrackingData();
    }

    /**
     * <tracking>
     *
     * @return void
     *
     * @ORM\PreUpdate
     */
    public function assertHasNewModifiedData()
    {
        parent::assertHasNewModifiedData();
    }
}
