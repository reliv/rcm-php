<?php

namespace RcmUser\User\Db;

use RcmUser\Acl\Db\AclRoleDataMapperInterface;
use RcmUser\Exception\RcmUserException;
use RcmUser\Result;
use RcmUser\User\Entity\UserInterface;

/**
 * Class UserRolesDataMapper
 *
 * LongDescHere
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
class UserRolesDataMapper implements UserRolesDataMapperInterface
{
    /**
     * @var AclRoleDataMapperInterface $aclRoleDataMapper
     */
    protected $aclRoleDataMapper;

    /**
     * @var array $availableRoles
     */
    protected $availableRoles = [];

    /**
     * __construct
     *
     * @param AclRoleDataMapperInterface $aclRoleDataMapper aclRoleDataMapper
     */
    public function __construct(AclRoleDataMapperInterface $aclRoleDataMapper)
    {
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
     * getAvailableRoles
     *
     * @return array
     */
    public function getAvailableRoles()
    {
        if (!empty($this->availableRoles)) {
            return $this->availableRoles;
        }

        $result = $this->getAclRoleDataMapper()->fetchAll();

        if ($result->isSuccess()) {
            $this->availableRoles = $result->getData();
        }

        return $this->availableRoles;
    }

    /**
     * fetchAll
     *
     * @param array $options options
     *
     * @return Result
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function fetchAll($options = [])
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * add
     *
     * @param UserInterface $user      user
     * @param string        $aclRoleId aclRoleId
     *
     * @return Result
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function add(
        UserInterface $user,
        $aclRoleId
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * remove
     *
     * @param UserInterface $user      user
     * @param string        $aclRoleId aclRoleId
     *
     * @return Result
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function remove(
        UserInterface $user,
        $aclRoleId
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * create
     *
     * @param UserInterface $user  user
     * @param array         $roles roles
     *
     * @return Result
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function create(
        UserInterface $user,
        $roles = []
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * read
     *
     * @param UserInterface $user user
     *
     * @return Result
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function read(UserInterface $user)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * update
     *
     * @param UserInterface $user  user
     * @param array         $roles roles
     *
     * @return Result
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function update(
        UserInterface $user,
        $roles = []
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * delete
     *
     * @param UserInterface $user  user
     * @param array         $roles roles
     *
     * @return Result
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function delete(
        UserInterface $user,
        $roles = []
    ) {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * canAdd
     *
     * @param UserInterface $user user
     * @param string        $role role id
     *
     * @return bool
     */
    public function canAdd(
        UserInterface $user,
        $role
    ) {
        $id = $user->getId();

        if (empty($id)) {
            return false;
        }

        $availableRoles = $this->getAvailableRoles();

        if (!in_array(
            $role,
            $availableRoles
        )
        ) {
            return false;
        }

        return true;
    }

    /**
     * canRemove
     *
     * @param UserInterface $user user
     * @param string        $role role id
     *
     * @return bool
     */
    public function canRemove(
        UserInterface $user,
        $role
    ) {
        $id = $user->getId();

        if (empty($id)) {
            return false;
        }

        return true;
    }
}
