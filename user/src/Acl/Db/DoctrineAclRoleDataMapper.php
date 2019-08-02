<?php

namespace RcmUser\Acl\Db;

use Doctrine\ORM\EntityManager;
use RcmUser\Acl\Entity\AclRole;
use RcmUser\Acl\Entity\DoctrineAclRole;
use RcmUser\Db\DoctrineMapperInterface;
use RcmUser\Result;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class DoctrineAclRoleDataMapper extends AclRoleDataMapper implements
    AclRoleDataMapperInterface,
    DoctrineMapperInterface
{
    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @var string $entityClass
     */
    protected $entityClass;

    protected $cache = [];

    /**
     * setEntityManager
     *
     * @param EntityManager $entityManager entityManager
     *
     * @return void
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * getEntityManager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * setEntityClass
     *
     * @param string $entityClass entityClass namespace
     *
     * @return void
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = (string)$entityClass;
    }

    /**
     * getEntityClass
     *
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * fetchAll
     *
     * @return Result
     */
    public function fetchAll()
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT role FROM ' . $this->getEntityClass() . ' role '
            . 'INDEX BY role.roleId '
        );
        $query->useResultCache(true);

        $roles = $query->getResult();

        $result = new Result($roles);

        if (empty($roles)) {
            $result->setMessage('No roles found,');
        }

        return $result;
    }

    /**
     * fetchByRoleId
     *
     * @param string $roleId the role identity string
     *
     * @return Result
     */
    public function fetchByRoleId($roleId)
    {
        if ($this->hasCache($roleId)) {
            $role = $this->getCache($roleId);

            return new Result($role);
        }

        /** @var AclRole|null $role */
        $role = $this->getEntityManager()->getRepository(
            $this->getEntityClass()
        )->findOneBy(['roleId' => $roleId]);

        $result = new Result($role);

        if (empty($role)) {
            $result->setMessage('No roles found,');
        }

        $this->setCache($role);

        return $result;
    }

    /**
     * fetchByParentRoleId
     *
     * @param mixed $parentRoleId the parent id
     *
     * @return Result
     */
    public function fetchByParentRoleId($parentRoleId)
    {
        $roles = $this->getEntityManager()->getRepository(
            $this->getEntityClass()
        )->findBy(['parentRoleId' => $parentRoleId]);

        $result = new Result($roles);

        if (empty($roles)) {
            $result->setMessage('No roles found,');
        }

        return $result;
    }

    /**
     * fetchRoleLineage - Get an array of my role and all parent in order of tree
     *
     * @param string $roleId roleId
     *
     * @return Result Containing array of AclRoles indexed by roleId
     */
    public function fetchRoleLineage($roleId)
    {
        $lineage = array();

        while (true) {
            $result = $this->fetchByRoleId($roleId);

            if (!$result->isSuccess()) {
                return new Result(
                    array(),
                    Result::CODE_FAIL,
                    $result->getMessages()
                );
                break;
            }

            /** @var AclRole $role */
            $role = $result->getData();
            $roleId = $role->getParentRoleId();
            $lineage[$role->getRoleId()] = $role;

            if (empty($roleId)) {
                // no parent
                break;
            }
        }

        return new Result($lineage, Result::CODE_SUCCESS, "Success");
    }

    /**
     * create
     *
     * @param AclRole $aclRole acl role
     *
     * @return Result
     */
    public function create(AclRole $aclRole)
    {
        $result = $this->getValidInstance($aclRole);

        $aclRole = $result->getData();

        $result = $this->read($aclRole);

        $existingAclRole = $result->getData();

        if ($result->isSuccess() && !empty($existingAclRole)) {
            return new Result(
                null,
                Result::CODE_FAIL,
                'Acl Role already exists: ' . var_export(
                    $aclRole,
                    true
                )
            );
        }

        try {
            $this->getEntityManager()->persist($aclRole);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            return new Result(
                null,
                Result::CODE_FAIL,
                'Acl Role could not be created: ' . var_export(
                    $aclRole,
                    true
                )
            );
        }

        return new Result($aclRole);
    }

    /**
     * read
     *
     * @param AclRole $aclRole the acl role
     *
     * @return Result
     */
    public function read(AclRole $aclRole)
    {
        $result = $this->getValidInstance($aclRole);

        $aclRole = $result->getData();
        $roleId = $aclRole->getRoleId();

        if (!empty($roleId)) {
            $result = $this->fetchByRoleId($roleId);

            return $result;
        }

        return new Result(null, Result::CODE_FAIL, 'Acl Role could not be read.');
    }

    /**
     * update
     *
     * @param AclRole $aclRole acl role
     *
     * @return Result
     */
    public function update(AclRole $aclRole)
    {
        $result = $this->getValidInstance($aclRole);
        $aclRole = $result->getData();

        $result = $this->read($aclRole);

        if (!$result->isSuccess()) {
            return $result;
        }

        $existingAclRole = $result->getData();

        if (empty($existingAclRole)) {
            return new Result(
                null,
                Result::CODE_SUCCESS,
                'Role not found to update: ' . $aclRole->getRoleId()
            );
        }

        try {
            $this->getEntityManager()->merge($aclRole);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            return new Result(
                null,
                Result::CODE_FAIL,
                'Acl Role could not be updated: ' . var_export(
                    $aclRole,
                    true
                )
            );
        }

        return new Result(
            $aclRole,
            Result::CODE_SUCCESS,
            'Successfully updated ' . $aclRole->getRoleId()
        );
    }

    /**
     * delete
     *
     * @param AclRole $aclRole acl role
     *
     * @return Result
     */
    public function delete(AclRole $aclRole)
    {
        $aclRoleId = $aclRole->getRoleId();
        $result = $this->read($aclRole);

        if (!$result->isSuccess()) {
            return $result;
        }

        $aclRole = $result->getData();

        if (empty($aclRole)) {
            return new Result(
                null,
                Result::CODE_SUCCESS,
                'Role not found to update: ' . $aclRoleId
            );
        }

        $aclRoleId = $aclRole->getRoleId();
        $parentRoleId = $aclRole->getParentRoleId();

        $result = $this->fetchByParentRoleId($aclRoleId);

        if (!$result->isSuccess()) {
            $result->setMessage(
                'Failed to find child roles for  ' . $aclRole->getRoleId() . '.'
            );

            return $result;
        }

        $childRoles = $result->getData();

        foreach ($childRoles as $childRole) {
            $childRole->setParentRoleId($parentRoleId);
            $childResult = $this->update($childRole);

            if (!$childResult->isSuccess()) {
                $result->setCode(Result::CODE_FAIL);
                $result->setMessage($childResult->getMessage());
            }
        }

        if (!$result->isSuccess()) {
            $result->setMessage(
                'Failed to update child roles for delete of ' . $aclRole->getRoleId()
                . '.'
            );

            return $result;
        }

        try {
            $this->getEntityManager()->remove($aclRole);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            return new Result(
                null,
                Result::CODE_FAIL,
                'Acl Role could not be deleted: ' . var_export(
                    $aclRole,
                    true
                )
            );
        }

        return new Result(
            null,
            Result::CODE_SUCCESS,
            'Successfully deleted ' . $aclRoleId
        );
    }

    /**
     * getValidInstance
     *
     * @param AclRole $aclRole acl role
     *
     * @return Result
     */
    public function getValidInstance(AclRole $aclRole)
    {
        if (!($aclRole instanceof DoctrineAclRole)) {
            $doctrineAclRole = new DoctrineAclRole();
            $doctrineAclRole->populate($aclRole);

            $aclRole = $doctrineAclRole;
        }

        return new Result($aclRole);
    }

    /**
     * prepareRoles
     *
     * @param array $roles indexed by id
     *
     * @return array
     */
    public function prepareRoles($roles)
    {
        foreach ($roles as $key => $role) {
            $parentRoleId = $role->getParentRoleId();

            if (isset($roles[$parentRoleId])) {
                $roles[$key]->setParentRole($roles[$parentRoleId]);
            }
        }

        return $roles;
    }

    /**
     * createNamespaceId
     *
     * @param AclRole $role     acl role
     * @param array   $aclRoles array of roles
     *
     * @return string
     */
    public function createNamespaceId(
        AclRole $role,
        $aclRoles
    ) {
        $parentRoleId = $role->getParentRoleId();
        $ns = $role->getRoleId();
        if (!empty($parentRoleId)) {
            $parent = $aclRoles[$parentRoleId];

            $newNs = $this->createNamespaceId(
                $parent,
                $aclRoles
            );
            $ns = $newNs . '.' . $ns;
        }

        return $ns;
    }

    /**
     * @param AclRole $role
     *
     * @return void
     */
    protected function setCache(AclRole $role)
    {
        $this->cache[$role->getRoleId()] = $role;
    }

    /**
     * @param $roleId
     *
     * @return bool
     */
    protected function hasCache($roleId)
    {
        return array_key_exists($roleId, $this->cache);
    }

    /**
     * @param $roleId
     *
     * @return AclRole|null
     */
    protected function getCache($roleId)
    {
        if ($this->hasCache($roleId)) {
            return $this->cache[$roleId];
        }

        return null;
    }
}
