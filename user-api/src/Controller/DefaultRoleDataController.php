<?php

namespace RcmUser\Api\Controller;

use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\Result;
use RcmUser\User\Entity\UserRoleProperty;
use Zend\Http\Response;

/**
 * Class DefaultRoleDataController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Api\ApiController
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class DefaultRoleDataController extends AbstractController
{
    /**
     * getList
     *
     * @return mixed|\Zend\Stdlib\ResponseInterface
     */
    public function getList()
    {
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_ACL,
            'read'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        /** @var $aclDataService \RcmUser\Acl\Service\AclDataService */
        $aclDataService = $this->getServiceLocator()->get(
            \RcmUser\Acl\Service\AclDataService::class
        );

        $data = [
            'rolePropertyId' => UserRoleProperty::PROPERTY_KEY,
            'superAdminRoleId' => $aclDataService->getSuperAdminRoleId()->getData(),
            'guestRoleId' => $aclDataService->getGuestRoleId()->getData(),
        ];

        $result = new Result(
            $data,
            Result::CODE_SUCCESS
        );

        return $this->getJsonResponse($result);
    }
}
