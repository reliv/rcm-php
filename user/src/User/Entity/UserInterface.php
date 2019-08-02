<?php

namespace RcmUser\User\Entity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface UserInterface extends \IteratorAggregate, \JsonSerializable
{
    /**
     * PASSWORD_OBFUSCATE
     */
    const PASSWORD_OBFUSCATE = null;
    /**
     * At the core, we only care if the user is disabled,
     * any other value means enabled and the value is up to the implementation
     */
    const STATE_DISABLED = 'disabled';

    /**
     * set a property
     *
     * @param string $property
     * @param mixed $value
     *
     * @return bool
     */
    public function set($property, $value);

    /**
     * get a property
     *
     * @param string $property
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($property, $default = null);

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
     * setPassword
     *
     * @param string $password password
     *
     * @return void
     */
    public function setPassword($password);

    /**
     * getPassword
     *
     * @return string
     */
    public function getPassword();

    /**
     * setUsername
     *
     * @param string $username username
     *
     * @return void
     */
    public function setUsername($username);

    /**
     * getUsername
     *
     * @return string
     */
    public function getUsername();

    /**
     * setProperties
     *
     * @param array $properties properties
     *
     * @return void
     */
    public function setProperties($properties);

    /**
     * getProperties
     *
     * @return array
     */
    public function getProperties();

    /**
     * setProperty
     *
     * @param string $propertyId propertyId
     * @param mixed  $value      value
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function setProperty(
        $propertyId,
        $value
    );

    /**
     * getProperty
     *
     * @param string $propertyId propertyId
     * @param null   $default    default if not found
     *
     * @return null
     */
    public function getProperty(
        $propertyId,
        $default = null
    );

    /**
     * setState
     *
     * @param string $state state
     *
     * @return mixed
     */
    public function setState($state);

    /**
     * getState
     *
     * @return string
     */
    public function getState();

    /**
     * setEmail
     *
     * @param string $email valid email
     *
     * @return void
     */
    public function setEmail($email);

    /**
     * getEmail
     *
     * @return string
     */
    public function getEmail();

    /**
     * setName
     *
     * @param string $name Display name
     *
     * @return void
     */
    public function setName($name);

    /**
     * getName
     *
     * @return string
     */
    public function getName();

    /**
     * populate
     *
     * @param UserInterface|array $data    data as User or array
     * @param array               $exclude list of object properties to ignore (not populate)
     *
     * @return mixed|void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function populate(
        $data,
        $exclude = []
    );

    /**
     * populateFromObject
     *
     * @param UserInterface $object
     *
     * @return void
     */
    public function populateFromObject(UserInterface $object);

    /**
     * getIterator
     *
     * @return \Traversable
     */
    public function getIterator();

    /**
     * toArray
     *
     * @return array
     */
    public function toArray();
}
