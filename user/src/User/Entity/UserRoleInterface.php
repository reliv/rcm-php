<?php

namespace RcmUser\User\Entity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface UserRoleInterface
{
    /**
     * setId
     *
     * @param mixed $id id
     *
     * @return void
     */
    public function setId($id);

    /**
     * getId
     *
     * @return mixed
     */
    public function getId();

    /**
     * setRoleId
     *
     * @param mixed $roleId roleId
     *
     * @return void
     */
    public function setRoleId($roleId);

    /**
     * getRoleId
     *
     * @return mixed
     */
    public function getRoleId();

    /**
     * setUserId
     *
     * @param mixed $userId userId
     *
     * @return void
     */
    public function setUserId($userId);

    /**
     * getUserId
     *
     * @return mixed
     */
    public function getUserId();
}
