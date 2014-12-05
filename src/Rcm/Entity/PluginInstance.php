<?php
/**
 * Plugin Instances Entity
 *
 * This is a Doctrine 2 definition file for Plugin Instances  This file
 * is used for any module that needs to know about plugin instances.
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
 * Plugin Instances Entity
 *
 * This object contains all the data for a plugin instance.  See documentation for
 * how our plugins work.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity(repositoryClass="Rcm\Repository\PluginInstance")
 * @ORM\Table(name="rcm_plugin_instances")
 */
class PluginInstance implements \JsonSerializable, \IteratorAggregate
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $pluginInstanceId;

    /**
     * @ORM\Column(type="string")
     **/
    protected $plugin;

    /**
     * @var Boolean Site wide instance
     *
     * @ORM\Column(type="boolean")
     */
    protected $siteWide = false;

    /**
     * @var string Name of Site wide Plugin
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $displayName = null;

    /**
     * @var string Md5 of posted data
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $md5 = '';

    /**
     * @var integer Previous Entity
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $previousEntity = null;

    /**
     * @var string config that will be stored in the DB as JSON
     *
     * @ORM\Column(type="text")
     */
    protected $instanceConfig;

    /**
     * @var string Place holder for rendered HTML
     */
    protected $renderedHtml;

    /**
     * @var array Place holder for rendered Css
     */
    protected $renderedCss = [];

    /**
     * @var array Place holder for rendered Js
     */
    protected $renderedJs = [];

    protected $editJs;

    protected $editCss;

    protected $tooltip;

    protected $icon;

    protected $canCache;


    public function __clone()
    {
        if (!$this->pluginInstanceId) {
            return;
        }

        $this->pluginInstanceId = null;
        $this->previousEntity = null;
    }

    /**
     * Get the unique Instance ID
     *
     * @return int
     */
    public function getInstanceId()
    {
        return $this->pluginInstanceId;
    }

    /**
     * Set the ID of the Plugin Instance.  This was added for unit testing and
     * should not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own.
     *
     * @param int $pluginInstanceId Unique Plugin Instance ID
     *
     * @return null
     */
    public function setInstanceId($pluginInstanceId)
    {
        $this->pluginInstanceId = $pluginInstanceId;
    }

    /**
     * Name of the plugin we are wrapping.  This is used to know what class to
     * call when rendering the instance.
     *
     * @return string
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * Set the name of the plugin we are wrapping.  This is used to know what class
     * to call when rendering the instance.
     *
     * @param string $plugin Module Name
     *
     * @return null
     */
    public function setPlugin($plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Set this instance as a site wide plugin instance
     *
     * @return null
     */
    public function setSiteWide()
    {
        $this->siteWide = true;
    }

    /**
     * Is this a site wide plugin
     *
     * @return bool
     */
    public function isSiteWide()
    {
        return $this->siteWide;
    }

    /**
     * Get the name to use for the Site Wide plugin
     *
     * @return string Name of Site Wide plugin
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set the name of the Instance to use as a site wide plugin.
     *
     * @param string $name Name to use for the Site wide plugin
     *
     * @return null
     */
    public function setDisplayName($name)
    {
        $this->displayName = $name;
    }

    /**
     * MD5 of save data.  This is used to figure out if we need to create a new
     * instance when saving a plugin.
     *
     * @param string $md5 MD5 of save data
     *
     * @return void
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;
    }

    /**
     * Current MD5 of save data. This is used to figure out if we need to create a
     * new instance when saving a plugin.
     *
     * @return string
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * setSaveData
     *
     * @param $saveData
     *
     * @return void
     */
    public function setSaveData($saveData)
    {
        $this->setMd5(md5(serialize($saveData)));
        $this->setInstanceConfig($saveData);
    }

    /**
     * Set Previous Plugin Instance.  This is used to keep a record of changes.
     *
     * @param PluginInstance $instance Previous Plugin Instance
     *
     * @return void
     */
    public function setPreviousInstance(PluginInstance $instance)
    {
        $this->previousEntity = $instance->getInstanceId();
    }

    /**
     * Get Previous Plugin Instance.  This is used to keep a record of changes.
     *
     * @return PluginInstance
     */
    public function getPreviousInstance()
    {
        return $this->previousEntity;
    }

    /**
     * Gets the config that will be stored in the DB as JSON
     *
     * @return array
     *
     */

    public function getInstanceConfig()
    {
        return json_decode($this->instanceConfig, true);
    }

    /**
     * Sets the config that will be stored in the DB as JSON
     *
     * @param array $instanceConfig new value test
     *
     * @return null
     *
     */
    public function setInstanceConfig($instanceConfig)
    {
        $this->instanceConfig = json_encode($instanceConfig);
    }

    /**
     * @return string
     */
    public function getRenderedCss()
    {
        return $this->renderedCss;
    }

    /**
     * @param string $renderedCss
     */
    public function setRenderedCss($renderedCss)
    {
        $this->renderedCss = $renderedCss;
    }

    /**
     * @return string
     */
    public function getRenderedHtml()
    {
        return $this->renderedHtml;
    }

    /**
     * @param string $renderedHtml
     */
    public function setRenderedHtml($renderedHtml)
    {
        $this->renderedHtml = $renderedHtml;
    }

    /**
     * @return array
     */
    public function getRenderedJs()
    {
        return $this->renderedJs;
    }

    /**
     * @param array $renderedJs
     */
    public function setRenderedJs($renderedJs)
    {
        $this->renderedJs = $renderedJs;
    }

    /**
     * @return mixed
     */
    public function getCanCache()
    {
        return $this->canCache;
    }

    /**
     * @param mixed $canCache
     */
    public function setCanCache($canCache)
    {
        $this->canCache = $canCache;
    }

    /**
     * @return array
     */
    public function getEditCss()
    {
        return $this->editCss;
    }

    /**
     * @param array $editCss
     */
    public function setEditCss($editCss)
    {
        $this->editCss = $editCss;
    }

    /**
     * @return mixed
     */
    public function getEditJs()
    {
        return $this->editJs;
    }

    /**
     * @param mixed $editJs
     */
    public function setEditJs($editJs)
    {
        $this->editJs = $editJs;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * @param mixed $tooltip
     */
    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;
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
        if (isset($data['plugin'])) {
            $this->setPlugin($data['plugin']);
        }

        if (isset($data['siteWide']) && (bool)$data['siteWide'] === true) {
            $this->setSiteWide();
        }

        if (isset($data['displayName'])) {
            $this->setDisplayName($data['displayName']);
        }

        if (isset($data['instanceConfig'])) {
            $this->setInstanceConfig($data['instanceConfig']);
        }

        if (isset($data['md5'])) {
            $this->setMd5($data['md5']);
        }

        if (isset($data['saveData'])) {
            $this->setSaveData($data['saveData']);
        }

        if (isset($data['previousInstance'])
            && $data['previousInstance'] instanceof PluginInstance
        ) {
            $this->setPreviousInstance($data['previousInstance']);
        }

        if (isset($data['renderedCss'])) {
            $this->setRenderedCss($data['renderedCss']);
        }

        if (isset($data['renderedJs'])) {
            $this->setRenderedJs($data['renderedJs']);
        }

        if (isset($data['renderedHtml'])) {
            $this->setRenderedHtml($data['renderedHtml']);
        }

        if (isset($data['canCache'])) {
            $this->setCanCache($data['canCache']);
        }

        if (isset($data['editCss'])) {
            $this->setEditCss($data['editCss']);
        }

        if (isset($data['editJs'])) {
            $this->setEditJs($data['editJs']);
        }

        if (isset($data['icon'])) {
            $this->setIcon($data['icon']);
        }

        if (isset($data['tooltip'])) {
            $this->setTooltip($data['tooltip']);
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
        $data = get_object_vars($this);
        $data['instanceConfig'] = $this->getInstanceConfig();
        return $data;
    }

}
