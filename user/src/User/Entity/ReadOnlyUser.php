<?php

namespace RcmUser\User\Entity;

use RcmUser\Exception\RcmUserReadOnlyException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ReadOnlyUser extends UserAbstract implements UserInterface
{
    /**
     * @var bool
     */
    protected $locked = false;

    /**
     * __construct
     *
     * @param UserInterface $user User used to initially populate the object
     */
    public function __construct(UserInterface $user)
    {
        $this->populate($user);
        $this->locked = true;
    }

    public function set($property, $value)
    {
        if (!$this->locked) {
            return parent::set($property, $value);
        }

        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }

    /**
     * setId
     *
     * @param mixed $id id
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserReadOnlyException
     */
    public function setId($id)
    {
        if (!$this->locked) {
            parent::setId($id);

            return;
        }

        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }

    /**
     * setUsername
     *
     * @param string $username username
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserReadOnlyException
     */
    public function setUsername($username)
    {
        if (!$this->locked) {
            parent::setUsername($username);

            return;
        }

        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }

    /**
     * setPassword
     *
     * @param string $password password
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserReadOnlyException
     */
    public function setPassword($password)
    {
        if (!$this->locked) {
            parent::setPassword($password);

            return;
        }

        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }

    /**
     * setState
     *
     * @param string $state state
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserReadOnlyException
     */
    public function setState($state)
    {
        if (!$this->locked) {
            parent::setState($state);

            return;
        }

        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }

    /**
     * setEmail
     *
     * @param string $email valid email
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserReadOnlyException
     */
    public function setEmail($email)
    {
        if (!$this->locked) {
            parent::setEmail($email);

            return;
        }

        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }

    /**
     * setName
     *
     * @param string $name Display name
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserReadOnlyException
     */
    public function setName($name)
    {
        if (!$this->locked) {
            parent::setName($name);

            return;
        }

        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }

    /**
     * setProperties
     *
     * @param array $properties properties
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserReadOnlyException
     */
    public function setProperties($properties)
    {
        if (!$this->locked) {
            parent::setProperties($properties);

            return;
        }

        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }

    /**
     * setProperty
     *
     * @param string $key key
     * @param mixed  $val val
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserReadOnlyException
     */
    public function setProperty(
        $key,
        $val
    ) {
        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }

    /**
     * populate
     *
     * @param UserInterface|array $data    data as User or array
     * @param array               $exclude list of object properties to ignore (not populate)
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException|RcmUserReadOnlyException
     */
    public function populate(
        $data,
        $exclude = []
    ) {
        if (!$this->locked) {
            parent::populate($data);

            return;
        }

        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }

    /**
     * populateFromObject
     *
     * @param UserInterface $object
     *
     * @return void
     * @throws RcmUserReadOnlyException
     */
    public function populateFromObject(UserInterface $object)
    {
        if (!$this->locked) {
            parent::populateFromObject($object);

            return;
        }

        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }

    /**
     * merge
     *
     * @param UserInterface $user user
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserReadOnlyException
     */
    public function merge(UserInterface $user)
    {
        throw new RcmUserReadOnlyException('Object is READ ONLY');
    }
}
