<?php

namespace RcmUser\User\Data;

use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Result;
use Zend\Crypt\Password\PasswordInterface;

/**
 * Class DbUserDataPreparer
 *
 * DbUserDataPreparer
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
class DbUserDataPreparer implements UserDataPreparerInterface
{
    /**
     * @var PasswordInterface $encryptor
     */
    protected $encryptor;

    /**
     * setEncryptor
     *
     * @param PasswordInterface $encryptor encryptor
     *
     * @return void
     */
    public function setEncryptor(PasswordInterface $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * getEncryptor
     *
     * @return PasswordInterface
     */
    public function getEncryptor()
    {
        return $this->encryptor;
    }

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

        $responseUser->setId($this->buildId());
        $responseUser->setPassword(
            $this->getEncryptor()->create($requestUser->getPassword())
        );
        $state = $responseUser->getState();
        if (empty($state)) {
            $responseUser->setState(UserInterface::STATE_DISABLED);
        }

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
        // PASSWORD CHECKS
        $requestPassword = $requestUser->getPassword();
        $existingPassword = $existingUser->getPassword();
        //$hashedPassword = $existingPassword;

        // if password changed
        if ($existingPassword !== $requestPassword) {
            $hashedPassword = $this->getEncryptor()->create($requestPassword);
            $responseUser->setPassword($hashedPassword);
        }

        // STATE
        $requestState = $requestUser->getState();
        $existingState = $existingUser->getState();

        if ($requestState !== $existingState) {
            $responseUser->setState($requestState);
        }

        return new Result($responseUser);
    }

    /**
     * isValidCredential
     *
     * @param UserInterface $credentialUser credentialUser
     * @param UserInterface $existingUser   existingUser
     *
     * @return bool

    public function isValidCredential(User $credentialUser, User $existingUser)
     * {
     * $existingHash = $existingUser->getPassword();
     *
     * $credential = $credentialUser->getPassword();
     *
     * $isValid = $this->getEncryptor()->verify($credential, $existingHash);
     *
     * return $isValid;
     * }
     */

    /**
     * buildId
     *
     * @return string
     */
    public function buildId()
    {
        return $this->guidv4();
    }

    /**
     * guidv4: UUID generation
     *
     * @return string
     */
    public function guidv4()
    {
        $data = openssl_random_pseudo_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0010
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf(
            '%s%s-%s-%s-%s-%s%s%s',
            str_split(
                bin2hex($data),
                4
            )
        );
    }
}
