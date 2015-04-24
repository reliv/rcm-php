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
    public function get($instanceId)
    {
        $siteId = $this->getServiceLocator()->get('Rcm\Service\CurrentSite')
            ->getSiteId();
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

        $routeMatch = $this->getEvent()->getRouteMatch();
        $pluginType = $routeMatch->getParam('pluginType');
        $pluginMgr = $this->getServiceLocator()
            ->get('Rcm\Service\PluginManager');
        $defaultInstanceCfg = $pluginMgr->getDefaultInstanceConfig($pluginType);
        if (empty($defaultInstanceCfg)) {
            return $this->notFoundAction();
        }
        if ($instanceId > 0) {
            try {
                $instanceConfig = $pluginMgr->getInstanceConfig($instanceId);
            } catch (PluginInstanceNotFoundException $e) {
                return $this->notFoundAction();
            }
        } else {
            $instanceConfig = $defaultInstanceCfg;
        }
        return new JsonModel(
            [
                'instanceConfig' => $instanceConfig,
                'defaultInstanceConfig' => $defaultInstanceCfg
            ]
        );
    }
}
