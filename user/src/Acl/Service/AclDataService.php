<?php

namespace RcmUser\Acl\Service;

use RcmUser\Acl\Db\AclRoleDataMapperInterface;
use RcmUser\Acl\Db\AclRuleDataMapperInterface;
use RcmUser\Acl\Entity\AclRole;
use RcmUser\Acl\Entity\AclRule;
use RcmUser\Acl\Entity\NamespaceAclRole;
use RcmUser\Event\EventProvider;
use RcmUser\Event\UserEventManager;
use RcmUser\Result;

/**
 * Class AclDataService
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AclDataService extends EventProvider
{
    const EVENT_IDENTIFIER = AclDataService::class;
    const EVENT_CREATE_ACL_ROLE = 'createAclRole';
    const EVENT_CREATE_ACL_ROLE_FAIL = 'createAclRoleFail';
    const EVENT_CREATE_ACL_ROLE_SUCCESS = 'createAclRoleSuccess';

    const EVENT_DELETE_ACL_ROLE = 'deleteAclRole';
    const EVENT_DELETE_ACL_ROLE_FAIL = 'deleteAclRoleFail';
    const EVENT_DELETE_ACL_ROLE_SUCCESS = 'deleteAclRoleSuccess';

    const EVENT_CREATE_ACL_RULE = 'createAclRule';
    const EVENT_CREATE_ACL_RULE_FAIL = 'createAclRuleFail';
    const EVENT_CREATE_ACL_RULE_SUCCESS = 'createAclRuleSuccess';

    const EVENT_DELETE_ACL_RULE = 'deleteAclRule';
    const EVENT_DELETE_ACL_RULE_FAIL = 'deleteAclRuleFail';
    const EVENT_DELETE_ACL_RULE_SUCCESS = 'deleteAclRuleSuccess';

    /**
     * @var AclRoleDataMapperInterface
     */
    protected $aclRoleDataMapper;

    /**
     * @var AclRuleDataMapperInterface
     */
    protected $aclRuleDataMapper;

    /**
     * Constructor.
     *
     * @param AclRoleDataMapperInterface $aclRoleDataMapper
     * @param AclRuleDataMapperInterface $aclRuleDataMapper
     * @param UserEventManager           $userEventManager
     */
    public function __construct(
        AclRoleDataMapperInterface $aclRoleDataMapper,
        AclRuleDataMapperInterface $aclRuleDataMapper,
        UserEventManager $userEventManager
    ) {
        $this->setAclRoleDataMapper($aclRoleDataMapper);
        $this->setAclRuleDataMapper($aclRuleDataMapper);

        parent::__construct($userEventManager);
    }

    /**
     * setAclRoleDataMapper
     *
     * @param AclRoleDataMapperInterface $aclRoleDataMapper aclRoleDataMapper
     *
     * @return void
     */
    public function setAclRoleDataMapper(
        AclRoleDataMapperInterface $aclRoleDataMapper
    ) {
        $this->aclRoleDataMapper = $aclRoleDataMapper;
    }

    /**
     * getAclRoleDataMapper
     *
     * @return AclRoleDataMapperInterface
     */
    public function getAclRoleDataMapper()
    {
        return $this->aclRoleDataMapper;
    }

    /**
     * setAclRuleDataMapper
     *
     * @param AclRuleDataMapperInterface $aclRuleDataMapper aclRuleDataMapper
     *
     * @return void
     */
    public function setAclRuleDataMapper(
        AclRuleDataMapperInterface $aclRuleDataMapper
    ) {
        $this->aclRuleDataMapper = $aclRuleDataMapper;
    }

    /**
     * getAclRuleDataMapper
     *
     * @return AclRuleDataMapperInterface
     */
    public function getAclRuleDataMapper()
    {
        return $this->aclRuleDataMapper;
    }

    /* ROLES ******************** */

    /**
     * getAllRolesData - alias getAllRoles without result
     *
     * @return array
     */
    public function getAllRolesData()
    {
        $result = $this->getAllRoles();

        return $result->getData();
    }

    /**
     * getDefaultGuestRoleIds
     *
     * @return Result
     */
    public function getDefaultGuestRoleIds()
    {
        return $this->aclRoleDataMapper->fetchDefaultGuestRoleIds();
    }

    /**
     * getDefaultUserRoleIds
     *
     * @return Result
     */
    public function getDefaultUserRoleIds()
    {
        return $this->aclRoleDataMapper->fetchDefaultUserRoleIds();
    }

    /**
     * getSuperAdminRoleId
     *
     * @return Result
     */
    public function getSuperAdminRoleId()
    {
        return $this->aclRoleDataMapper->fetchSuperAdminRoleId();
    }

    /**
     * getGuestRoleId
     *
     * @return Result
     */
    public function getGuestRoleId()
    {
        return $this->aclRoleDataMapper->fetchGuestRoleId();
    }

    /**
     * getAllRoles
     *
     * @return Result
     */
    public function getAllRoles()
    {
        return $this->aclRoleDataMapper->fetchAll();
    }

    /**
     * getAllRoles
     *
     * @return Result containing array of AcLRoles
     */
    public function getRoleLineage($roleId)
    {
        return $this->aclRoleDataMapper->fetchRoleLineage($roleId);
    }

    /**
     * getRoleByRoleId
     *
     * @param string $roleId roleId
     *
     * @return mixed
     */
    public function getRoleByRoleId($roleId)
    {
        return $this->aclRoleDataMapper->fetchByRoleId($roleId);
    }

    /**
     * createRole
     *
     * @param AclRole $aclRole aclRole
     *
     * @return Result
     */
    public function createRole(AclRole $aclRole)
    {
        $this->getEventManager()->trigger(
            self::EVENT_CREATE_ACL_ROLE,
            $this,
            [
                'aclRole' => $aclRole,
            ]
        );

        $result = $this->aclRoleDataMapper->create($aclRole);

        if (!$result->isSuccess()) {
            $this->getEventManager()->trigger(
                self::EVENT_CREATE_ACL_ROLE_FAIL,
                $this,
                [
                    'aclRole' => $aclRole,
                    'result' => $result,
                ]
            );

            return $result;
        }

        $this->getEventManager()->trigger(
            self::EVENT_CREATE_ACL_ROLE_SUCCESS,
            $this,
            [
                'aclRole' => $aclRole,
                'result' => $result,
            ]
        );

        return $result;
    }

    /**
     * readRole
     *
     * @param AclRole $aclRole aclRole
     *
     * @return Result
     */
    public function readRole(AclRole $aclRole)
    {
        return $this->aclRoleDataMapper->read($aclRole);
    }

    /**
     * deleteRole
     *
     * @param AclRole $aclRole aclRole
     *
     * @return Result
     */
    public function deleteRole(AclRole $aclRole)
    {
        $this->getEventManager()->trigger(
            self::EVENT_DELETE_ACL_ROLE,
            $this,
            [
                'aclRole' => $aclRole,
            ]
        );

        $roleId = $aclRole->getRoleId();

        // some roles should not be deleted, like super admin and guest
        $superAdminRoleId = $this->getSuperAdminRoleId()->getData();
        if ($roleId == $superAdminRoleId) {
            $result = new Result(null, Result::CODE_FAIL, "Super admin role ({$roleId}) cannot be deleted.");
            $this->getEventManager()->trigger(
                self::EVENT_DELETE_ACL_ROLE_FAIL,
                $this,
                [
                    'aclRole' => $aclRole,
                    'result' => $result,
                ]
            );

            return $result;
        }

        $guestRoleId = $this->getGuestRoleId()->getData();
        if ($roleId == $guestRoleId) {
            $result = new Result(null, Result::CODE_FAIL, "Guest role ({$roleId}) cannot be deleted.");
            $this->getEventManager()->trigger(
                self::EVENT_DELETE_ACL_ROLE_FAIL,
                $this,
                [
                    'aclRole' => $aclRole,
                    'result' => $result,
                ]
            );

            return $result;
        }

        $result = $this->aclRoleDataMapper->delete($aclRole);

        if (!$result->isSuccess()) {
            $this->getEventManager()->trigger(
                self::EVENT_DELETE_ACL_ROLE_FAIL,
                $this,
                [
                    'aclRole' => $aclRole,
                    'result' => $result,
                ]
            );

            return $result;
        }

        $rulesResult = $this->getRulesByRole($roleId);

        if (!$rulesResult->isSuccess()) {
            $rulesResult->setMessage(
                'Could not remove related rules for role: ' . $roleId
            );

            $this->getEventManager()->trigger(
                self::EVENT_DELETE_ACL_ROLE_FAIL,
                $this,
                [
                    'aclRole' => $aclRole,
                    'result' => $rulesResult,
                ]
            );

            return $rulesResult;
        }

        $aclRules = $rulesResult->getData();

        foreach ($aclRules as $aclRule) {
            $ruleResult = $this->deleteRule($aclRule);
            if (!$ruleResult->isSuccess()) {
                $result->setCode(Result::CODE_FAIL);
                $result->setMessage($ruleResult->getMessage());
            }
        }

        if (!$result->isSuccess()) {
            $this->getEventManager()->trigger(
                self::EVENT_DELETE_ACL_ROLE_FAIL,
                $this,
                [
                    'aclRole' => $aclRole,
                    'result' => $rulesResult,
                ]
            );

            return $result;
        }

        $this->getEventManager()->trigger(
            self::EVENT_DELETE_ACL_ROLE_SUCCESS,
            $this,
            [
                'aclRole' => $aclRole,
                'result' => $rulesResult,
            ]
        );

        return $result;
    }

    /**
     * getRolesWithNamespace
     *
     * @param string $nsChar  nsChar
     * @param bool   $refresh refresh
     *
     * @return Result
     */
    public function getRolesWithNamespace(
        $nsChar = '.',
        $refresh = false
    ) {
        $aclRoles = array();
        $roles = $this->getNamespacedRoles(
            $nsChar,
            $refresh
        )->getData();

        $index = 0;
        foreach ($roles as $ns => $nsRole) {
            $aclRoles[$index] = $nsRole;
            $index++;
        }

        return new Result($aclRoles, Result::CODE_SUCCESS);
    }

    /**
     * getNamespacedRoles
     *
     * @param string $nsChar nsChar
     *
     * @return Result
     */
    public function getNamespacedRoles($nsChar = '.')
    {
        $aclRoles = array();
        $result = $this->getAllRoles();

        if (!$result->isSuccess()) {
            return $result;
        }

        $roles = $result->getData();

        foreach ($roles as $role) {
            $ns = $this->createRoleNamespaceId(
                $role,
                $roles,
                $nsChar
            );

            $nsRole = new NamespaceAclRole(
                $this->getSuperAdminRoleId()->getData(),
                $this->getGuestRoleId()->getData()
            );

            $nsRole->populate($role);

            $nsRole->setNamespace($ns);

            $aclRoles[$ns] = $nsRole;
        }

        ksort($aclRoles);

        return new Result($aclRoles, Result::CODE_SUCCESS);
    }

    /**
     * createRoleNamespaceId
     *
     * @param AclRole $aclRole  aclRole
     * @param array   $aclRoles aclRoles
     * @param string  $nsChar   nsChar
     *
     * @return string
     */
    public function createRoleNamespaceId(
        AclRole $aclRole,
        $aclRoles,
        $nsChar = '.'
    ) {
        $parentId = $aclRole->getParentRoleId();
        $ns = $aclRole->getRoleId();
        if (!empty($parentId)) {
            $parent = $aclRoles[$parentId];

            $newns = $this->createRoleNamespaceId(
                $parent,
                $aclRoles,
                $nsChar
            );
            $ns = $newns . $nsChar . $ns;
        }

        return $ns;
    }

    /* RULES ******************** */

    /**
     * getAllRules
     *
     * @return Result
     */
    public function getAllRules()
    {
        return $this->aclRuleDataMapper->fetchAll();
    }

    /**
     * getRulesByResources
     *
     * @param array $resources Array of Resources to lookup
     *
     * @return Result
     */
    public function getRulesByResources(array $resources)
    {
        return $this->aclRuleDataMapper->fetchByResources($resources);
    }

    /**
     * getRulesByResource
     *
     * @param string $resourceId $resourceId
     *
     * @return Result
     */
    public function getRulesByResource($resourceId)
    {
        return $this->aclRuleDataMapper->fetchByResource($resourceId);
    }

    /**
     * getRulesByResourcePrivilege
     *
     * @param $resourceId
     * @param $privilege
     *
     * @return Result
     */
    public function getRulesByResourcePrivilege($resourceId, $privilege)
    {
        return $this->aclRuleDataMapper->fetchByResourcePrivilege(
            $resourceId,
            $privilege
        );
    }

    /**
     * getRulesByRole
     *
     * @param string $roleId roleId
     *
     * @return Result
     */
    public function getRulesByRole($roleId)
    {
        return $this->aclRuleDataMapper->fetchByRole($roleId);
    }

    /**
     * createRule
     *
     * @param AclRule $aclRule aclRule
     *
     * @return Result
     */
    public function createRule(AclRule $aclRule)
    {
        $this->getEventManager()->trigger(
            self::EVENT_CREATE_ACL_RULE,
            $this,
            [
                'aclRule' => $aclRule,
            ]
        );

        $rule = $aclRule->getRule();
        $roleId = $aclRule->getRoleId();
        $resource = $aclRule->getResourceId();

        // check required
        if (empty($rule) || empty($roleId) || empty($resource)) {
            $result = new Result(
                null,
                Result::CODE_FAIL,
                "New rule requires: rule, roleId and resourceId."
            );

            $this->getEventManager()->trigger(
                self::EVENT_CREATE_ACL_RULE_FAIL,
                $this,
                [
                    'aclRule' => $aclRule,
                    'result' => $result,
                ]
            );

            return $result;
        }

        // check if is super admin
        if ($roleId == $this->getSuperAdminRoleId()->getData()) {
            $result = new Result(
                null,
                Result::CODE_FAIL,
                "Rules cannot be assigned to super admin."
            );

            $this->getEventManager()->trigger(
                self::EVENT_CREATE_ACL_RULE_FAIL,
                $this,
                [
                    'aclRule' => $aclRule,
                    'result' => $result,
                ]
            );

            return $result;
        }

        // check if role exists
        $result = $this->getRoleByRoleId($roleId);

        if (!$result->isSuccess()) {
            $result = new Result(
                null,
                Result::CODE_FAIL,
                "Rules cannot be assigned to role that does not exist."
            );

            $this->getEventManager()->trigger(
                self::EVENT_CREATE_ACL_RULE_FAIL,
                $this,
                [
                    'aclRule' => $aclRule,
                    'result' => $result,
                ]
            );

            return $result;
        }

        // @todo validate resource/privilege exists
        $result = $this->aclRuleDataMapper->create($aclRule);

        if (!$result->isSuccess()) {
            $this->getEventManager()->trigger(
                self::EVENT_CREATE_ACL_RULE_FAIL,
                $this,
                [
                    'aclRule' => $aclRule,
                    'result' => $result,
                ]
            );

            return $result;
        }

        $this->getEventManager()->trigger(
            self::EVENT_CREATE_ACL_RULE_SUCCESS,
            $this,
            [
                'aclRule' => $aclRule,
                'result' => $result,
            ]
        );

        return $result;
    }

    /**
     * createRule
     *
     * @param AclRule $aclRule aclRule
     *
     * @return Result
     */
    public function deleteRule(AclRule $aclRule)
    {
        $this->getEventManager()->trigger(
            self::EVENT_DELETE_ACL_RULE,
            $this,
            [
                'aclRule' => $aclRule,
            ]
        );

        $rule = $aclRule->getRule();
        $roleId = $aclRule->getRoleId();
        $resource = $aclRule->getResourceId();

        // check required
        if (empty($rule) || empty($roleId) || empty($resource)) {
            $result = new Result(
                null,
                Result::CODE_FAIL,
                "Rule requires: rule, roleId and resourceId."
            );

            $this->getEventManager()->trigger(
                self::EVENT_DELETE_ACL_RULE_FAIL,
                $this,
                [
                    'aclRule' => $aclRule,
                    'result' => $result,
                ]
            );

            return $result;
        }

        // check if exists and get valid id
        $result = $this->aclRuleDataMapper->read($aclRule);

        if (!$result->isSuccess()) {
            $this->getEventManager()->trigger(
                self::EVENT_DELETE_ACL_RULE_FAIL,
                $this,
                [
                    'aclRule' => $aclRule,
                    'result' => $result,
                ]
            );

            return $result;
        }

        $result = $this->aclRuleDataMapper->delete($result->getData());

        if (!$result->isSuccess()) {
            $this->getEventManager()->trigger(
                self::EVENT_DELETE_ACL_RULE_FAIL,
                $this,
                [
                    'aclRule' => $aclRule,
                    'result' => $result,
                ]
            );

            return $result;
        }

        $this->getEventManager()->trigger(
            self::EVENT_DELETE_ACL_RULE_SUCCESS,
            $this,
            [
                'aclRule' => $aclRule,
                'result' => $result,
            ]
        );

        return $result;
    }

    /**
     * getRulesByRoles
     *
     * @param string $nsChar nsChar
     *
     * @return Result
     */
    public function getRulesByRoles($nsChar = '.')
    {
        $aclRoles = array();
        $result = $this->getNamespacedRoles($nsChar);

        if (!$result->isSuccess()) {
            return $result;
        }

        $roles = $result->getData();

        foreach ($roles as $ns => $role) {
            $id = $role->getRoleId();
            $aclRoles[$ns] = array();
            $aclRoles[$ns]['role'] = $role;
            $aclRoles[$ns]['roleNs'] = $ns;
            $rulesResult = $this->getRulesByRole($id);
            if ($rulesResult->isSuccess()) {
                $aclRoles[$ns]['rules'] = $rulesResult->getData();
            } else {
                $aclRoles[$ns]['rules'] = array();
            }
        }

        return new Result($aclRoles, Result::CODE_SUCCESS);
    }
}
