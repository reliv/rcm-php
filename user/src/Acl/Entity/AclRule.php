<?php

namespace RcmUser\Acl\Entity;

use RcmUser\Acl\Filter\ResourceIdFilter;
use RcmUser\Acl\Validator\ResourceIdValidator;
use RcmUser\Exception\RcmUserException;
use Zend\Permissions\Acl\Assertion\AssertionInterface;

/**
 * AclRule
 *
 * AclRule entity
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
class AclRule implements \JsonSerializable, \IteratorAggregate
{
    /**
     * string RULE_ALLOW
     */
    const RULE_ALLOW = 'allow';
    /**
     * string RULE_DENY
     */
    const RULE_DENY = 'deny';
    /**
     * string RULE_IGNORE
     * this rule is a way of disabling a rule without deleting it
     */
    const RULE_IGNORE = 'ignore';

    /**
     * @var string $rule
     */
    protected $rule = null;

    /**
     * @var string $roleId
     */
    protected $roleId = null;

    /**
     * @var string $resourceId
     */
    protected $resourceId = null;

    /**
     * @deprecated Use $privileges
     * @var string $privilege
     */
    protected $privilege = null;

    /**
     * @var array $privileges
     */
    protected $privileges = [];

    /**
     * @var AssertionInterface/string $assertion
     */
    protected $assertion = null;

    /**
     * setRule
     *
     * @param string $rule rule
     *
     * @return void
     * @throws RcmUserException
     */
    public function setRule($rule)
    {
        if (!$this->isValidRule($rule)) {
            throw new RcmUserException("Rule ({$rule}) is invalid.");
        }

        $this->rule = $rule;
    }

    /**
     * getRule
     *
     * @return mixed
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * setRoleId
     *
     * @param string $roleId roleId
     *
     * @return void
     */
    public function setRoleId($roleId)
    {
        $roleId = strtolower((string)$roleId);

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
     * setResourceId
     *
     * @param string $resourceId
     *
     * @return void
     * @throws RcmUserException
     */
    public function setResourceId($resourceId)
    {
        $resourceId = ResourceIdFilter::filter($resourceId);

        if (!ResourceIdValidator::isValid($resourceId) || empty($resourceId)) {
            throw new RcmUserException(
                "Resource resourceId ({$resourceId}) is invalid for rule."
            );
        }

        $this->resourceId = $resourceId;
    }

    /**
     * getResource
     *
     * @return string
     */
    public function getResourceId()
    {
        return ResourceIdFilter::filter($this->resourceId);
    }

    /**
     * setPrivilege
     *
     * @param string $privilege privilege
     *
     * @return void
     */
    public function setPrivilege($privilege)
    {
        if (empty($privilege)) {
            return;
        }

        $privilege = (string)$privilege;

        $privileges = $this->privileges;

        if (!in_array($privilege, $privileges)) {
            $privileges[] = $privilege;
            $this->setPrivileges($privileges);
        }
    }

    /**
     * getPrivilege
     *
     * @param null $privilege @bc Default of null is only here to support older versions
     *
     * @return array|null
     */
    public function getPrivilege($privilege = null)
    {
        $privileges = $this->getPrivileges();
        // @bc This is only here to support older versions
        if ($privilege === null) {
            if (count($privileges) > 0) {
                return array_values($privileges)[0]; // $privileges[0];
            }

            return null;
        }

        $privilege = (string)$privilege;

        $key = array_search($privilege, $privileges);
        if ($key !== false) {
            return $privileges;
        }

        return null;
    }

    /**
     * setPrivileges
     *
     * @return array
     */
    public function setPrivileges(array $privileges)
    {
        $this->privileges = $privileges;
        // To standardize ordering for easy querying
        sort($this->privileges);
    }

    /**
     * getPrivileges
     *
     * @return array|null
     */
    public function getPrivileges()
    {
        // @bc This is only here to support older versions
        if ($this->privilege !== null) {
            $this->setPrivilege($this->privilege);
        }

        return $this->privileges;
    }

    /**
     * setAssertion
     * \Zend\Permissions\Acl\Assertion\AssertionInterface
     *
     * @param AssertionInterface|string $assertion assertion
     *
     * @return void
     */
    public function setAssertion($assertion)
    {
        $this->assertion = $assertion;
    }

    /**
     * getAssertion
     *
     * @return AssertionInterface|string
     */
    public function getAssertion()
    {
        return $this->assertion;
    }

    /**
     * isValidRule
     *
     * @param string $rule rule
     *
     * @return bool
     */
    public function isValidRule($rule)
    {
        if ($rule == self::RULE_ALLOW
            || $rule == self::RULE_DENY
            || $rule == self::RULE_IGNORE
        ) {
            return true;
        }

        return false;
    }

    /**
     * populate
     *
     * @param array|AclRule $data data
     *
     * @return void
     * @throws RcmUserException
     */
    public function populate($data = [])
    {
        if (($data instanceof AclRule)) {
            $this->setRule($data->getRule());
            $this->setRoleId($data->getRoleId());
            $this->setResourceId($data->getResourceId());
            $this->setPrivileges($data->getPrivileges());
            $this->setAssertion($data->getAssertion());

            return;
        }

        if (is_array($data)) {
            if (isset($data['rule'])) {
                $this->setRule($data['rule']);
            }
            if (isset($data['roleId'])) {
                $this->setRoleId($data['roleId']);
            }
            if (isset($data['resourceId'])) {
                $this->setResourceId($data['resourceId']);
            }
            // @bc This is only here to support older versions
            if (isset($data['privilege'])) {
                $this->setPrivilege($data['privilege']);
            }
            if (isset($data['privileges'])) {
                $this->setPrivileges($data['privileges']);
            }
            if (isset($data['assertion'])) {
                $this->setAssertion($data['assertion']);
            }

            return;
        }

        throw new RcmUserException(
            'Rule data could not be populated, data format not supported'
        );
    }

    /**
     * jsonSerialize
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass();
        $obj->rule = $this->getRule();
        $obj->roleId = $this->getRoleId();
        $obj->resourceId = $this->getResourceId();
        $obj->privileges = $this->getPrivileges();

        // @bc This is only here to support older versions
        $obj->_deprecated_privilege = "privilege is deprecate, use privileges";
        $obj->privilege = $this->getPrivilege();

        return $obj;
    }

    /**
     * getIterator
     *
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator(get_object_vars($this));
    }
}
