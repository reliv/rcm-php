<?php
/**
 * Plugin Controller Interface
 *
 * Is the contract for plugin controllers
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\Plugin;

use Zend\Stdlib\RequestInterface;

/**
 * Plugin Controller Interface
 *
 * Is the contract for plugin controllers
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
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
     * Sets the Zend Request Object
     *
     * @param RequestInterface $request Zend Framework Request Object
     *
     * @return void
     */
    public function setRequest(RequestInterface $request);
}