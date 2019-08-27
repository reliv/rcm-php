<?php

namespace Rcm\Controller;

use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Acl2\SecurityPropertyConstants;
use Rcm\Exception\PluginInstanceNotFoundException;
use Rcm\Http\NotAllowedResponseJsonZf2;
use Rcm\RequestContext\RequestContext;
use Rcm\Service\PluginManager;
use RcmUser\Service\RcmUserService;
use Zend\View\Model\JsonModel;

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

        /** @oldControllerAclAccessCheckReplaced */

        /**
         * @var AssertIsAllowed $assertIsAllowed
         */
        $assertIsAllowed = $this->getServiceLocator()->get(RequestContext::class)
            ->get(AssertIsAllowed::class);

        try {
            $assertIsAllowed->__invoke(
                AclActions::READ,
                [
                    'type' => SecurityPropertyConstants::TYPE_ADMIN_TOOL,
                    SecurityPropertyConstants::ADMIN_TOOL_TYPE_KEY =>
                        SecurityPropertyConstants::ADMIN_TOOL_TYPE_BLOCK_INSTANCE_CONFIG
                ]
            );
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
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
