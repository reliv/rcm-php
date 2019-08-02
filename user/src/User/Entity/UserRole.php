<?php

namespace RcmUser\User\Entity;

use RcmUser\Exception\RcmUserException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UserRole implements UserRoleInterface
{

    /**
     * @var mixed $id
     */
    protected $id;
    /**
     * @var mixed $userId
     */
    protected $userId;
    /**
     * @var mixed $roleId
     */
    protected $roleId;

    /**
     * setId
     *
     * @param mixed $id id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * getId
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setRoleId
     *
     * @param mixed $roleId roleId
     *
     * @return void
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    }

    /**
     * getRoleId
     *
     * @return mixed
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * setUserId
     *
     * @param mixed $userId userId
     *
     * @return void
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * getUserId
     *
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * populate
     *
     * @param array $data data
     *
     * @return void
     * @throws RcmUserException
     */
    public function populate($data = [])
    {
        if (($data instanceof UserRole)) {
            $this->setId($data->getId());
            $this->setRoleId($data->getRoleId());
            $this->setUserId($data->getUserId());

            return;
        }

        if (is_array($data)) {
            if (isset($data['id'])) {
                $this->setId($data['id']);
            }
            if (isset($data['roleId'])) {
                $this->setRoleId($data['roleId']);
            }
            if (isset($data['userId'])) {
                $this->setUserId($data['userId']);
            }

            return;
        }

        throw new RcmUserException('User role data could not be populated, date format not supported');
    }

    /**
     * jsonSerialize
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass();
        $obj->id = $this->getId();
        $obj->roleId = $this->getRoleId();
        $obj->userId = $this->getUserId();

        return $obj;
    }
}
