<?php

namespace Rcm\Controller;

use Rcm\Acl\ResourceName;
use Rcm\Exception\PluginInstanceNotFoundException;
use Rcm\Service\PluginManager;
use RcmUser\Service\RcmUserService;
use Zend\View\Model\JsonModel;

/**
 * InstanceConfigApiController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller\Plugin
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2018 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class InstanceConfigApiController extends AbstractRestfulController
{
    /**
     * get
     *
     * @param mixed $instanceId
     *
     * @return array|\Zend\Stdlib\ResponseInterface|JsonModel
     */
    public function get($instanceId)
    {
        $siteId = $this->getServiceLocator()->get(\Rcm\Service\CurrentSite::class)
            ->getSiteId();
        /** @var RcmUserService $rcmUserService */
        $rcmUserService = $this->serviceLocator->get(RcmUserService::class);

        /** @var ResourceName $resourceName */
        $resourceName = $this->getServiceLocator()->get(
            ResourceName::class
        );

        $allowed = $rcmUserService->isAllowed(
            $resourceName->get(ResourceName::RESOURCE_SITES, $siteId),
            'admin',
            \Rcm\Acl\ResourceProvider::class
        );

        if (!$allowed) {
            $this->getResponse()->setStatusCode(401);

            return $this->getResponse();
        }

        $routeMatch = $this->getEvent()->getRouteMatch();
        $pluginType = $routeMatch->getParam('pluginType');
        /**
         * @var $pluginMgr PluginManager
         */
        $pluginMgr = $this->getServiceLocator()
            ->get('Rcm\Service\PluginManager');
        $defaultInstanceCfg = $pluginMgr->getDefaultInstanceConfig($pluginType);
        if (!is_array($defaultInstanceCfg)) {
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
