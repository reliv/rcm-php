<?php

namespace RcmUser\Acl\Entity;

use RcmUser\Exception\RcmUserException;

/**
 * Class AclPrivilege
 *
 * AclPrivilege @todo Implement this
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AclPrivilege implements \JsonSerializable, \IteratorAggregate
{

    /**
     * @var string PRIV_DELIMITER
     */
    const PRIV_DELIMITER = ',';

    /**
     * @var array $privileges
     */
    protected $privileges = [];

    /**
     * __construct
     *
     * @param array $privileges privileges
     */
    public function __construct($privileges = [])
    {
        $this->setPrivileges($privileges);
    }

    /**
     * setPrivileges
     *
     * @param array $privileges privileges
     *
     * @return void
     */
    public function setPrivileges($privileges)
    {
        $privileges = $this->preparePrivileges($privileges);

        foreach ($privileges as $privilege) {
            $this->setPrivilege($privilege);
        }
    }

    /**
     * getPrivileges
     *
     * @return array
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * setPrivilege
     *
     * @param string $privilege privilege
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function setPrivilege($privilege)
    {
        $privilege = strtolower((string)$privilege);

        if (!$this->isValidPrivilege($privilege)) {
            throw new RcmUserException("Privilege ({$privilege}) is invalid.");
        }

        $key = array_search(
            $privilege,
            $this->privileges
        );

        if ($key === false) {
            $this->privileges[] = $privilege;
        }
    }

    /**
     * getPrivilege
     *
     * @param string $privilege privilege
     * @param null   $default   default
     *
     * @return null
     */
    public function getPrivilege(
        $privilege,
        $default = null
    ) {
        $privilege = strtolower((string)$privilege);

        $key = array_search(
            $privilege,
            $this->privileges
        );

        if ($key === false) {
            return $default;
        }

        return $this->privileges[$key];
    }

    /**
     * isValidPrivilege
     *
     * @param string $privilege privilege
     *
     * @return bool
     */
    public function isValidPrivilege($privilege)
    {
        if (preg_match(
            '/[^a-z_\-0-9]/i',
            $privilege
        )
        ) {
            return false;
        }

        return true;
    }

    /**
     * preparePrivileges
     *
     * @param array $privileges privileges
     *
     * @return array|string
     */
    public function preparePrivileges($privileges)
    {
        if (!is_array($privileges)) {
            $privileges = (string)$privileges;

            $privileges = explode(
                self::PRIV_DELIMITER,
                $privileges
            );
        }

        return $privileges;
    }

    /**
     * jsonSerialize
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        $privileges = $this->getPrivileges();

        return $privileges;
    }

    /**
     * getIterator
     *
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        $privileges = $this->getPrivileges();

        return new \ArrayIterator($privileges);
    }

    /**
     * __toString
     *
     * @return mixed
     */
    public function __toString()
    {
        $privileges = $this->getPrivileges();

        return implode(
            self::PRIV_DELIMITER,
            $privileges
        );
    }
}
