<?php

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
class PluginInstance extends AbstractApiModel implements \JsonSerializable, \IteratorAggregate
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
     * @ORM\Column(type="text", nullable=true)
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

    /**
     * @var
     */
    protected $editJs;

    /**
     * @var
     */
    protected $editCss;

    /**
     * @var
     */
    protected $tooltip;

    /**
     * @var
     */
    protected $icon;

    /**
     * @var
     */
    protected $canCache;

    /**
     * __clone
     *
     * @return void
     */
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
     * @return void
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
     * @return void
     */
    public function setPlugin($plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Set this instance as a site wide plugin instance
     *
     * @return void
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
     * @return void
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
    /**
     * getInstanceConfig
     *
     * @return array|mixed
     * @throws \Exception
     */
    public function getInstanceConfig()
    {
        if (empty($this->instanceConfig)) {
            return [];
        }

        $value = json_decode($this->instanceConfig, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = 'plugin: ' . $this->getPlugin() . ' id: ' . $this->getInstanceId() .
                ' getInstanceConfig received invalid JSON value: ' .
                '"' . var_export($this->instanceConfig, true) . '"' .
                ' with error ' . json_last_error_msg();
            throw new \Exception($message);
        }

        if (!is_array($value)) {
            return [];
        }

        return $value;
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
     * @param array $ignore
     *
     * @return void
     */
    public function populate(array $data, array $ignore = [])
    {
        if (isset($data['plugin']) && !in_array('plugin', $ignore)) {
            $this->setPlugin($data['plugin']);
        }

        if (isset($data['siteWide']) && $data['siteWide']
            && !in_array(
                'siteWide',
                $ignore
            )
        ) {
            $this->setSiteWide();
        }

        if (isset($data['displayName']) && !in_array('displayName', $ignore)) {
            $this->setDisplayName($data['displayName']);
        }

        if (isset($data['instanceConfig']) && !in_array('instanceConfig', $ignore)) {
            $this->setInstanceConfig($data['instanceConfig']);
        }

        if (isset($data['md5']) && !in_array('md5', $ignore)) {
            $this->setMd5($data['md5']);
        }

        if (isset($data['saveData']) && !in_array('saveData', $ignore)) {
            $this->setSaveData($data['saveData']);
        }

        if (isset($data['previousInstance'])
            && !in_array(
                'previousInstance',
                $ignore
            )
            && $data['previousInstance'] instanceof PluginInstance
        ) {
            $this->setPreviousInstance($data['previousInstance']);
        }

        if (isset($data['renderedCss']) && !in_array('renderedCss', $ignore)) {
            $this->setRenderedCss($data['renderedCss']);
        }

        if (isset($data['renderedJs']) && !in_array('renderedJs', $ignore)) {
            $this->setRenderedJs($data['renderedJs']);
        }

        if (isset($data['renderedHtml']) && !in_array('renderedHtml', $ignore)) {
            $this->setRenderedHtml($data['renderedHtml']);
        }

        if (isset($data['canCache']) && !in_array('canCache', $ignore)) {
            $this->setCanCache($data['canCache']);
        }

        if (isset($data['editCss']) && !in_array('editCss', $ignore)) {
            $this->setEditCss($data['editCss']);
        }

        if (isset($data['editJs']) && !in_array('editJs', $ignore)) {
            $this->setEditJs($data['editJs']);
        }

        if (isset($data['icon']) && !in_array('icon', $ignore)) {
            $this->setIcon($data['icon']);
        }

        if (isset($data['tooltip']) && !in_array('tooltip', $ignore)) {
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

        // @bc
        if (!in_array('siteWide', $ignore)) {
            $data['siteWide'] = $this->isSiteWide();
        }

        if (!in_array('isSiteWide', $ignore)) {
            $data['isSiteWide'] = $this->isSiteWide();
        }

        return $data;
    }
}
