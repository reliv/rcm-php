<?php

namespace RcmUser\Service;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\Api\Acl\HasRoleBasedAccess;
use RcmUser\Api\Acl\HasRoleBasedAccessUser;
use RcmUser\Api\Acl\IsAllowed;
use RcmUser\Api\Acl\IsUserAllowed;
use RcmUser\Api\Authentication\Authenticate;
use RcmUser\Api\Authentication\ClearIdentity;
use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\Api\Authentication\HasIdentity;
use RcmUser\Api\Authentication\IsIdentity;
use RcmUser\Api\Authentication\RefreshIdentity;
use RcmUser\Api\Authentication\SetIdentity;
use RcmUser\Api\Authentication\ValidateCredentials;
use RcmUser\Api\User\BuildNewUser;
use RcmUser\Api\User\BuildUser;
use RcmUser\Api\User\CreateUser;
use RcmUser\Api\User\CreateUserResult;
use RcmUser\Api\User\DeleteUser;
use RcmUser\Api\User\DeleteUserResult;
use RcmUser\Api\User\GetUser;
use RcmUser\Api\User\GetUserById;
use RcmUser\Api\User\GetUserByUsername;
use RcmUser\Api\User\GetUserProperty;
use RcmUser\Api\User\GetUserPropertyCurrent;
use RcmUser\Api\User\ReadUser;
use RcmUser\Api\User\ReadUserResult;
use RcmUser\Api\User\UpdateUser;
use RcmUser\Api\User\UpdateUserResult;
use RcmUser\Api\User\UserExists;
use RcmUser\Exception\RcmUserException;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Result;
use Zend\Diactoros\ServerRequestFactory;

/**
 * @deprecated Use \RcmUser\Api\*
 *
 * @author     James Jervis - https://github.com/jerv13
 */
class RcmUserService
{
    protected $getUser;
    protected $getUserById;
    protected $getUserByUsername;
    protected $userExists;
    protected $readUserResult;
    protected $readUser;
    protected $createUserResult;
    protected $createUser;
    protected $updateUserResult;
    protected $updateUser;
    protected $deleteUserResult;
    protected $deleteUser;
    protected $getUserProperty;
    protected $getUserPropertyCurrent;
    protected $validateCredentials;
    protected $authenticate;
    protected $clearIdentity;
    protected $hasIdentity;
    protected $isIdentity;
    protected $setIdentity;
    protected $refreshIdentity;
    protected $getIdentity;
    protected $isAllowed;
    protected $isUserAllowed;
    protected $hasRoleBasedAccess;
    protected $hasRoleBasedAccessUser;
    protected $buildNewUser;
    protected $buildUser;

    /**
     * @param GetUser                $getUser
     * @param GetUserById            $getUserById
     * @param GetUserByUsername      $getUserByUsername
     * @param UserExists             $userExists
     * @param ReadUserResult         $readUserResult
     * @param ReadUser               $readUser
     * @param CreateUserResult       $createUserResult
     * @param CreateUser             $createUser
     * @param UpdateUserResult       $updateUserResult
     * @param UpdateUser             $updateUser
     * @param DeleteUserResult       $deleteUserResult
     * @param DeleteUser             $deleteUser
     * @param GetUserProperty        $getUserProperty
     * @param GetUserPropertyCurrent $getUserPropertyCurrent
     * @param ValidateCredentials    $validateCredentials
     * @param Authenticate           $authenticate
     * @param ClearIdentity          $clearIdentity
     * @param HasIdentity            $hasIdentity
     * @param IsIdentity             $isIdentity
     * @param SetIdentity            $setIdentity
     * @param RefreshIdentity        $refreshIdentity
     * @param GetIdentity            $getIdentity
     * @param IsAllowed              $isAllowed
     * @param IsUserAllowed          $isUserAllowed
     * @param HasRoleBasedAccess     $hasRoleBasedAccess
     * @param HasRoleBasedAccessUser $hasRoleBasedAccessUser
     * @param BuildNewUser           $buildNewUser
     * @param BuildUser              $buildUser
     */
    public function __construct(
        GetUser $getUser,
        GetUserById $getUserById,
        GetUserByUsername $getUserByUsername,
        UserExists $userExists,
        ReadUserResult $readUserResult,
        ReadUser $readUser,
        CreateUserResult $createUserResult,
        CreateUser $createUser,
        UpdateUserResult $updateUserResult,
        UpdateUser $updateUser,
        DeleteUserResult $deleteUserResult,
        DeleteUser $deleteUser,
        GetUserProperty $getUserProperty,
        GetUserPropertyCurrent $getUserPropertyCurrent,
        ValidateCredentials $validateCredentials,
        Authenticate $authenticate,
        ClearIdentity $clearIdentity,
        HasIdentity $hasIdentity,
        IsIdentity $isIdentity,
        SetIdentity $setIdentity,
        RefreshIdentity $refreshIdentity,
        GetIdentity $getIdentity,
        IsAllowed $isAllowed,
        IsUserAllowed $isUserAllowed,
        HasRoleBasedAccess $hasRoleBasedAccess,
        HasRoleBasedAccessUser $hasRoleBasedAccessUser,
        BuildNewUser $buildNewUser,
        BuildUser $buildUser
    ) {
        $this->getUser = $getUser;
        $this->getUserById = $getUserById;
        $this->getUserByUsername = $getUserByUsername;
        $this->userExists = $userExists;
        $this->readUserResult = $readUserResult;
        $this->readUser = $readUser;
        $this->createUserResult = $createUserResult;
        $this->createUser = $createUser;
        $this->updateUserResult = $updateUserResult;
        $this->updateUser = $updateUser;
        $this->deleteUserResult = $deleteUserResult;
        $this->deleteUser = $deleteUser;
        $this->getUserProperty = $getUserProperty;
        $this->getUserPropertyCurrent = $getUserPropertyCurrent;
        $this->validateCredentials = $validateCredentials;
        $this->authenticate = $authenticate;
        $this->clearIdentity = $clearIdentity;
        $this->hasIdentity = $hasIdentity;
        $this->isIdentity = $isIdentity;
        $this->setIdentity = $setIdentity;
        $this->refreshIdentity = $refreshIdentity;
        $this->getIdentity = $getIdentity;
        $this->isAllowed = $isAllowed;
        $this->isUserAllowed = $isUserAllowed;
        $this->hasRoleBasedAccess = $hasRoleBasedAccess;
        $this->hasRoleBasedAccessUser = $hasRoleBasedAccessUser;
        $this->buildNewUser = $buildNewUser;
        $this->buildUser = $buildUser;
    }

