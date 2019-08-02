<?php

namespace RcmUser\User\Db;

use RcmUser\Result;
use RcmUser\User\Entity\UserInterface;

/**
 * Interface UserRolesDataMapperInterface
 *
 * UserRolesDataMapperInterface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface UserRolesDataMapperInterface
{

    /**
     * getAvailableRoles
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
     * @return array
     */
    public function getAvailableRoles();

    /**
     * fetchAll
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *        0 => RcmUser\User\Entity\UserRole
     *    );
     *
     *    -- fail
     *    array()
     *
     * @param array $options options
     *
     * @return Result
     */
    public function fetchAll($options = []);

    /**
     * add
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    role Id
     *
     *    -- fail
     *    null
     *
     * @param UserInterface $user      user
     * @param string        $aclRoleId aclRoleId
     *
     * @return Result
     */
    public function add(
        UserInterface $user,
        $aclRoleId
    );

    /**
     * remove
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    role Id
     *
     *    -- fail
     *    null
     *
     * @param UserInterface $user      user
     * @param string        $aclRoleId aclRoleId
     *
     * @return Result
     */
    public function remove(
        UserInterface $user,
        $aclRoleId
    );

    /**
     * create
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *        0 => RoleId,
     *    );
     *
     *    -- fail
     *    array()
     *
     * @param UserInterface $user  user
     * @param array         $roles roles
     *
     * @return Result
     */
    public function create(
        UserInterface $user,
        $roles = []
    );

    /**
     * read
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *        0 => RoleId,
     *    );
     *
     *    -- fail
     *    array()
     *
     * @param UserInterface $user user
     *
     * @return Result
     */
    public function read(UserInterface $user);

    /**
     * update
     *
     * RETURN DATA FORMAT:
     *
     *    -- success (updated role id list)
     *    array (
     *        0 => RoleId,
     *    );
     *
     *    -- fail (updated role id list)
     *    array (
     *        0 => RoleId,
     *    );
     *
     * @param UserInterface $user  user
     * @param array         $roles roles
     *
     * @return Result
     */
    public function update(
        UserInterface $user,
        $roles = []
    );

    /**
     * delete
     *
     * RETURN DATA FORMAT:
     *
     *    -- success (updated role id list)
     *    array (
     *        0 => RoleId,
     *    );
     *
     *    -- fail (updated role id list)
     *    array (
     *        0 => RoleId,
     *    );
     *
     * @param UserInterface $user  user
     * @param array         $roles roles
     *
     * @return Result
     */
    public function delete(
        UserInterface $user,
        $roles = []
    );
}
