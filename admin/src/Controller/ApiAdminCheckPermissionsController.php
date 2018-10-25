<?php

namespace RcmAdmin\Controller;

use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\User\Entity\UserRoleProperty;

/**
 * Class ApiAdminCheckPermisionsController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2017 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiAdminCheckPermissionsController extends ApiAdminBaseController
{
    /**
     * isAllowed
     *
     * @param $resourceId
     * @param $privilege
     *
     * @return mixed
     */
    protected function isAllowed($resourceId, $privilege)
    {
        $rcmUserService = $this->getRcmUserService();

        return $rcmUserService->isAllowed(
            $resourceId,
            $privilege
        );
    }

    /**
     * getRcmUserService
     *
     * @return \RcmUser\Service\RcmUserService'
     */
    protected function getRcmUserService()
    {
        return $this->getServiceLocator()->get(
            'RcmUser\Service\RcmUserService'
        );
    }

    /**
     * getRequestResourceId
     *
     * @return string
     */
    protected function getRequestResourceId()
    {
        return (string)$this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'resourceId'
            );
    }

    /**
     * getRequestPrivileges
     *
     * @return null|string
     */
    protected function getRequestPrivileges()
    {
        $privileges = (string)$this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'privileges'
            );

        if ($privileges === '_NULL_') {
            $privileges = null;
        }

        return $privileges;
    }

    /**
     * get
     *
     * @param mixed $id
     *
     * @return mixed|ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function get($id)
    {
        if (!$this->isAllowed(RcmUserAclResourceProvider::RESOURCE_ID_ACL, 'read')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        $rcmUserService = $this->getRcmUserService();

        $requestUser = $rcmUserService->buildNewUser();

        $requestUser->setId($id);

        $userResult = $rcmUserService->readUser($requestUser);

        if (!$userResult->isSuccess()) {
            return new ApiJsonModel(
                null,
                1,
                "User was not found with id {$id}."
            );
        }

        $user = $userResult->getUser();

        $resourceId = $this->getRequestResourceId();
        $privileges = $this->getRequestPrivileges();

        $apiResponse = [
            'hasAccess' => $rcmUserService->isUserAllowed(
                $resourceId,
                $privileges,
                null,
                $user
            ),
            'resourceId' => $resourceId,
            'privileges' => $privileges,
            // 'user' => $user,
            'userRoles' => $user->getProperty(UserRoleProperty::PROPERTY_KEY)
        ];

        return new ApiJsonModel($apiResponse, 0, 'Success');
    }
}
