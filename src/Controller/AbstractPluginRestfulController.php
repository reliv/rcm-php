<?php


namespace Rcm\Controller;

use Rcm\Exception\PluginInstanceNotFoundException;
use Zend\Mvc\Controller\AbstractRestfulController;


/**
 * Class AbstractPluginRestfulController
 *
 * Exposes instance methods for plugins with APIs
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class AbstractPluginRestfulController extends AbstractRestfulController
{
    /**
     * @var string
     */
    protected $rcmPluginName;

    /**
     * @param null $rcmPluginName
     */
    public function __construct($rcmPluginName = null)
    {
        // not enforcing this so a factory is not required
        // Therefore the name can be forced as needed
        if (!empty($rcmPluginName)) {
            $this->rcmPluginName = $rcmPluginName;
        }
    }

    /**
     * getRcmPluginConfig
     *
     * @return null
     */
    public function getRcmPluginConfig()
    {
        $config = $this->getServiceLocator()->get(
            'config'
        );

        $rcmPluginName = $this->getRcmPluginName();

        if (isset($config['rcmPlugin'][$rcmPluginName])) {
            return $config['rcmPlugin'][$rcmPluginName];
        }

        return null;
    }

    /**
     * getRcmPluginName
     *
     * @return null|string
     */
    public function getRcmPluginName()
    {
        if (empty($this->rcmPluginName)) {
            /**
             * Automatically detect the plugin name for controllers that extend
             * this class by looking at the first part of the child's namespace
             */
            $classParts = explode('\\', get_class($this));
            $this->rcmPluginName = $classParts[0];
        }

        return $this->rcmPluginName;
    }

    /**
     * getInstanceConfig by instance id
     *
     * @param int $instanceId
     *
     * @return array
     */
    protected function getInstanceConfig($instanceId = 0)
    {
        /** @var \Rcm\Service\PluginManager $pluginManager */
        $pluginManager = $this->getServiceLocator()->get(
            'Rcm\Service\PluginManager'
        );

        $instanceConfig = [];

        $pluginName = $this->getRcmPluginName();

        if ($instanceId > 0) {
            try {
                $instanceConfig = $pluginManager->getInstanceConfigForPlugin($instanceId, $pluginName);
            } catch (PluginInstanceNotFoundException $e) {
                // ignore error
            }
        }

        if (empty($instanceConfig)) {

            $instanceConfig = $pluginManager->getDefaultInstanceConfig(
                $pluginName
            );
        }

        return $instanceConfig;
    }

    /**
     * getRequestedInstanceConfig
     *
     * @param string $paramName
     *
     * @return array
     * @throws \Exception
     */
    protected function getRequestedInstanceConfig($paramName = 'instanceId')
    {
        $instanceId = $this->getInstanceIdParam($paramName, null);

        if ($instanceId === null) {
            throw new \Exception("Route param '{$paramName}' required");
        }

        return $this->getInstanceConfig($instanceId);
    }

    /**
     * getInstanceIdParam from route param
     *
     * @param string $paramName
     * @param int    $default
     *
     * @return mixed
     */
    protected function getInstanceIdParam($paramName = 'instanceId', $default = 0)
    {
        return $this->getEvent()
            ->getRouteMatch()
            ->getParam($paramName, $default);
    }
}
