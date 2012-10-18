<?php
/**
 * Plugin Controller Interface
 *
 * Is the contract for plugin controllers
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\HtmlArea
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
namespace Rcm\Controller;
/**
 * Plugin Controller Interface
 *
 * Is the contract for plugin controllers
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\HtmlArea
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 */
interface PluginControllerInterface
{
    /**
     * Plugin Action - Returns the guest-facing view model for this plugin
     *
     * @param int $instanceId plugin instance id
     *
     * @return \Zend\View\Model\ViewModel
     */
    function pluginAction($instanceId);

    /**
     * Save Action - Reads input data for a plugin instance and saves it to DB
     *
     * @param string $instanceId plugin instance id
     * @param array  $data       posted data to be saved
     *
     * @return null
     */
    function saveAction($instanceId,$data);

    /**
     * Delete Action - Deletes all data for a plugin instance from the DB
     *
     * @param string $instanceId plugin instance id
     *
     * @return null
     */
    function deleteAction($instanceId);
}
