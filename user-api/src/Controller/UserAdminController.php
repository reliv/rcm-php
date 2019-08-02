<?php

namespace RcmUser\Api\Controller;

use RcmUser\Acl\Entity\AclRule;
use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;
use RcmUser\User\Entity\UserRoleProperty;
use RcmUser\User\Result;
use Zend\View\Model\JsonModel;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UserAdminController extends AbstractAdminApiController
{
    /**
     * getList
     *
     * @return mixed|\Zend\Stdlib\ResponseInterface|JsonModel
     */
    public function getList()
    {
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'read'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        /** @var \RcmUser\User\Service\UserDataService $userDataService */
        $userDataService = $this->getServiceLocator()->get(
            \RcmUser\User\Service\UserDataService::class
        );

        try {
            $result = $userDataService->getAllUsers([]);
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * get
     *
     * @param string $id id
     *
     * @return mixed|JsonModel
     */
    public function get($id)
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'read'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        /** @var \RcmUser\User\Service\UserDataService $userDataService */
        $userDataService = $this->getServiceLocator()->get(
            \RcmUser\User\Service\UserDataService::class
        );

        try {
            $user = new User($id);

            $result = $userDataService->readUser($user);
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * create
     *
     * @param mixed|AclRule $data data
     *
     * @return mixed|JsonModel
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

        /** @var \RcmUser\User\Service\UserDataService $userDataService */
        $userDataService = $this->getServiceLocator()->get(
            \RcmUser\User\Service\UserDataService::class
        );

        try {
            // Build user from request
            $user = $this->buildUser($data);

            $result = $userDataService->createUser($user);
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * delete
     *
     * @param string $id id
     *
     * @return mixed|JsonModel
     */
    public function delete($id)
    {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'delete'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        /** @var \RcmUser\User\Service\UserDataService $userDataService */
        $userDataService = $this->getServiceLocator()->get(
            \RcmUser\User\Service\UserDataService::class
        );

        try {
            //$data = json_decode($this->getRequest()->getContent());
            $currentUser = $this->getRcmUserService()->getCurrentUser(new User());

            if ($id == $currentUser->getId()) {
                return new Result(
                    $id,
                    Result::CODE_FAIL,
                    "Cannot delete yourself."
                );
            }

            // Build user from request
            $user = new User($id);

            $result = $userDataService->deleteUser($user);
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * update
     *
     * @param mixed $id   user id
     * @param mixed $data user array
     *
     * @return array|mixed
     */
    public function update(
        $id,
        $data
    ) {
        // ACCESS CHECK
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'update'
        )
        ) {
            return $this->getNotAllowedResponse();
        }

        /** @var \RcmUser\User\Service\UserDataService $userDataService */
        $userDataService = $this->getServiceLocator()->get(
            \RcmUser\User\Service\UserDataService::class
        );

        try {
            // Build user from request
            $user = $this->buildUser($data);

            // NO PASSWORD change ALLOWED?
            $isAllowChangeCreds = $this->isAllowed(
                RcmUserAclResourceProvider::RESOURCE_ID_USER,
                'update_credentials'
            );
            if (!$isAllowChangeCreds) {
                if ($user->getPassword() !== null) {
                    $result = new Result(
                        $user,
                        Result::CODE_FAIL,
                        "Not allowed to change username and password."
                    );

                    return $this->getJsonResponse($result);
                }

                $user->setUsername(null);
            }

            $result = $userDataService->updateUser($user);

            if ($user->getUsername() === null) {
                $result->setMessage(
                    'Username was not update, was not allowed or empty.'
                );
            }
        } catch (\Exception $e) {
            return $this->getExceptionResponse($e);
        }

        return $this->getJsonResponse($result);
    }

    /**
     * buildUser
     *
     * @param array $data data
     *
     * @return UserInterface
     */
    protected function buildUser($data)
    {
        $user = new User();
        $user->populate(
            $data,
            ['properties']
        );

        $properties = [];
        if (isset($data['properties'])) {
            $properties = $data['properties'];
        }

        if (isset($properties[UserRoleProperty::PROPERTY_KEY])) {
            $roles = $properties[UserRoleProperty::PROPERTY_KEY];
            $userRoleProperty = new UserRoleProperty();
            $userRoleProperty->populate($roles);
            $user->setProperty(
                UserRoleProperty::PROPERTY_KEY,
                $userRoleProperty
            );
        }

        return $user;
    }
}
