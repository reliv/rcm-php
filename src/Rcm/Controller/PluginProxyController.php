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
 * @package   Main\Application\Controllers\Index
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace Rcm\Controller;

use \Rcm\Controller\BaseController,
\Rcm\Entity\PageRevision,
Zend\View\Model\ViewModel,
\Rcm\Entity\PluginInstance;

/**
 *
 * @category  Reliv
 * @package   Main\Application\Controllers\Index
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class PluginProxyController extends BaseController
{
    /**
     * Proxy admin functions that start with "admin" if admin is logged in
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function adminProxyAction()
    {
        $this->ensureAdminIsLoggedIn();

        list($pluginName, $instanceId, $action) = $this->parseParams();

        //This action is only for private plugin functions that start with "Admin"
        if (strpos($action, 'admin') !== 0) {
            $this->response->setStatusCode(404);
            return;
        }

        $instance = $this->getInstance($pluginName, $instanceId);

        if (!$instance) {
            $this->response->setStatusCode(404);
            return false;
        }

        /**
         * @var \Zend\View\Model\ViewModel | \Zend\Http\Response
         */
        $actionResponse = $this->callPlugin($instance, $action);

        return $actionResponse;
    }

    /**
     * Proxy functions that start with "ajax" this is publicly available
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function ajaxProxyAction()
    {

        list($pluginName, $instanceId, $action) = $this->parseParams();

        //This action is only plugin functions that start with "ajax"
        if (strpos($action, 'ajax') !== 0) {
            $this->response->setStatusCode(404);
            return;
        }

        $instance = $this->getInstance($pluginName, $instanceId);

        if (!$instance) {
            $this->response->setStatusCode(404);
            return false;
        }

        $view = $this->callPlugin($instance, $action);

        exit($view->content);
    }

    function parseParams()
    {

        $routeMatch=$this->getEvent()->getRouteMatch();
        
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
            $instance = $this->getEm()
                ->getRepository('\Rcm\Entity\PluginInstance')
                ->findOneByInstanceId($instanceId);
        }

        return $instance;

    }

    function hyphensToCamel($value)
    {
        return preg_replace("/\-(.)/e", "strtoupper('\\1')", $value);
    }
}