<?php

namespace RcmUser\User\Entity;

use RcmUser\Exception\RcmUserException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class UserAbstract
{
    /**
     * @var array Exclude these from the magic get
     */
    private $getSetExclude = ['iterator'];

    /**
     * @var mixed $id
     */
    protected $id = null;

    /**
     * @var string $username
     */
    protected $username = null;

    /**
     * @var string $password
     */
    protected $password = null;

    /**
     * @var string $state
     */
    protected $state = null;

    /**
     * @var string $email
     */
    protected $email = null;

    /**
     * @var string $name Display name
     */
    protected $name = null;

    /**
     * Property data injected by external sources
     *
     * @var array $properties
     */
    protected $properties = [];

    /**
     * __construct
     *
     * @param null $id id
     */
    public function __construct($id = null)
    {
        $this->setId($id);
    }

    /**
     * set a property
     *
     * @param $property
     * @param $value
     *
     * @return bool
     * @throws RcmUserException
     */
    public function set($property, $value)
    {
        if (in_array(lcfirst($property), $this->getSetExclude)) {
            return false;
        }

        $setter = 'set' . ucfirst($property);

        if (method_exists($this, $setter)) {
            $this->$setter($value);
            return true;
        }

        try {
            $this->setProperty($property, $value);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * get a property
     *
     * @param $property
     * @param $default
     *
     * @return mixed
     */
    public function get($property, $default = null)
    {
        if (in_array(lcfirst($property), $this->getSetExclude)) {
            return $default;
        }

        $getter = 'get' . ucfirst($property);

        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        return $this->getProperty($property, $default);
    }

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
     * setUsername
     *
     * @param string $username username
     *
     * @return void
     */
    public function setUsername($username)
    {
        $username = (string)$username;

        if (empty($username)) {
            $username = null;
        }
        $this->username = $username;
    }

    /**
     * getUsername
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * setPassword
     *
     * @param string $password password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $password = (string)$password;
        if (empty($password)) {
            $password = null;
        }
        $this->password = $password;
    }

    /**
     * getPassword
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * setState
     *
     * @param string $state state
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function setState($state)
    {
        $state = (string)$state;

        if (!$this->isValidState($state)) {
            throw new RcmUserException("User state is invalid: {$state}");
        }

        if (empty($state)) {
            $state = null;
        }
        $this->state = $state;
    }

    /**
     * getState
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * setEmail
     *
     * @param string $email valid email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $email = (string)$email;
        if (empty($email)) {
            $email = null;
        }
        $this->email = $email;
    }

    /**
     * getEmail
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * setName
     *
     * @param string $name Display name
     *
     * @return void
     */
    public function setName($name)
    {
        $name = (string)$name;
        if (empty($name)) {
            $name = null;
        }
        $this->name = $name;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * setProperties
     *
     * @param array $properties properties
     *
     * @return void
     */
    public function setProperties($properties)
    {
        if (empty($properties)) {
            $properties = [];
        }
        $this->properties = $properties;
    }

    /**
     * getProperties
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

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
    ) {
        if (!$this->isValidPropertyId($propertyId)) {
            throw new RcmUserException("Property Id is invalid: {$propertyId}");
        }

        $this->properties[$propertyId] = $value;
    }

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
    ) {
        if (array_key_exists($propertyId, $this->properties)) {
            return $this->properties[$propertyId];
        }

        return $default;
    }

    /**
     * isEnabled - Any state that is not disabled is considered enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getState() !== UserInterface::STATE_DISABLED;
    }

    /**
     * isValidPropertyId
     *
     * @param string $propertyId propertyId
     *
     * @return bool
     */
    public function isValidPropertyId($propertyId)
    {
        if (preg_match('/[^a-z_\-0-9]/i', $propertyId)) {
            return false;
        }

        return true;
    }

    /**
     * isValidState
     *
     * @param string $state user stateå
     *
     * @return bool
     */
    public function isValidState($state)
    {
        if (preg_match('/[^a-z_\-0-9]/i', $state)) {
            return false;
        }

        return true;
    }

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
    ) {
        if (($data instanceof UserInterface)) {
            $this->populateFromObject($data);

            return;
        }

        if (is_array($data)) {
            foreach ($data as $property => $value) {
                // Check for ignore keys
                if (in_array($property, $exclude)) {
                    continue;
                }

                $this->set($property, $value);
            }

            return;
        }

        throw new RcmUserException(
            'User data could not be populated, data format not supported'
        );
    }

    /**
     * populateFromObject
     *
     * @param UserInterface $object
     *
     * @return void
     * @throws RcmUserException
     */
    public function populateFromObject(UserInterface $object)
    {
        $this->populate($object->toArray());
    }

    /**
     * getIterator
     *
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        $arr = get_object_vars($this);

        unset($arr['getSetExclude']);

        return $arr;
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
        $obj->username = $this->getUsername();
        // Might be better way to obfuscate
        $obj->password = UserInterface::PASSWORD_OBFUSCATE;
        $obj->state = $this->getState();
        $obj->email = $this->getEmail();
        $obj->name = $this->getName();
        $obj->properties = $this->getProperties();

        return $obj;
    }

    /**
     * Merges values of the $user arg into this user if the values are not set
     *
     * @param UserInterface $user user
     *
     * @return void
     */
    public function merge(UserInterface $user)
    {
        if ($this->getId() === null) {
            $this->setId($user->getId());
        }

        if ($this->getUsername() === null) {
            $this->setUsername($user->getUsername());
        }

        if ($this->getPassword() === null) {
            $this->setPassword($user->getPassword());
        }

        if ($this->getState() === null) {
            $this->setState($user->getState());
        }

        if ($this->getEmail() === null) {
            $this->setEmail($user->getEmail());
        }

        if ($this->getName() === null) {
            $this->setName($user->getName());
        }

        $properties = $user->getProperties();
        foreach ($properties as $key => $property) {
            $userProperty = $this->getProperty($key);
            if (empty($userProperty)) {
                $this->setProperty(
                    $key,
                    $property
                );
            }
        }
    }
}
