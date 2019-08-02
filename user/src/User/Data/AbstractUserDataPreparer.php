<?php

namespace RcmUser\User\Data;

use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Result;

/**
 * Class AbstractUserDataPreparer
 *
 * AbstractUserDataPreparer
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
class AbstractUserDataPreparer implements UserDataPreparerInterface
{
    /**
     * prepareUserCreate
     *
     * @param UserInterface $requestUser  requestUser
     * @param UserInterface $responseUser responseUser
     *
     * @return Result
     */
    public function prepareUserCreate(
        UserInterface $requestUser,
        UserInterface $responseUser
    ) {
        return new Result($responseUser);
    }

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
    ) {
        return new Result($responseUser);
    }
}
