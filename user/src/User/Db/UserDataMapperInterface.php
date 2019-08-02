<?php

namespace RcmUser\User\Db;

use RcmUser\User\Entity\UserInterface;

/**
 * Interface UserDataMapperInterface
 *
 * UserDataMapperInterface
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
interface UserDataMapperInterface
{
    /**
     * fetchAll
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    array (
     *        0 => RcmUser\User\Entity\User,
     *    );
     *
     *    -- fail
     *    array()
     *
     * @param array $options options
     *
     * @return \RcmUser\User\Result
     */
    public function fetchAll($options = []);

    /**
     * fetchById
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    RcmUser\User\Entity\User,
     *
     *    -- fail
     *    null
     *
     * @param mixed $id id
     *
     * @return \RcmUser\User\Result
     */
    public function fetchById($id);

    /**
     * fetchByUsername
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    RcmUser\User\Entity\User,
     *
     *    -- fail
     *    null
     *
     * @param string $username username
     *
     * @return \RcmUser\User\Result
     */
    public function fetchByUsername($username);

    /**
     * create
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    RcmUser\User\Entity\User,
     *
     *    -- fail
     *    null
     *
     * @param UserInterface $requestUser  User new data (AKA request)
     * @param UserInterface $responseUser User new data prepared (VIA Event Listeners)
     *
     * @return \RcmUser\User\Result Result::user = $responseUser or null
     */
    public function create(
        UserInterface $requestUser,
        UserInterface $responseUser
    );

    /**
     * read
     *
     * RETURN DATA FORMAT:
     *
     *    -- success
     *    RcmUser\User\Entity\User,
     *
     *    -- fail
     *    null
     *
     * @param UserInterface $requestUser  User read data (AKA request)
     * @param UserInterface $responseUser User read data prepared (VIA Event Listeners)
     *
     * @return \RcmUser\User\Result Result::user = $responseUser or null
     */
    public function read(
        UserInterface $requestUser,
        UserInterface $responseUser
    );

    /**
     * update
     *
     * RETURN DATA FORMAT:
     *
     *    -- success (updated user)
     *    RcmUser\User\Entity\User,
     *
     *    -- fail (request user)
     *    RcmUser\User\Entity\User,
     *
     * @param UserInterface $requestUser  User update data (AKA request)
     * @param UserInterface $responseUser User update data prepared (VIA Event Listeners)
     * @param UserInterface $existingUser User that currently exists
     *
     * @return \RcmUser\User\Result Result::user = $responseUser or null
     */
    public function update(
        UserInterface $requestUser,
        UserInterface $responseUser,
        UserInterface $existingUser
    );

    /**
     * delete
     *
     * RETURN DATA FORMAT:
     *
     *    -- success (default guest user)
     *    RcmUser\User\Entity\User,
     *
     *    -- fail (request user)
     *    RcmUser\User\Entity\User,
     *
     * @param UserInterface $requestUser  User delete data (AKA request)
     * @param UserInterface $responseUser User delete data prepared (VIA Event Listeners)
     *
     * @return \RcmUser\User\Result Result::user = $responseUser or null
     */
    public function delete(
        UserInterface $requestUser,
        UserInterface $responseUser
    );
}
