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
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace Rcm\Plugin;

use \Zend\Http\PhpEnvironment\Request;

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
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 */
interface PluginInterface
{
    /**
     * Reads a plugin instance from persistent storage returns a view model for
     * it
     *
     * @param int $instanceId plugin instance id
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderInstance($instanceId);


    /**
     * Returns a view model filled with content for a brand new instance. This
     * usually comes out of a config file rather than writable persistent
     * storage like a database.
     *
     * @param integer $instanceId
     *
     * @return mixed
     */
     public function renderDefaultInstance($instanceId);

    /**
     * Saves a plugin instance to persistent storage
     *
     * @param string $instanceId plugin instance id
     * @param array  $data       posted data to be saved
     *
     * @return null
     */
    public function saveInstance($instanceId, $data);

    /**
     * Deletes a plugin instance from persistent storage
     *
     * @param string $instanceId plugin instance id
     *
     * @return null
     */
    public function deleteInstance($instanceId);

    /**
     * Set Request
     */
    public function setRequest(Request $request);
}