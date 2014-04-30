<?php
/**
 * Abstract Class for Containers.
 *
 * This file contains the abstract class used by plugin containers.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\Service;

/**
 * Abstract Class for Containers.
 *
 * Abstract Class for Containers.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
abstract class ContainerAbstract
{
    /** @var \Rcm\Service\PluginManager */
    protected $pluginManager;

    /**
     * Get a rendered plugin Instance from the Plugin Manager
     *
     * @param array &$revisionData Database result set
     *
     * @return void
     */
    protected function getPluginRenderedInstances(&$revisionData)
    {

        foreach ($revisionData['pluginInstances'] as &$pluginWrapper) {
            $renderedData = $this->pluginManager->getPluginByInstanceId(
                $pluginWrapper['instance']['pluginInstanceId']
            );

            $pluginWrapper['instance']['renderedData'] = $renderedData;
        }
    }

    /**
     * Check all the plugin instances to see if the entire container revision
     * can be cached.
     *
     * @param array &$revisionData Database result set
     *
     * @return bool
     */
    protected function canCacheRevision(&$revisionData)
    {
        $canCache = true;

        foreach ($revisionData['pluginInstances'] as &$pluginWrapper) {
            if (empty($pluginWrapper['instance']['canCache'])) {
                $canCache = false;
            }
        }

        return $canCache;
    }
}