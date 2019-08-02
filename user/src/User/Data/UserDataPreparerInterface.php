<?php

namespace RcmUser\User\Data;

use RcmUser\User\Entity\UserInterface;

/**
 * Interface UserDataPreparerInterface
 *
 * UserDataPreparerInterface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\User\Data
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface UserDataPreparerInterface
{
    /**
     * prepareUserCreate
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $userToCreate userToCreate
     *
     * @return Result
     */
    public function prepareUserCreate(
        UserInterface $requestUser,
        UserInterface $userToCreate
    );

    /**
     * prepareUserUpdate
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     * @param UserInterface $existingUser existingUser
     *
     * @return Result
     */
    public function prepareUserUpdate(
        UserInterface $requestUser,
        UserInterface $responseUser,
        UserInterface $existingUser
    );
}
