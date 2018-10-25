<?php

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rcm\Tracking\Model\Tracking;

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
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="rcm_plugin_instances")
 */
class PluginInstance extends ApiModelTrackingAbstract implements \JsonSerializable, \IteratorAggregate, Tracking
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
     * @deprecated <deprecated-site-wide-plugin>
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
    protected $instanceConfig = "[]";

    /**
     * <tracking>
     *
     * @var \DateTime Date object was first created
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdDate;

    /**
     * <tracking>
     *
     * @var string User ID of creator
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $createdByUserId;

    /**
     * <tracking>
     *
     * @var string Short description of create reason
     *
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $createdReason = Tracking::UNKNOWN_REASON;

    /**
     * <tracking>
     *
     * @var \DateTime Date object was modified
     *
     * @ORM\Column(type="datetime")
     */
    protected $modifiedDate;

    /**
     * <tracking>
     *
     * @var string User ID of modifier
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $modifiedByUserId;

    /**
     * <tracking>
     *
     * @var string Short description of create reason
     *
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $modifiedReason = Tracking::UNKNOWN_REASON;

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
     * @return static
     */
    public function newInstance(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        /** @var static $new */
        $new = parent::newInstance(
            $createdByUserId,
            $createdReason
        );

        // if no id, then it has not been save and can be returned
        if (empty($new->pluginInstanceId)) {
            return $new;
        }

        $new->pluginInstanceId = null;
        $new->previousEntity = null;

        return $new;
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
     * @deprecated <deprecated-site-wide-plugin>
     * Set this instance as a site wide plugin instance
     *
     * @return void
     */
    public function setSiteWide()
    {
        $this->siteWide = true;
    }

    /**
     * @deprecated <deprecated-site-wide-plugin>
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
     * Get Previous Plugin Instance ID.  This is used to keep a record of changes.
     *
     * @return int
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
     * @return void
     * @throws \Exception
     */
    public function setInstanceConfig($instanceConfig)
    {
        $this->instanceConfig = json_encode($instanceConfig);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(json_last_error_msg());
        }
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
    public function populate(array $data, array $ignore = ['createdByUserId', 'createdDate', 'createdReason'])
    {
        if (isset($data['plugin']) && !in_array('plugin', $ignore)) {
            $this->setPlugin($data['plugin']);
        }

        // @deprecated <deprecated-site-wide-plugin>
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
        // @deprecated <deprecated-site-wide-plugin>
        if (!in_array('siteWide', $ignore)) {
            $data['siteWide'] = $this->isSiteWide();
        }

        // @deprecated <deprecated-site-wide-plugin>
        if (!in_array('isSiteWide', $ignore)) {
            $data['isSiteWide'] = $this->isSiteWide();
        }

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
