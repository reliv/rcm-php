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
 * @ORM\Table(name="rcm_plugin_instances")
 */
class PluginInstance
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $instanceId;

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
    protected $displayName;

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
    protected $previousEntity;

    /**
     * @var array Array plugin assets which include images and links in use by
     *                  this plugin instance
     *
     * @ORM\ManyToMany(targetEntity="PluginAsset", mappedBy="pluginInstances")
     */
    protected $assets;

    /**
     * @var \Zend\View\Model\ViewModel Returned ViewModel from the
     *                                 Plugins Controller
     */
    protected $viewModel;

    /**
     * @var string Path the JS needed for editing plugin.  Defined in Modules
     *             config file and stored here for easy reference.
     */
    protected $adminEditJs;

    /**
     * @var sting Path to needed CSS file for editing the plugin.  Defined in
     *            the modules config file and stored here for easy reference.
     */
    protected $adminEditCss;

    protected $tooltip;

    protected $icon;

    protected $onPage = false;


    public function __construct()
    {
        $this->assets = new ArrayCollection();
    }

    public function __clone()
    {
        if ($this->instanceId) {
            $this->previousEntity = $this->instanceId;
            $this->instanceId = null;
        }
    }

    public function clearAssets()
    {
        $this->assets = new ArrayCollection();
    }

    /**
     * Function to return an array representation of the object.
     *
     * @return array Array representation of the object
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * Get the unique Instance ID
     *
     * @return int
     */
    public function getInstanceId()
    {
        return $this->instanceId;
    }

    /**
     * Set the ID of the Plugin Instance.  This was added for unit testing and
     * should not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own,
     *
     * @param int $instanceId Unique Plugin Instance ID
     *
     * @return null
     */
    public function setInstanceId($instanceId)
    {
        $this->instanceId = $instanceId;
    }

    /**
     * Get list of plugins
     *
     * @return string
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * Set the plugin for this instance.
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
     * Change SiteWide plugin to a normal plugin instance.
     *
     * @return null
     */
    public function setPageOnlyPlugin()
    {
        $this->siteWide = false;
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

        if (empty($this->displayName)) {
            return null;
        }

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
     * Set the ViewModel returned for this plugin instance.
     *
     * @param \Zend\View\Model\ViewModel $viewModel Zend ViewModel
     */
    public function setViewModel(\Zend\View\Model\ViewModel $viewModel)
    {
        $this->viewModel = $viewModel;
    }

    /**
     * Get the view for the plugin.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function getView()
    {
        return $this->viewModel;
    }

    /**
     * Set the JS file to use for editing the plugin
     *
     * @param string $jsPath Path to JavaScript
     */
    public function setAdminEditJs($jsPath)
    {
        $this->adminEditJs = $jsPath;
    }

    /**
     * Get the Admin Javascript path needed to edit the plugin.
     *
     * @return string JS Path
     */
    public function getAdminEditJs()
    {
        return $this->adminEditJs;
    }

    /**
     * Set the Admin CSS needed to to edit the plugin
     *
     * @param string $cssPath
     */
    public function setAdminEditCss($cssPath)
    {
        $this->adminEditCss = $cssPath;
    }

    /**
     * Get the Admin CSS path needed to edit the plugin.
     *
     * @return sting CSS Path
     */
    public function getAdminEditCss()
    {
        return $this->adminEditCss;
    }

    public function hasAdminJs()
    {
        if (!empty($this->adminEditJs)) {
            return true;
        }

        return false;
    }

    public function hasAdminCss()
    {
        if (!empty($this->adminEditCss)) {
            return true;
        }

        return false;
    }

    public function setMd5($md5)
    {
        $this->md5 = $md5;
    }

    public function getMd5()
    {
        return $this->md5;
    }

    public function setPreviousEntity(\Rcm\Entity\PluginInstance $instance)
    {
        $this->previousEntity = $instance->getInstanceId();
    }

    public function getPreviousEntityId()
    {
        return $this->previousEntity;
    }

    /**
     * Sets the Assets property
     *
     * @param array $assets
     *
     * @return null
     *
     */
    public function setAssets($assets)
    {
        $this->assets = $assets;
    }

    /**
     * Gets the Assets property
     *
     * @return array Assets
     *
     */
    public function getAssets()
    {
        return $this->assets;
    }

    public function getName()
    {
        return $this->plugin;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;
    }

    public function getTooltip()
    {
        return $this->tooltip;
    }

    public function setOnPage($onPage)
    {
        $this->onPage = $onPage;
    }

    public function getOnPage()
    {
        return $this->onPage;
    }

}