    /*
     * ACL
     * @var AuthorizeService
     */
    protected $authorizeService;

    /**
     * @deprecated
     * getAuthorizeService: ACL service
     *
     * @return AuthorizeService
     */
    public function getAuthorizeService()
    {
        return $this->authorizeService;
    }

    /** HELPERS ***************************************/

    /**
     * @deprecated Use \RcmUser\Api\User\GetUser
     * getUser
     * returns a user from the data source
     * based on the data in the provided User object (User::id and User::username)
     *
     * @param UserInterface $requestUser request user object
     *
     * @return null|UserInterface
     */
    public function getUser(UserInterface $requestUser)
    {
        return $this->getUser->__invoke($requestUser);
    }

    /**
     * @deprecated Use \RcmUser\Api\User\GetUserById
     * getUserById
     *
     * @param $userId
     *
     * @return null|UserInterface
     */
    public function getUserById($userId)
    {
        return $this->getUserById->__invoke($userId);
    }

    /**
     * @deprecated RcmUser\Api\User\GetUserByUsername
     * getUserByUsername
     *
     * @param string $username
     *
     * @return null|UserInterface
     */
    public function getUserByUsername($username)
    {
        return $this->getUserByUsername->__invoke($username);
    }

    /**
     * @deprecated Use \RcmUser\Api\User\UserExists
     * userExists
     * returns true if the user exists in the data source
     *
     * @param UserInterface $requestUser request user object
     *
     * @return bool
     */
    public function userExists(UserInterface $requestUser)
    {
        return $this->userExists->__invoke($requestUser);
    }

    /* CRUD HELPERS ***********************************/

    /**
     * @deprecated Use \RcmUser\Api\User\ReadUser | \RcmUser\Api\User\ReadUserResult
     * readUser
     *
     * @param UserInterface $requestUser   request user object
     * @param bool          $includeResult If true, will return data in result object
     *
     * @return Result|UserInterface|null
     */
    public function readUser(
        UserInterface $requestUser,
        $includeResult = true
    ) {
        if ($includeResult) {
            return $this->readUserResult->__invoke($requestUser);
        } else {
            return $this->readUser->__invoke($requestUser);
        }
    }

    /**
     * @deprecated Use \RcmUser\Api\User\CreateUser | \RcmUser\Api\User\CreateUserResult
     * createUser
     *
     * @param UserInterface $requestUser   request user object
     * @param bool          $includeResult If true, will return data in result object
     *
     * @return Result|UserInterface|null
     */
    public function createUser(
        UserInterface $requestUser,
        $includeResult = true
    ) {
        if ($includeResult) {
            return $this->createUserResult->__invoke($requestUser);
        } else {
            return $this->createUser->__invoke($requestUser);
        }
    }

