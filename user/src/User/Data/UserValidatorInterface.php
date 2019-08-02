<?php

namespace RcmUser\User\Data;

use RcmUser\User\Entity\UserInterface;

/**
 * Interface UserValidatorInterface
 *
 * UserValidatorInterface
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
interface UserValidatorInterface
{
    /**
     * validateCreateUser
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     *
     * @return Result
     */
    public function validateCreateUser(
        UserInterface $requestUser,
        UserInterface $responseUser
    );

    /**
     * validateUpdateUser
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     * @param UserInterface $existingUser existingUser
     *
     * @return Result
     */
    public function validateUpdateUser(
        UserInterface $requestUser,
        UserInterface $responseUser,
        UserInterface $existingUser
    );
}
