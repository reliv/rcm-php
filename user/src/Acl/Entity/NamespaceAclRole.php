<?php

namespace RcmUser\Acl\Entity;

/**
 * Class NamespaceAclRole
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class NamespaceAclRole extends AclRole
{
    /**
     * A namespace defining location in the tree of roles guest.user.admin
     * @var string
     */
    protected $namespace = '';

    /**
     * Super admin role
     * @var bool
     */
    protected $superAdminRoleId = null;

    /**
     * Guest role
     * @var bool
     */
    protected $guestRoleId = null;

    /**
     * NamespaceAclRole constructor.
     *
     * @param null|string $superAdminRoleId
     * @param null|string $guestRoleId
     */
    public function __construct($superAdminRoleId, $guestRoleId)
    {
        $this->superAdminRoleId = $superAdminRoleId;
        $this->guestRoleId = $guestRoleId;
    }

    /**
     * getNamespace
     *
     * @return string
     */
    public function getNamespace()
    {
        if (empty($this->namespace)) {
            return $this->getRoleId();
        }
        return $this->namespace;
    }

    /**
     * setNamespace -
     *
     * @param $namespace
     *
     * @return void
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * isSuperAdminRole
     *
     * @return mixed
     */
    public function isSuperAdminRole()
    {
        return ($this->roleId == $this->superAdminRoleId);
    }

    /**
     * isGuestRole
     *
     * @return bool
     */
    public function isGuestRole()
    {
        return ($this->roleId == $this->guestRoleId);
    }

    /**
     * jsonSerialize
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        $obj = parent::jsonSerialize();
        $obj->namespace = $this->getNamespace();
        $obj->isGuestRole = $this->isGuestRole();
        $obj->isSuperAdminRole = $this->isSuperAdminRole();

        return $obj;
    }
}