    /**
     * @deprecated Use \RcmUser\Api\User\UpdateUser | \RcmUser\Api\User\UpdateUserResult
     * updateUser
     *
     * @param UserInterface $requestUser   request user object
     * @param bool          $includeResult If true, will return data in result object
     *
     * @return Result|UserInterface|null
     */
    public function updateUser(
        UserInterface $requestUser,
        $includeResult = true
    ) {
        if ($includeResult) {
            return $this->updateUserResult->__invoke($requestUser);
        } else {
            return $this->updateUser->__invoke($requestUser);
        }
    }

    /**
     * @deprecated Use \RcmUser\Api\User\DeleteUser | \RcmUser\Api\User\DeleteUserResult
     * deleteUser
     *
     * @param UserInterface $requestUser   request user object
     * @param bool          $includeResult If true, will return data in result object
     *
     * @return Result|UserInterface|null
     */
    public function deleteUser(
        UserInterface $requestUser,
        $includeResult = true
    ) {
        if ($includeResult) {
            return $this->deleteUserResult->__invoke($requestUser);
        } else {
            return $this->deleteUser->__invoke($requestUser);
        }
    }

    /* PROPERTY HELPERS *******************************/

    /**
     * @deprecated Use \RcmUser\Api\User\GetUserProperty
     * getUserProperty
     * OnDemand loading of a user property.
     * Is a way of populating User::property using events.
     * Some user properties are not loaded with the user to increase speed.
     * Use this method to load these properties.
     *
     * @param UserInterface $user              request user object
     * @param string        $propertyNameSpace unique id of the requested property
     * @param mixed         $default           return value if property not set
     * @param bool          $refresh           will force retrieval of property
     *
     * @return mixed
     */
    public function getUserProperty(
        UserInterface $user,
        $propertyNameSpace,
        $default = null,
        $refresh = false
    ) {
        return $this->getUserProperty->__invoke(
            $user,
            $propertyNameSpace,
            $default,
            $refresh
        );
    }

    /**
     * @deprecated Use \RcmUser\Api\User\GetUserPropertyCurrent
     * getCurrentUserProperty
     *
     * @param string $propertyNameSpace propertyNameSpace
     * @param mixed  $default           return value if property not set
     * @param bool   $refresh           refresh
     *
     * @return mixed
     */
    public function getCurrentUserProperty(
        $propertyNameSpace,
        $default = null,
        $refresh = false
    ) {
        return $this->getUserPropertyCurrent->__invoke(
            $this->getPsrRequest(),
            $propertyNameSpace,
            $default,
            $refresh
        );
    }

    /**
     * @deprecated NOT IMPLEMENTED
     * disableUser @todo WRITE THIS
     *
     * @param UserInterface $user user
     *
     * @return void
     */
    public function disableUser(UserInterface $user)
    {
    }

    /* AUTHENTICATION HELPERS ********************************/

    /**
     * @deprecated Use RcmUser\Api\Authentication\ValidateCredentials
     * validateCredentials
     * Allows the validation of user credentials (username and password)
     * without creating an auth session.
     * Helpful for doing non-login authentication checks.
     *
     * @param UserInterface $requestUser request user object
     *
     * @return \Zend\Authentication\Result
     */
    public function validateCredentials(UserInterface $requestUser)
    {
        return $this->validateCredentials->__invoke($requestUser);
    }

    /**
     * @deprecated Use \RcmUser\Api\Authentication\Authenticate
     * authenticate
     * Creates auth session (logs in user)
     * if credentials provided in the User object are valid.
     *
     * @param UserInterface $requestUser request user object
     *
     * @return \Zend\Authentication\Result
     */
    public function authenticate(UserInterface $requestUser)
    {
        return $this->authenticate->__invoke(
            $this->getPsrRequest(),
            $requestUser
        );
    }

    /**
     * @deprecated Use \RcmUser\Api\Authentication\ClearIdentity
     * clearIdentity
     * Clears auth session (logs out user)
     *
     * @return void
     */
    public function clearIdentity()
    {
        $this->clearIdentity->__invoke(
            $this->getPsrRequest()
        );
    }

    /**
     * @deprecated Use \RcmUser\Api\Authentication\HasIdentity
     * hasIdentity
     * Check if any User is auth'ed (logged in)
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->hasIdentity->__invoke(
            $this->getPsrRequest()
        );
    }

    /**
     * @deprecated Use \RcmUser\Api\Authentication\IsIdentity
     * isIdentity
     * Check if the requested user in the user that is currently in the auth session
     *
     * @param UserInterface $user request user object
     *
     * @return bool
     */
    public function isIdentity(UserInterface $user)
    {
        return $this->isIdentity->__invoke(
            $this->getPsrRequest(),
            $user
        );
    }

