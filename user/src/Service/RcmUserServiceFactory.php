<?php

namespace RcmUser\Service;

use Interop\Container\ContainerInterface;
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
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RcmUserServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RcmUserServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return RcmUserService
     */
    public function __invoke($serviceLocator)
    {
        return new RcmUserService(
            $serviceLocator->get(GetUser::class),
            $serviceLocator->get(GetUserById::class),
            $serviceLocator->get(GetUserByUsername::class),
            $serviceLocator->get(UserExists::class),
            $serviceLocator->get(ReadUserResult::class),
            $serviceLocator->get(ReadUser::class),
            $serviceLocator->get(CreateUserResult::class),
            $serviceLocator->get(CreateUser::class),
            $serviceLocator->get(UpdateUserResult::class),
            $serviceLocator->get(UpdateUser::class),
            $serviceLocator->get(DeleteUserResult::class),
            $serviceLocator->get(DeleteUser::class),
            $serviceLocator->get(GetUserProperty::class),
            $serviceLocator->get(GetUserPropertyCurrent::class),
            $serviceLocator->get(ValidateCredentials::class),
            $serviceLocator->get(Authenticate::class),
            $serviceLocator->get(ClearIdentity::class),
            $serviceLocator->get(HasIdentity::class),
            $serviceLocator->get(IsIdentity::class),
            $serviceLocator->get(SetIdentity::class),
            $serviceLocator->get(RefreshIdentity::class),
            $serviceLocator->get(GetIdentity::class),
            $serviceLocator->get(IsAllowed::class),
            $serviceLocator->get(IsUserAllowed::class),
            $serviceLocator->get(HasRoleBasedAccess::class),
            $serviceLocator->get(HasRoleBasedAccessUser::class),
            $serviceLocator->get(BuildNewUser::class),
            $serviceLocator->get(BuildUser::class)
        );
    }
}
