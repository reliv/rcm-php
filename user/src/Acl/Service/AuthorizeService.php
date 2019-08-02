<?php

namespace RcmUser\Acl\Service;

use RcmUser\Acl\Entity\AclRole;
use RcmUser\Acl\Entity\AclRule;
use RcmUser\Acl\Exception\RcmUserAclException;
use RcmUser\Event\EventProvider;
use RcmUser\Event\UserEventManager;
use RcmUser\Exception\RcmUserException;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\UserRoleProperty;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Exception\ExceptionInterface;

class AuthorizeService extends EventProvider
{
    const EVENT_IDENTIFIER = AuthorizeService::class;

    const EVENT_IS_ALLOWED_SUPER_ADMIN = 'aclIsAllowedSuperAdmin';
    const EVENT_IS_ALLOWED_TRUE = 'aclIsAllowedTrue';
    const EVENT_IS_ALLOWED_FALSE = 'aclIsAllowedFalse';
    const EVENT_IS_ALLOWED_ERROR = 'aclIsAllowedError';

    /**
     *
     * @var string RESOURCE_DELIMITER
     */
    const RESOURCE_DELIMITER = '.';

    /**
     * @var Acl $acl
     */
    protected $acl;

    /**
     * @var AclResourceService $aclResourceService
     */
    protected $aclResourceService;

    /**
     * @var AclDataService $aclDataService
     */
    protected $aclDataService;

    /**
     * Constructor.
     *
     * @param AclResourceService $aclResourceService
     * @param AclDataService $aclDataService
     * @param UserEventManager $userEventManager
     */
    public function __construct(
        AclResourceService $aclResourceService,
        AclDataService $aclDataService,
        UserEventManager $userEventManager
    ) {
        $this->aclResourceService = $aclResourceService;
        $this->aclDataService = $aclDataService;

        parent::__construct($userEventManager);
    }

    /**
     * getAclResourceService
     *
     * @return AclResourceService
     */
    public function getAclResourceService()
    {
        return $this->aclResourceService;
    }

    /**
     * getAclResourceService
     *
     * @return AclDataService
     */
    public function getAclDataService()
    {
        return $this->aclDataService;
    }

    /**
     * getSuperAdminRoleId
     *
     * @return string
     */
    public function getSuperAdminRoleId()
    {
        return $this->getAclDataService()->getSuperAdminRoleId();
    }

    /**
     * Get the guest user role id
     *
     * @return string
     */
    public function getGuestRole()
    {
        $id = $this->getAclDataService()->getGuestRoleId()->getData();

        return $this->getAclDataService()->getRoleByRoleId($id)->getData();
    }

    /**
     * getRoles
     *
     * @return array
     */
    public function getRoles()
    {
        $result = $this->getAclDataService()->getNamespacedRoles();

        if (!$result->isSuccess()) {
            // @todo Throw error?
            return [];
        }

        return $result->getData();
    }

    /**
     * getUserRoles
     *
     * @param UserInterface|null $user user
     *
     * @return array
     */
    public function getUserRoles($user)
    {
        if (!($user instanceof UserInterface)) {
            return [$this->getGuestRole()];
        }

        /** @var $userRoleProperty UserRoleProperty */
        $userRoleProperty = $user->getProperty(UserRoleProperty::PROPERTY_KEY);

        if (!($userRoleProperty instanceof UserRoleProperty)) {
            return [];
        }

        return $userRoleProperty->getRoles();
    }

    /**
     * getRules
     *
     * @param array $resources resources
     *
     * @return array
     */
    public function getRules($resources = null)
    {
        if (empty($resources)) {
            $result = $this->getAclDataService()->getAllRules();

            if (!$result->isSuccess()) {
                // @todo Throw error?
                return [];
            }

            return $result->getData();
        }

        $rules = $this->getAclDataService()->getRulesByResources($resources);

        return $rules->getData();
    }

    /**
     * getResources
     *
     * @param string $resourceId resourceId
     * @param string $providerId @deprecated No Longer Required - providerId
     *
     * @return array
     */
    public function getResources(
        $resourceId,
        $providerId = null
    ) {
        return $this->getAclResourceService()->getResources(
            $resourceId,
            $providerId
        );
    }

    /**
     * getAcl - This cannot be called before resources are parsed
     *
     * @param string $resourceId resourceId
     * @param string $providerId @deprecated No Longer Required - providerId
     *
     * @return Acl
     */
    public function getAcl(
        $resourceId,
        $providerId
    ) {
        if (!isset($this->acl)) {
            $this->buildAcl();
        }

        /* resources privileges
            we load the every time so they maybe updated dynamically
        */
        $resources = $this->getResources(
            $resourceId,
            $providerId
        );

        foreach ($resources as $resource) {
            if (!$this->acl->hasResource($resource)) {
                $this->acl->addResource(
                    $resource,
                    $resource->getParentResource()
                );
            }

            $privileges = $resource->getPrivileges();

            if (!empty($privileges)) {
                foreach ($privileges as $privilege) {
                    if (!$this->acl->hasResource($privilege)) {
                        $this->acl->addResource(
                            $privilege,
                            $resource
                        );
                    }
                }
            }
        }

        // get only for resources
        $rules = $this->getRules($resources);

        /** @var AclRule $aclRule */
        foreach ($rules as $aclRule) {
            if ($aclRule->getRule() == AclRule::RULE_ALLOW) {
                $this->acl->allow(
                    $aclRule->getRoleId(),
                    $aclRule->getResourceId(),
                    $aclRule->getPrivileges(),
                    $aclRule->getAssertion()
                );
            } elseif ($aclRule->getRule() == AclRule::RULE_DENY) {
                $this->acl->deny(
                    $aclRule->getRoleId(),
                    $aclRule->getResourceId(),
                    $aclRule->getPrivileges(),
                    $aclRule->getAssertion()
                );
            }
        }

        return $this->acl;
    }

