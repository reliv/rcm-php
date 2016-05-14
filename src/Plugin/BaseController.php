<?php

namespace Rcm\Plugin;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;
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

    /**
     * @var string
     */
    protected $pluginName;

    /**
     * @var string
     */
    protected $nameLowerDashed;

    /**
     * @var array
     */
    protected $config;

    /**
     * BaseController constructor.
     *
     * @param array                   $config
     * @param null                    $pluginName
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(
        $config,
        $pluginName = null,
        $serviceLocator = null
    ) {
        if (empty($pluginName)) {
            /**
             * Automatically detect the plugin name for controllers that extend
             * this class by looking at the first part of the child's namespace
             */
            $classParts = explode('\\', get_class($this));
            $this->pluginName = $classParts[0];
        } else {
            /**
             * When this class is instantiated directly instead of being
             * extended, the plugin name must be passed in as the third argument
             */
            $this->pluginName = $pluginName;
        }

        $this->nameLowerDashed = $this->camelToHyphens(
            $this->pluginName
        );

        $this->template = $this->nameLowerDashed . '/plugin';

        $this->config = $config;

        if (!empty($serviceLocator) && $serviceLocator instanceOf ServiceLocatorInterface) {
            $this->setServiceLocator($serviceLocator);
        }
    }

    /**
     * Reads a plugin instance from persistent storage returns a view model for
     * it
     *
     * @param int   $instanceId
     * @param array $instanceConfig
     *
     * @return ViewModel
     */
    public function renderInstance($instanceId, $instanceConfig)
    {
        $view = new ViewModel(
            [
                'instanceId' => $instanceId,
                'instanceConfig' => $instanceConfig,
                'config' => $this->config,
            ]
        );

        $view->setTemplate($this->template);

        return $view;
    }

    /**
     * Is the post for this plugin
     *
     * @return bool
     */
    public function postIsForThisPlugin()
    {
        if (!$this->getRequest()->isPost()) {
            return false;
        }

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

    /**
     * Set zend request object
     *
     * @param RequestInterface $request
     *
     * @return mixed
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Set zend response object
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Service locator is going away from the abstract controller.  Adding here.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
