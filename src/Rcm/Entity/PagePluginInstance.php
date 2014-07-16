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
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Plugin Instances Entity
 *
 * This object contains a list of plugin instances.  See documentation for
 * how our plugins work.
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_page_plugin_instances")
 */
class PagePluginInstance
{

    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $pageInstanceId;

    /**
     * @var integer Layout Placement
     *
     * @ORM\Column(type="integer")
     */
    protected $layoutContainer;

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

    public function __clone()
    {
        if (empty($this->instance)) {
            return;
        }

        if ($this->pageInstanceId) {
            $this->pageInstanceId = null;
        }

        if (!$this->instance->isSiteWide()) {
            $clonedInstance = clone $this->instance;
            $this->instance = $clonedInstance;
        }
    }

    /**
     * Get Instance layout container
     *
     * @return int Layout Container
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

    public function setInstance($instance)
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
     * @param int $pageInstanceId
     */
    public function setPageInstanceId($pageInstanceId)
    {
        $this->pageInstanceId = $pageInstanceId;
    }

    /**
     * @return int
     */
    public function getPageInstanceId()
    {
        return $this->pageInstanceId;
    }

    public function getInstanceId()
    {
        return $this->instance->getInstanceId();
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        //Having floats in here was causing sql errors sometimes when saving
        if (empty($height)) {
            $this->height = null;
        } else {
            $this->height = round($height);
        }
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
        //Having floats in here was causing sql errors sometimes when saving
        if (empty($width)) {
            $this->width = null;
        } else {
            $this->width = round($width);
        }
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