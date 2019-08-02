<?php

namespace RcmUser\Api\Controller;

use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\Result;
use RcmUser\User\Entity\User;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UserRoleController extends AbstractAdminApiController
{
    /**
     * get GET
     *
     * @param string $userId userId
     *
     * @return string
     */
    public function get($userId)
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'read'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        /** @var \RcmUser\User\Service\UserRoleService $userRoleService */
        $userRoleService = $this->getServiceLocator()->get(
            \RcmUser\User\Service\UserRoleService::class
        );

        try {
            $user = new User($userId);

            $result = $userRoleService->readRoles($user);

            $result->setMessage("For user id: {$userId}");
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * create POST
     *
     * @param array $data User with role
     *
     * @return string
     */
    public function create($data)
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'create'
        )
        ) {
            return $this->getNotAllowedResponse();
        }
        /** @var \RcmUser\User\Service\UserRoleService $userRoleService */
        $userRoleService = $this->getServiceLocator()->get(
            \RcmUser\User\Service\UserRoleService::class
        );

        try {
            if (!isset($data['userId'])) {
                $result = new Result(
                    null,
                    Result::CODE_FAIL,
                    "No user id received."
                );

                return $this->getJsonResponse($result);
            }

            $user = new User($data['userId']);

            if (!isset($data['role'])) {
                $result = new Result(
                    null,
                    Result::CODE_FAIL,
                    "No user roles received."
                );

                return $this->getJsonResponse($result);
            }

            $newRole = (string)$data['role'];

            $result = $userRoleService->addRole(
                $user,
                $newRole
            );
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * delete DELETE
     *
     * @param string $data User id with role to delete
     *                     array(
     *                     "userId" => "{ID}",
     *                     "role" => "{roleId}"
     *                     )
     *
     * @return string
     */
    public function delete($data)
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'delete'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        /** @var \RcmUser\User\Service\UserRoleService $userRoleService */
        $userRoleService = $this->getServiceLocator()->get(
            \RcmUser\User\Service\UserRoleService::class
        );

        try {
            $data = json_decode(
                urldecode($data),
                true
            );

            if (!isset($data['userId'])) {
                $result = new Result(
                    null,
                    Result::CODE_FAIL,
                    "No user id received."
                );

                return $this->getJsonResponse($result);
            }

            $user = new User($data['userId']);

            if (!isset($data['role'])) {
                $result = new Result(
                    null,
                    Result::CODE_FAIL,
                    "No user role received."
                );

                return $this->getJsonResponse($result);
            }

            $deleteRole = (string)$data['role'];

            $result = $userRoleService->removeRole(
                $user,
                $deleteRole
            );
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }
}
