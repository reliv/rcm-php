<?php
/**
 * Index Controller for the entire application
 *
 * This file contains the main controller used for the application.  This
 * should extend from the base class and should need no further modification.
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
namespace Rcm\Controller;

use \Rcm\Exception\PluginActionNotImplemented;
use \Rcm\Entity\PluginInstance;

/**
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class PluginProxyController extends BaseController
{
    /**
     * Private Ajax actions
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function adminProxyAction()
    {
        $this->ensureAdminIsLoggedIn();

        list($pluginName, $instanceId, $action) = $this->parseParams();

        $instance = $this->getInstance($pluginName, $instanceId);

        if (!$instance) {
            $this->response->setStatusCode(404);
            return false;
        }

        try {
            /**
             * @var \Zend\View\Model\ViewModel | \Zend\Http\Response
             */
            $actionResponse = $this->pluginManager
                ->callPlugin(
                    $instance, $action . 'AdminAjaxAction', array(),
                    $this->getEvent()
                );
        } catch (PluginActionNotImplemented $e) {
            $this->response->setStatusCode(404);
            return false;
        }

        return $actionResponse;
    }

    /**
     * Public ajax actions
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function ajaxProxyAction()
    {

        list($pluginName, $instanceId, $action) = $this->parseParams();

        $instance = $this->getInstance($pluginName, $instanceId);

        if (!$instance) {
            $this->response->setStatusCode(404);
            return false;
        }

        try {
            /**
             * @var \Zend\View\Model\ViewModel | \Zend\Http\Response
             */
            $view = $this->pluginManager
                ->callPlugin(
                    $instance, $action . 'AjaxAction', array(),
                    $this->getEvent()
                );
        } catch (PluginActionNotImplemented $e) {
            $this->response->setStatusCode(404);
            return false;
        }

        exit($view->content);
    }

    function parseParams()
    {

        $routeMatch = $this->getEvent()->getRouteMatch();

        return array(

            //Plugin name
            ucfirst(
                $this->hyphensToCamel(
                    $routeMatch->getParam('pluginName')
                )
            ),

            //InstanceId
            $routeMatch->getParam('instanceId'),

            //Plugin action
            $this->hyphensToCamel(
                $routeMatch->getParam('pluginActionName')
            )


        );

    }

    function getInstance($pluginName, $instanceId)
    {

        if ($instanceId < 0) {
            $instance = new PluginInstance();
            $instance->setInstanceId($instanceId);
            $instance->setPlugin($pluginName);
        } else {
            $instance = $this->entityMgr
                ->getRepository('\Rcm\Entity\PluginInstance')
                ->findOneByInstanceId($instanceId);
        }

        return $instance;

    }

    function hyphensToCamel($value)
    {
        return preg_replace_callback(
            '/-[a-zA-Z]/',
            function ($matches) {
                return strtoupper($matches[0][1]);
            }
            , $value
        );
    }
}