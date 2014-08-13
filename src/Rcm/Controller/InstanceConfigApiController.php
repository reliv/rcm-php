<?php
/**
 * InstanceConfigApiController.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller\Plugin
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\Controller;

use Rcm\Exception\PluginInstanceNotFoundException;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * InstanceConfigApiController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller\Plugin
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class InstanceConfigApiController extends AbstractRestfulController
{
    public function get($id)
    {
        $siteId = $this->getServiceLocator()->get('Rcm\Service\SiteManager')
            ->getCurrentSiteId();
        $allowed = $this->getServiceLocator()
            ->get('RcmUser\Service\RcmUserService')->isAllowed(
                'sites.' . $siteId,
                'admin',
                'Rcm\Acl\ResourceProvider'
            );
        if (!$allowed) {
            $this->getResponse()->setStatusCode(401);
            return $this->getResponse();
        }
        try {
            $pluginMgr = $this->getServiceLocator()
                ->get('Rcm\Service\PluginManager');
            $plugin = $pluginMgr->getPluginByInstanceId($id);
            $instanceConfig = $pluginMgr->getInstanceConfig($id);
            $defaultInstanceConfig = $pluginMgr->getDefaultInstanceConfig(
                $plugin['pluginName']
            );
        } catch (PluginInstanceNotFoundException $e) {
            return $this->notFoundAction();
        }
        return new JsonModel(
            [
                'instanceConfig' => $instanceConfig,
                'defaultInstanceConfig' => $defaultInstanceConfig
            ]
        );
    }
}