    /**
     * @deprecated Use \RcmUser\Api\Authentication\SetIdentity
     * setIdentity
     * Force a User into the auth'd session.
     * - WARNING: this by-passes the authentication process
     *            and should only be used with extreme caution
     *
     * @param UserInterface $user request user object
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function setIdentity(UserInterface $user)
    {
        $this->setIdentity->__invoke(
            $this->getPsrRequest(),
            $user
        );
    }

    /**
     * @deprecated Use \RcmUser\Api\Authentication\RefreshIdentity
     * refreshIdentity
     * Will reload the current User that is Auth'd into the auth'd session.
     * Is a way of refreshing the session user without log-out, then log-in
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function refreshIdentity()
    {
        $this->refreshIdentity->__invoke(
            $this->getPsrRequest()
        );
    }

    /**
     * @deprecated Use \RcmUser\Api\Authentication\GetIdentity
     * getIdentity
     * Get the current User (logged in User) from Auth'd session
     * or returns $default is there is no User Auth'd
     *
     * @param mixed $default return this value if no User is auth'd
     *
     * @return UserInterface|null
     */
    public function getIdentity($default = null)
    {
        return $this->getIdentity->__invoke(
            $this->getPsrRequest(),
            $default
        );
    }

    /**
     * @deprecated Use \RcmUser\Api\Authentication\GetIdentity
     * getCurrentUser
     *  - @alias getIdentity
     *
     * @param mixed $default default
     *
     * @return UserInterface|null
     */
    public function getCurrentUser($default = null)
    {
        return $this->getIdentity($default);
    }

    //@todo implement guestIdentity
    // - if getIdentity is empty return guest and save updates in session
    // on login we can sync the guest user or the session user as needed

    /* ACL HELPERS ********************************/

    /**
     * @deprecated Use \RcmUser\Api\Acl\IsAllowed
     * isAllowed
     * Check if the current Auth'd User has
     * access to a resource with a privilege provided by provider id.
     * This is use to validate a users access
     * based on their role and the rules set by ACL
     *
     * @param string $resourceId a string resource id as defined by a provider
     * @param string $privilege  privilege of the resource to check
     * @param string $providerId @deprecated No Longer Required
     *
     * @return bool
     */
    public function isAllowed(
        $resourceId,
        $privilege = null,
        $providerId = null
    ) {
        return $this->isAllowed->__invoke(
            $this->getPsrRequest(),
            $resourceId,
            $privilege
        );
    }

    /**
     * @deprecated Use \RcmUser\Api\Acl\IsUserAllowed
     * isUserAllowed
     * Check if the current Auth'd User has
     * access to a resource with a privilege provided by provider id.
     * This is use to validate a users access
     * based on their role and the rules set by ACL
     *
     * @param string        $resourceId a string resource id as defined by a provider
     * @param string        $privilege  privilege of the resource to check
     * @param string        $providerId @deprecated No Longer Required
     * @param UserInterface $user       request user object
     *
     * @return bool
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function isUserAllowed(
        $resourceId,
        $privilege = null,
        $providerId = null,
        $user = null
    ) {
        return $this->isUserAllowed->__invoke(
            $user,
            $resourceId,
            $privilege
        );
    }

    /**
     * @deprecated Use \RcmUser\Api\Acl\HasRoleBasedAccess
     * hasRoleBasedAccess
     * Check if current user has access based on role inheritance
     *
     * @param $roleId
     *
     * @return bool
     */
    public function hasRoleBasedAccess($roleId)
    {
        return $this->hasRoleBasedAccess->__invoke(
            $this->getPsrRequest(),
            $roleId
        );
    }

    /**
     * @deprecated Use \RcmUser\Api\Acl\HasRoleBasedAccessUser
     * hasUserRoleBasedAccess -
     * Check if a user has access based on role inheritance
     *
     * @param UserInterface $user
     * @param string        $roleId
     *
     * @return bool
     */
    public function hasUserRoleBasedAccess($user, $roleId)
    {
        return $this->hasRoleBasedAccessUser->__invoke(
            $user,
            $roleId
        );
    }

    /* UTILITIES **************************************/
    /**
     * @deprecated Use \RcmUser\Api\User\BuildNewUser;
     * buildNewUser
     * Factory method to build new User object
     * populated with defaults from event listeners
     *
     * @param array $options
     *
     * @return UserInterface
     */
    public function buildNewUser(array $options = [])
    {
        return $this->buildNewUser->__invoke($options);
    }

    /**
     * @deprecated Use \RcmUser\Api\User\BuildUser
     * buildUser
     * Populate a User with defaults from event listeners
     *
     * @param UserInterface $user request user object
     * @param array         $options
     *
     * @return UserInterface
     * @throws RcmUserException
     */
    public function buildUser(UserInterface $user, array $options = [])
    {
        return $this->buildUser->__invoke(
            $user,
            $options
        );
    }

    /**
     * @return ServerRequestInterface
     */
    protected function getPsrRequest()
    {
        return ServerRequestFactory::fromGlobals();
    }
}