    /**
     * buildAcl
     *
     * @return void
     */
    public function buildAcl()
    {
        $this->acl = new Acl();

        // roles
        $roles = $this->getRoles();

        foreach ($roles as $role) {
            if ($this->acl->hasRole($role)) {
                // @todo throw error?
                continue;
            }

            $this->acl->addRole(
                $role,
                $role->getParent()
            );
        }
    }

    /**
     * hasSuperAdmin
     *
     * @param $userRoles
     *
     * @return bool
     */
    public function hasSuperAdmin($userRoles)
    {
        $superAdminRoleId = $this->getSuperAdminRoleId()->getData();

        if (!empty($superAdminRoleId)
            && is_array($userRoles)
            && in_array(
                $superAdminRoleId,
                $userRoles
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * isAllowed
     *
     * @param string $resourceId resourceId
     * @param string $privilege privilege
     * @param string $providerId @deprecated No Longer Required  - providerId
     * @param UserInterface $user user
     *
     * @return bool
     * @throws RcmUserException|RcmUserAclException
     */
    public function isAllowed(
        $resourceId,
        $privilege = null,
        $providerId = null,
        $user = null
    ) {
        $resourceId = strtolower($resourceId);

        /* Get roles or guest roles if no user */
        $userRoles = $this->getUserRoles($user);

        /** @todo This is an issue
         * if (count($userRoles) > 1) {
         * $userId = 'UNKNOWN';
         * if (!empty($user)) {
         * $userId = $user->getId();
         * }
         *
         * throw new RcmUserAclException(
         * 'Multiple roles are not currently supported: User: ' . $userId
         * . ' with roles: ' . json_encode($userRoles)
         * );
         * }*/

        /* Check super admin
         * we over-ride everything if user has super admin
         */
        if ($this->hasSuperAdmin($userRoles)) {
            $result = true;

            $this->getEventManager()->trigger(
                self::EVENT_IS_ALLOWED_SUPER_ADMIN,
                $this,
                [
                    'resourceId' => $resourceId,
                    'privilege' => $privilege,
                    'providerId' => $providerId,
                    'result' => $result,
                    'user' => $user,
                    'userRoles' => $userRoles,
                ]
            );

            return $result;
        }

        try {
            $acl = $this->getAcl(
                $resourceId,
                $providerId
            );

            foreach ($userRoles as $userRole) {
                // @todo This will fail on deny in some cases
                // @todo The logic for dealing with multiple roles with deny and allow needs to be addressed
                $result = $acl->isAllowed(
                    $userRole,
                    $resourceId,
                    $privilege
                );

                if ($result) {
                    $this->getEventManager()->trigger(
                        self::EVENT_IS_ALLOWED_TRUE,
                        $this,
                        [
                            'privilege' => $privilege,
                            'providerId' => $providerId,
                            'resourceId' => $resourceId,
                            'result' => $result,
                            'user' => $user,
                            'userRoleAllowed' => $userRole,
                            'userRoles' => $userRoles,
                        ]
                    );

                    return $result;
                }
            }
        } catch (ExceptionInterface $e) {
            $result = false;

            $error = 'AuthorizeService->isAllowed failed check for resourceId: (' . $resourceId . ')'
                . ' with exception: ' . get_class($e) . '::message: ' . $e->getMessage();

            $params = [
                'definedRoles' => $this->getRoles(),
                'error' => $error,
                'privilege' => $privilege,
                'providerId' => $providerId,
                'resourceId' => $resourceId,
                'result' => $result,
                'user' => $user,
                'userRoles' => $userRoles,
            ];

            $this->getEventManager()->trigger(
                self::EVENT_IS_ALLOWED_ERROR,
                $this,
                $params
            );

            return $result;
        }

        $result = false;

        $this->getEventManager()->trigger(
            self::EVENT_IS_ALLOWED_FALSE,
            $this,
            [
                'privilege' => $privilege,
                'providerId' => $providerId,
                'resourceId' => $resourceId,
                'result' => $result,
                'user' => $user,
                'userRoles' => $userRoles,
            ]
        );

        return $result;
    }

    /**
     * NOTE: This does NOT use rules, just determines if the user has a role in the linage
     *
     * @param UserInterface $user
     * @param string $allowedRoleId The role ID that would be allowed if the user has it
     * @param bool $useRoleInheritance True means check the user's parent roles too
     * @return bool
     */
    public function hasRoleBasedAccess(UserInterface $user, $allowedRoleId, $useRoleInheritance)
    {
        /* Get roles or guest roles if no user */
        $userRoles = $this->getUserRoles($user);

        /* Check for super admin. We over-ride everything if the user has super admin. */
        if ($this->hasSuperAdmin($userRoles)) {
            return true;
        }

        foreach ($userRoles as $userRole) {
            if ($userRole instanceof AclRole) {
                $userRoleId = $userRole->getRoleId();
            } else {
                $userRoleId = $userRole;
            }

            if ($userRoleId === $allowedRoleId) {
                return true;
            }

            if ($useRoleInheritance) {
                $userRoleLineageRoleIds = array_keys($this->aclDataService->getRoleLineage($userRoleId)->getData());
                if (in_array($allowedRoleId, $userRoleLineageRoleIds)) {
                    return true;
                }
            }
        }

        return false;
    }
}
