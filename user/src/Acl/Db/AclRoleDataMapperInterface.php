<?php

namespace RcmUser\Acl\Db;

use RcmUser\Acl\Entity\AclRole;
use RcmUser\Result;

/**
 * Interface AclRoleDataMapperInterface
 *
 * AclRoleDataMapperInterface Interface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface AclRoleDataMapperInterface
{
    /**
     * fetchSuperAdminRoleId
     *
     * RETURN DATA FORMAT:
     *    -- success
     *    '{roleId}'
     *
     *    -- fail
     *    null
     *
     * @return Result
     */
    public function fetchSuperAdminRoleId();

    /**
     * fetchGuestRoleId
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    '{roleId}'
     *
     *    -- fail
     *    null
     *
     * @return Result
     */
    public function fetchGuestRoleId();

    /**
     * fetchDefaultGuestRoleIds
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *    *    0 => '{roleId}',
     *    );
     *
     *    -- fail
     *    array()
     *
     * @return Result
     */
    public function fetchDefaultGuestRoleIds();

    /**
     * fetchDefaultUserRoleIds
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *        0 => '{roleId}',
     *    );
     *
     *    -- fail
     *    array()
     *
     * @return Result
     */
    public function fetchDefaultUserRoleIds();

    /**
     * fetchAll
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *        {roleId}' => RcmUser\Acl\Entity\AclRole,
     *    );
     *
     *    -- fail
     *    array()
     *
     * @return Result
     */
    public function fetchAll();

    /**
     * fetchByRoleId
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    RcmUser\Acl\Entity\AclRole
     *
     *    -- fail
     *    null
     *
     * DEFAULT ORDER BY: roleId
     *
     * @param string $roleId roleId
     *
     * @return Result
     */
    public function fetchByRoleId($roleId);

    /**
     * fetchByParentRoleId
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *    *    0 =>  RcmUser\Acl\Entity\AclRole
     *    );
     *
     *    -- fail
     *    array()
     *
     * @param int $parentRoleId parent id
     *
     * @return Result
     */
    public function fetchByParentRoleId($parentRoleId);

    /**
     * fetchRoleLineage - Get an array of my role and all parent in order of tree
     *
     *    -- success
     *    array (
     *    *    '{roleId}' =>  RcmUser\Acl\Entity\AclRole
     *    );
     *
     *    -- fail
     *    array()
     *
     * @param string $roleId roleId
     *
     * @return Result Containing array of AclRoles indexed by roleId
     */
    public function fetchRoleLineage($roleId);

    /**
     * create
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    RcmUser\Acl\Entity\AclRole
     *
     *    -- fail
     *    null
     *
     * @param AclRole $aclRole acl role
     *
     * @return Result
     */
    public function create(AclRole $aclRole);

    /**
     * read
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    RcmUser\Acl\Entity\AclRole
     *
     *    -- fail
     *    null
     *
     * @param AclRole $aclRole acl role
     *
     * @return Result
     */
    public function read(AclRole $aclRole);

    /**
     * update
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    RcmUser\Acl\Entity\AclRole
     *
     *    -- fail
     *    null
     *
     * @param AclRole $aclRole acl role
     *
     * @return Result
     */
    public function update(AclRole $aclRole);

    /**
     * delete
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    null
     *
     *    -- fail
     *    null
     *
     * @param AclRole $aclRole acl role
     *
     * @return Result
     */
    public function delete(AclRole $aclRole);
}
