<?php

/**
 * Plugin BaseController
 *
 * Extend or directly-use this plugin controller for any Rcm plugin.
 * This controller does the following for you:
 * 1) Save plugin instance configs in Json format using the Doctrine DB Conn
 * 2) Injects instance configs into the view model for plugins under name "$instanceConfig"
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace Rcm\Plugin;

use RcmInstanceConfig\Service\PluginStorageMgrInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\RequestInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class BaseController extends AbstractActionController implements PluginInterface
{
    /**
     * @var string Tells public function renderInstance() which template to use.
     */
    protected $template;

    protected $pluginName;

    protected $pluginNameLowerCaseDash;

    protected $config;

    protected $pluginStorageMgr;

    public function __construct(
        PluginStorageMgrInterface $pluginStorageMgr,
        $config,
        $pluginName = null
    ) {
        $this->pluginStorageMgr = $pluginStorageMgr;

        if ($pluginName === null) {
            /**
             * Automatically detect the plugin name for controllers that extend
             * this class by looking at the first part of the child's namespace
             */
            $classParts = explode('\\', get_class($this));
            $this->pluginName = $classParts[0];
        } elseif (substr($pluginName, 0, 1) == '/') {
            /**
             * @TODO REMOVE THIS AFTER REMOVING ALL USES OF IT
             * Support the deprecated method of passing the plugin path rather
             * than its name as the third argument
             */
            $this->pluginName = basename(realpath($pluginName));
        } else {
            /**
             * When this class is instantiated directly instead of being
             * extended, the plugin name must be passed in as the third argument
             */
            $this->pluginName = $pluginName;
        }

        $this->pluginNameLowerCaseDash = $this->camelToHyphens(
            $this->pluginName
        );
        $this->template = $this->pluginNameLowerCaseDash . '/plugin';

        $this->config = $config;

    }

    /**
     * Reads a plugin instance from persistent storage returns a view model for
     * it
     *
     * @param int   $instanceId
     * @param array $extraViewVariables
     *
     * @return ViewModel
     */
    public function renderInstance($instanceId, $extraViewVariables = array())
    {
        $view = new ViewModel(
            array_merge(
                array(
                    'instanceId' => $instanceId,
                    'instanceConfig' => $this->getInstanceConfig($instanceId),
                    'config' => $this->config,
                ),
                $extraViewVariables
            )
        );
        $view->setTemplate($this->template);
        return $view;
    }

    /**
     * Returns a view model filled with content for a brand new instance. This
     * usually comes out of a config file rather than writable persistent
     * storage like a database.
     *
     * @param int   $instanceId
     * @param array $extraViewVariables
     *
     * @return mixed|ViewModel
     */
    public function renderDefaultInstance(
        $instanceId,
        $extraViewVariables = array()
    ) {
        $view = new ViewModel(
            array_merge(
                array(
                    'instanceId' => $instanceId,
                    'instanceConfig' => $this->getDefaultInstanceConfig($instanceId),
                    'config' => $this->config
                ),
                $extraViewVariables
            )
        );
        $view->setTemplate($this->template);
        return $view;
    }


    /**
     * Allows core to properly pass the request to this plugin controller
     *
     * @param $request
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Get entity content as JSON. This is called by the editor javascript of
     * some plugins. Urls look like
     * '/rcm-plugin-admin-proxy/rcm-plugin-name/11824/instance-config'
     *
     *
     * @param integer $instanceId instance id
     *
     * @return null
     */
    public function instanceConfigAdminAjaxAction($instanceId)
    {
        return new JsonModel(
            array(
                'instanceConfig' => $this->getInstanceConfig($instanceId),
                'defaultInstanceConfig' => $this->getDefaultInstanceConfig()
            )
        );
    }

    public function getInstanceConfig($instanceId)
    {
        return $this->pluginStorageMgr->getInstanceConfig(
            $instanceId,
            $this->pluginName
        );
    }

    public function getDefaultInstanceConfig()
    {
        return $this->pluginStorageMgr
            ->getDefaultInstanceConfig($this->pluginName);
    }

    /**
     * Saves a plugin instance to persistent storage
     *
     * @param string $instanceId plugin instance id
     * @param array  $configData posted data to be saved
     *
     * @return null
     */
    public function saveInstance($instanceId, $configData)
    {
        $this->pluginStorageMgr->saveInstance($instanceId, $configData);
    }

    /**
     * Deletes a plugin instance from persistent storage
     *
     * @param string $instanceId plugin instance id
     *
     * @return null
     */
    public function deleteInstance($instanceId)
    {
        $this->pluginStorageMgr->deleteInstance($instanceId);
    }

    public function postIsForThisPlugin()
    {
        return
            $this->getRequest()->getPost('rcmPluginName') == $this->pluginName;
    }


    /**
     * Converts camelCase to lower-case-hyphens
     *
     * @param string $value the value to convert
     *
     * @return string
     */
    public function camelToHyphens($value)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $value));
    }

    /**
     * @param string $pluginName
     */
    public function setPluginName($pluginName)
    {
        $this->pluginName = $pluginName;
    }

    /**
     * @return string
     */
    public function getPluginName()
    {
        return $this->pluginName;
    }
}
