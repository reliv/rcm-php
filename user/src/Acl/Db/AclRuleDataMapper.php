<?php

namespace RcmUser\Acl\Db;

use RcmUser\Acl\Entity\AclRule;
use RcmUser\Exception\RcmUserException;
use RcmUser\Result;

/**
 * class AclRuleDataMapper
 *
 * AclRuleDataMapper
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AclRuleDataMapper implements AclRuleDataMapperInterface
{
    /**
     * fetchAll
     *
     * @return Result
     * @throws RcmUserException
     */
    public function fetchAll()
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * fetchByRole
     *
     * @param mixed $roleId role id
     *
     * @return Result
     * @throws RcmUserException
     */
    public function fetchByRole($roleId)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * fetchByRule
     *
     * @param AclRule $rule rule
     *
     * @return Result
     * @throws RcmUserException
     */
    public function fetchByRule($rule)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * fetchByResource
     *
     * @param array $resources Resources
     *
     * @return mixed
     * @throws RcmUserException
     */
    public function fetchByResources(array $resources)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * fetchByResource
     *
     * @param string $resourceId resourceId
     *
     * @return mixed
     * @throws RcmUserException
     */
    public function fetchByResource($resourceId)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * fetchByResourcePrivilege
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *       1 => RcmUser\Acl\Entity\AclRule
     *    )
     *
     *    -- fail
     *    array()
     *
     * @param string $resourceId resourceId
     * @param mixed  $privilege  privilege
     *
     * @return Result
     * @throws RcmUserException
     */
    public function fetchByResourcePrivilege($resourceId, $privilege)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * create
     *
     * @param AclRule $aclRule acl rule
     *
     * @return Result
     * @throws RcmUserException
     */
    public function create(AclRule $aclRule)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * read
     *
     * @param AclRule $aclRule acl rule
     *
     * @return Result
     * @throws RcmUserException
     */
    public function read(AclRule $aclRule)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * update
     *
     * @param AclRule $aclRule acl rule
     *
     * @return Result
     * @throws RcmUserException
     */
    public function update(AclRule $aclRule)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }

    /**
     * delete
     *
     * @param AclRule $aclRule acl rule
     *
     * @return Result
     * @throws RcmUserException
     */
    public function delete(AclRule $aclRule)
    {
        throw new RcmUserException("Method " . __METHOD__ . " not implemented.");
    }
}
