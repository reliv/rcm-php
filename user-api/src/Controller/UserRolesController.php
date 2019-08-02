<?php

namespace RcmUser\Api\Controller;

use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\Result;
use RcmUser\User\Entity\User;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UserRolesController extends AbstractAdminApiController
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
     * @param array $data User with roles
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

            if (!isset($data['roles'])) {
                $result = new Result(
                    null,
                    Result::CODE_FAIL,
                    "No user roles received."
                );

                return $this->getJsonResponse($result);
            }

            $newRoles = $data['roles'];

            $result = $userRoleService->createRoles(
                $user,
                $newRoles
            );
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * update PUT
     *
     * @param string $userId User id
     * @param array  $roles  Updated roles
     *
     * @return array|mixed
     */
    public function update(
        $userId,
        $roles
    ) {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'update'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        /** @var \RcmUser\User\Service\UserRoleService $userRoleService */
        $userRoleService = $this->getServiceLocator()->get(
            \RcmUser\User\Service\UserRoleService::class
        );

        try {
            if (empty($userId)) {
                $result = new Result(
                    null,
                    Result::CODE_FAIL,
                    "No user id received."
                );

                return $this->getJsonResponse($result);
            }

            $user = new User($userId);

            if (empty($roles)) {
                $result = new Result(
                    null,
                    Result::CODE_FAIL,
                    "No user roles received."
                );

                return $this->getJsonResponse($result);
            }

            $result = $userRoleService->updateRoles(
                $user,
                $roles
            );
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * delete DELETE
     *
     * @param string $data User id with roles to delete
     *                     {
     *                     userId: "{ID}",
     *                     roles: [
     *                     "{roleId1}",
     *                     "{roleId2}"
     *                     ]
     *                     }
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

        // @todo implement this
        $result = new Result(null, Result::CODE_FAIL, "Method not available.");

        return $this->getJsonResponse($result);
    }
}
