<?php

namespace RcmUser\Api\Controller;

use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\Result;
use Zend\View\Model\JsonModel;

/**
 * Class AclResourcesController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Api\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AclResourcesController extends AbstractAdminApiController
{
    /**
     * getList
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_ACL,
            'read'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        /** @var \RcmUser\Acl\Service\AclResourceNsArrayService $aclResourceNsArrayService */
        $aclResourceNsArrayService = $this->getServiceLocator()->get(
            'RcmUser\Acl\Service\AclResourceNsArrayService'
        );

        // Increase time limit as this can be a long call
        set_time_limit(0);

        try {
            $resources = $aclResourceNsArrayService->getResourcesWithNamespace();
            $result = new Result($resources);
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }
}
