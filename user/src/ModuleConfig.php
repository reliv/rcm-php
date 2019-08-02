<?php

namespace RcmUser;

use RcmUser\Api\Acl\GetRulesByResources;
use RcmUser\Api\Acl\GetRulesByResourcesBasicFactory;
use RcmUser\Api\Acl\HasRoleBasedAccess;
use RcmUser\Api\Acl\HasRoleBasedAccessBasicFactory;
use RcmUser\Api\Acl\HasRoleBasedAccessUser;
use RcmUser\Api\Acl\HasRoleBasedAccessUserBasicFactory;
use RcmUser\Api\Acl\IsAllowed;
use RcmUser\Api\Acl\IsAllowedBasicFactory;
use RcmUser\Api\Acl\IsUserAllowed;
use RcmUser\Api\Acl\IsUserAllowedBasicFactory;
use RcmUser\Api\Authentication\Authenticate;
use RcmUser\Api\Authentication\AuthenticateBasicFactory;
use RcmUser\Api\Authentication\ClearIdentity;
use RcmUser\Api\Authentication\ClearIdentityBasicFactory;
use RcmUser\Api\Authentication\GetCurrentUser;
use RcmUser\Api\Authentication\GetCurrentUserBasicFactory;
use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\Api\Authentication\GetIdentityBasicFactory;
use RcmUser\Api\Authentication\HasIdentity;
use RcmUser\Api\Authentication\HasIdentityBasicFactory;
use RcmUser\Api\Authentication\IsIdentity;
use RcmUser\Api\Authentication\IsIdentityBasicFactory;
use RcmUser\Api\Authentication\RefreshIdentity;
use RcmUser\Api\Authentication\RefreshIdentityBasicFactory;
use RcmUser\Api\Authentication\SetIdentity;
use RcmUser\Api\Authentication\SetIdentityBasicFactory;
use RcmUser\Api\Authentication\SetIdentityInsecure;
use RcmUser\Api\Authentication\SetIdentityInsecureFactory;
use RcmUser\Api\Authentication\ValidateCredentials;
use RcmUser\Api\Authentication\ValidateCredentialsBasicFactory;
use RcmUser\Api\User\BuildNewUser;
use RcmUser\Api\User\BuildNewUserBasicFactory;
use RcmUser\Api\User\BuildUser;
use RcmUser\Api\User\BuildUserBasicFactory;
use RcmUser\Api\User\CreateUser;
use RcmUser\Api\User\CreateUserBasicFactory;
use RcmUser\Api\User\CreateUserResult;
use RcmUser\Api\User\CreateUserResultBasicFactory;
use RcmUser\Api\User\DeleteUser;
use RcmUser\Api\User\DeleteUserBasicFactory;
use RcmUser\Api\User\DeleteUserResult;
use RcmUser\Api\User\DeleteUserResultBasicFactory;
use RcmUser\Api\User\GetUser;
use RcmUser\Api\User\GetUserBasicFactory;
use RcmUser\Api\User\GetUserById;
use RcmUser\Api\User\GetUserByIdBasicFactory;
use RcmUser\Api\User\GetUserByUsername;
use RcmUser\Api\User\GetUserByUsernameBasicFactory;
use RcmUser\Api\User\GetUserProperty;
use RcmUser\Api\User\GetUserPropertyBasicFactory;
use RcmUser\Api\User\GetUserPropertyCurrent;
use RcmUser\Api\User\GetUserPropertyCurrentBasicFactory;
use RcmUser\Api\User\ReadUser;
use RcmUser\Api\User\ReadUserBasicFactory;
use RcmUser\Api\User\ReadUserResult;
use RcmUser\Api\User\ReadUserResultBasicFactory;
use RcmUser\Api\User\UpdateUser;
use RcmUser\Api\User\UpdateUserBasicFactory;
use RcmUser\Api\User\UpdateUserResult;
use RcmUser\Api\User\UpdateUserResultBasicFactory;
use RcmUser\Api\User\UserExists;
use RcmUser\Api\User\UserExistsBasicFactory;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => [

                'factories' => [
                    /**
                     * API =============================================
                     */
                    GetRulesByResources::class
                    => GetRulesByResourcesBasicFactory::class,

                    HasRoleBasedAccess::class
                    => HasRoleBasedAccessBasicFactory::class,

                    HasRoleBasedAccessUser::class
                    => HasRoleBasedAccessUserBasicFactory::class,

                    IsAllowed::class
                    => IsAllowedBasicFactory::class,

                    IsUserAllowed::class
                    => IsUserAllowedBasicFactory::class,

                    Authenticate::class
                    => AuthenticateBasicFactory::class,

                    ClearIdentity::class
                    => ClearIdentityBasicFactory::class,

                    GetCurrentUser::class
                    => GetCurrentUserBasicFactory::class,

                    GetIdentity::class
                    => GetIdentityBasicFactory::class,

                    HasIdentity::class
                    => HasIdentityBasicFactory::class,

                    IsIdentity::class
                    => IsIdentityBasicFactory::class,

                    RefreshIdentity::class
                    => RefreshIdentityBasicFactory::class,

                    SetIdentity::class
                    => SetIdentityBasicFactory::class,

                    SetIdentityInsecure::class
                    => SetIdentityInsecureFactory::class,

                    ValidateCredentials::class
                    => ValidateCredentialsBasicFactory::class,

                    BuildNewUser::class
                    => BuildNewUserBasicFactory::class,

                    BuildUser::class
                    => BuildUserBasicFactory::class,

                    CreateUser::class
                    => CreateUserBasicFactory::class,

                    CreateUserResult::class
                    => CreateUserResultBasicFactory::class,

                    DeleteUser::class
                    => DeleteUserBasicFactory::class,

                    DeleteUserResult::class
                    => DeleteUserResultBasicFactory::class,

                    GetUser::class
                    => GetUserBasicFactory::class,

                    GetUserById::class
                    => GetUserByIdBasicFactory::class,

                    GetUserByUsername::class
                    => GetUserByUsernameBasicFactory::class,

                    GetUserProperty::class
                    => GetUserPropertyBasicFactory::class,

                    GetUserPropertyCurrent::class
                    => GetUserPropertyCurrentBasicFactory::class,

                    ReadUser::class
                    => ReadUserBasicFactory::class,

                    ReadUserResult::class
                    => ReadUserResultBasicFactory::class,

                    UpdateUser::class
                    => UpdateUserBasicFactory::class,

                    UpdateUserResult::class
                    => UpdateUserResultBasicFactory::class,

                    UserExists::class
                    => UserExistsBasicFactory::class,
                    /*
                     * Config
                     */
                    \RcmUser\Config\Config::class
                    => \RcmUser\Config\ConfigFactory::class,

                    \RcmUser\User\Config::class
                    => \RcmUser\User\ConfigFactory::class,

                    \RcmUser\Authentication\Config::class
                    => \RcmUser\Authentication\ConfigFactory::class,

                    \RcmUser\Acl\Config::class
                    => \RcmUser\Acl\ConfigFactory::class,

                    \RcmUser\Log\Config::class
                    => \RcmUser\Log\ConfigFactory::class,

                    /* ************************************** */
                    /* USER ********************************* */
                    /* ************************************** */

                    /*
                     * UserDataService - Core User data access service
                     * Required *
                     *
                     * This service exposes basic CRUD operations for the User objects.
                     */
                    \RcmUser\User\Service\UserDataService::class
                    => \RcmUser\User\Service\UserDataServiceFactory::class,

                    /*
                     * UserPropertyService
                     * - Allows user properties to be set by event listeners
                     * Required *
                     *
                     * This service allows User properties
                     * to be loaded on demand using event listeners.
                     * This helps reduce the size of the User object
                     * as non-essential properties may be loaded when needed.
                     */
                    \RcmUser\User\Service\UserPropertyService::class
                    => \RcmUser\User\Service\UserPropertyServiceFactory::class,

                    /*
                     * UserRoleService - Core User Role data access service
                     * Required *
                     *
                     * This service exposes basic CRUD operations for the User roles.
                     */
                    \RcmUser\User\Service\UserRoleService::class
                    => \RcmUser\User\Service\UserRoleServiceFactory::class,

                    /*
                     * UserDataMapper - Data source adapter
                     * Required for:
                     *  \RcmUser\User\Service\UserDataService
                     *
                     * Old Service Name: '\RcmUser\User\UserDataMapper'
                     *
                     * This is a DataMapper adapter that is used
                     * to abstract the data storage method.
                     * This may be configured to use a custom data mapper
                     * for unique storage requirements.
                     */
                    \RcmUser\User\Db\UserDataMapper::class
                    => \RcmUser\User\Db\DoctrineUserDataMapperFactory::class,

                    /* ---------------------------- */
                    /*
                     * UserRolesDataMapper
                     * Required for (ACL user property]:
                     *  \RcmUser\User\Event\UserRoleDataServiceListeners
                     *
                     * Old Service Name: '\RcmUser\User\UserRolesDataMapper'
                     *
                     * This is a DataMapper adapter that is used
                     * to abstract the data storage method.
                     * This may be configured to use a custom data mapper
                     * for unique storage requirements.
                     */
                    \RcmUser\User\Db\UserRolesDataMapper::class
                    => \RcmUser\User\Db\DoctrineUserRoleDataMapperFactory::class,

                    /* - Validations - */
                    /*
                     * UserValidator - Validates User object data on create and update
                     * Required for:
                     *  \RcmUser\User\Db\UserDataMapper (\RcmUser\User\UserDataMapper]
                     *
                     * Uses the InputFilter value from the config by default.
                     * This may be configured to use a custom UserValidator as required.
                     */
                    \RcmUser\User\Data\UserValidator::class
                    => \RcmUser\User\Data\UserValidatorFactory::class,

                    /* - Data Prep - */
                    /*
                     * Encryptor
                     * Required for:
                     *  \RcmUser\User\Data\DbUserDataPreparer
                     *  \RcmUser\Authentication\Adapter\UserAdapter
                     *
                     * Old Service Name: '\RcmUser\User\Encryptor'
                     *
                     * Used for encrypting/hashing passwords by default.
                     * May not be required depending
                     * on the DbUserDataPreparer and UserAdapter that is being used.
                     */
                    \RcmUser\User\Password\Password::class
                    => \RcmUser\User\Password\EncryptorFactory::class,

                    /*
                     * UserDataPreparer
                     * Required for:
                     *  \RcmUser\User\Db\UserDataMapper (\RcmUser\User\UserDataMapper]
                     *
                     * Used by default to prepare data for DB storage.
                     * By default, encrypts passwords and creates id (UUID]
                     * This may be configured to use a custom UserDataPreparer as required
                     */
                    \RcmUser\User\Data\UserDataPreparer::class
                    => \RcmUser\User\Data\DbUserDataPreparerFactory::class,

                    /*
                     * UserDataServiceListeners
                     * Required
                     *  to validate, prepare and save (CRUD] User:
                     *
                     * Requires: \RcmUser\User\UserDataMapper
                     *
                     * Old Service Name: '\RcmUser\User\UserDataServiceListeners'
                     *
                     * Creates event listeners that use the UserValidator
                     * to do validation checks on User create and update.
                     */
                    \RcmUser\User\Event\UserDataServiceListeners::class
                    => \RcmUser\User\Event\UserDataServiceListenersFactory::class,

                    /*
                     * UserRoleDataServiceListeners
                     * Required for (User Acl Property populating]:
                     * Old Service Name: '\RcmUser\User\UserRoleDataServiceListeners'
                     */
                    \RcmUser\User\Event\UserRoleDataServiceListeners::class
                    => \RcmUser\User\Event\UserRoleDataServiceListenersFactory::class,

                    /* ************************************** */
                    /* AUTH ********************************* */
                    /* ************************************** */
                    /*
                     * UserAuthenticationService
                     * Required *
                     *
                     * Wraps events, actions are preformed in event listeners
                     * so that any auth provider may do auth logic.
                     */
                    \RcmUser\Authentication\Service\UserAuthenticationService::class
                    => \RcmUser\Authentication\Service\UserAuthenticationServiceFactory::class,

                    /* ---------------------------- */
                    /*
                     * UserAdapter (requires Encryptor]
                     * Required for (Auth]:
                     *  \RcmUser\Authentication\Service\AuthenticationService
                     *
                     * Old Service Name: '\RcmUser\Authentication\Adapter'
                     *
                     * By default this auth Adapter uses the Encryptor
                     * to validate a users credentials
                     * This may be configured to use a custom auth Adapter as required
                     */
                    \RcmUser\Authentication\Adapter\Adapter::class
                    => \RcmUser\Authentication\Adapter\UserAdapterFactory::class,

                    /*
                     * UserSession
                     * Required for (Auth]:
                     *  \RcmUser\Authentication\Service\AuthenticationService
                     *
                     * Old Service Name: '\RcmUser\Authentication\Storage'
                     *
                     * By default this module uses the default session container for storage
                     * This may be configured to use a custom Storage object as required
                     */
                    \RcmUser\Authentication\Storage\Session::class
                    => \RcmUser\Authentication\Storage\UserSessionFactory::class,

                    /*
                     * AuthenticationService
                     * Required for:
                     *  \RcmUser\Authentication\EventListeners
                     *
                     * Old Service Name: '\RcmUser\Authentication\AuthenticationService'
                     *
                     * By default this module uses the default Adapter and Storage
                     * to do authentication
                     * This may be configure to use custom AuthenticationService as required
                     */
                    \RcmUser\Authentication\Service\AuthenticationService::class
                    => \RcmUser\Authentication\Service\AuthenticationServiceFactory::class,

                    /*
                     * EventListeners
                     * Used for listening for auth related events:
                     *
                     * Old Service Name: '\RcmUser\Authentication\UserAuthenticationServiceListeners'
                     *
                     * By default this module listens for the events
                     * from UserAuthenticationService to do authentication
                     * This may be configured to use custom event listeners
                     * or disabled if not required
                     */
                    \RcmUser\Authentication\Event\UserAuthenticationServiceListeners::class
                    => \RcmUser\Authentication\Event\UserAuthenticationServiceListenersFactory::class,

                    /* ************************************** */
                    /* ACL ********************************** */
                    /* ************************************** */
                    /*
                     * AclResourceService
                     * Used by:
                     *  \RcmUser\Acl\Provider\ResourceProvider
                     *
                     * Exposes this module's resource aggregation methods
                     */
                    \RcmUser\Acl\Service\AclResourceService::class
                    => \RcmUser\Acl\Service\AclResourceServiceFactory::class,

                    /*
                     * AuthorizeService (ACL)
                     * Used by:
                     *  RcmUserService
                     *  ControllerPluginRcmUserIsAllowed
                     *  ViewHelperRcmUserIsAllowed
                     *
                     * Exposes the ACL isAllowed method
                     */
                    \RcmUser\Acl\Service\AuthorizeService::class
                    => \RcmUser\Acl\Service\AuthorizeServiceFactory::class,

                    /*
                     * AclResourceNsArrayService
                     *
                     * Exposes a data prep for creating namespaces based on resource
                     */
                    \RcmUser\Acl\Service\AclResourceNsArrayService::class
                    => \RcmUser\Acl\Service\AclResourceNsArrayServiceFactory::class,

                    /*
                     * RootResourceProvider
                     *
                     * Old Service Name: '\RcmUser\Acl\RootResourceProvider'
                     *
                     * Provides the root resource
                     */
                    \RcmUser\Acl\Provider\RootResourceProvider::class
                    => \RcmUser\Acl\Provider\RootResourceProviderFactory::class,

                    /*
                     * \RcmUser\Acl\ResourceProvider
                     *
                     * Old Service Name: '\RcmUser\Acl\ResourceProvider'
                     *
                     * Main Resource provider
                     * By default it wraps all other resource providers
                     * NOTE: Over-riding this is touchy
                     *       as it handles the resource normalization and other details
                     */
                    \RcmUser\Acl\Provider\ResourceProvider::class
                    => \RcmUser\Acl\Provider\CompositeResourceProviderFactory::class,

                    /*
                     * \RcmUser\Acl\ResourceCache
                     *
                     * Old Service Name: '\RcmUser\Acl\ResourceCache'
                     *
                     * Resource Caching
                     * By default caches to an array
                     */
                    \RcmUser\Acl\Cache\ResourceCache::class
                    => \RcmUser\Acl\Cache\ResourceCacheArrayFactory::class,

                    /*
                     * \RcmUser\Acl\RootAclResource
                     *
                     * Old Service Name: '\RcmUser\Acl\RootAclResource'
                     *
                     * Root resource used for wrapping all other resources
                     */
                    \RcmUser\Acl\Entity\RootAclResource::class
                    => \RcmUser\Acl\Entity\RootAclResourceFactory::class,

                    /*
                     * AclResourceBuilder
                     */
                    \RcmUser\Acl\Builder\AclResourceBuilder::class
                    => \RcmUser\Acl\Builder\AclResourceBuilderFactory::class,

                    /*
                     * AclResourceStackBuilder
                     */
                    \RcmUser\Acl\Builder\AclResourceStackBuilder::class
                    => \RcmUser\Acl\Builder\AclResourceStackBuilderFactory::class,

                    /*
                     * ResourceProviderBuilder
                     */
                    \RcmUser\Acl\Builder\ResourceProviderBuilder::class
                    => \RcmUser\Acl\Builder\ResourceProviderBuilderFactory::class,

                    /*
                     * AclRoleDataMapper
                     *
                     * Old Service Name: '\RcmUser\Acl\AclRoleDataMapper'
                     *
                     * Required
                     * This data mapper adapter allows this module
                     * to read roles from a data source
                     * This may be configured to use a custom data mapper if required
                     */
                    \RcmUser\Acl\Db\AclRoleDataMapper::class
                    => \RcmUser\Acl\Db\DoctrineAclRoleDataMapperFactory::class,

                    /*
                     * AclRuleDataMapper
                     *
                     * Old Service Name: '\RcmUser\Acl\AclRuleDataMapper'
                     *
                     * Required for:
                     * This data mapper adapter allows this module
                     * to read rules from a data source
                     * This may be configured to use a custom data mapper if required
                     */
                    \RcmUser\Acl\Db\AclRuleDataMapper::class
                    => \RcmUser\Acl\Db\DoctrineAclRuleDataMapperFactory::class,

                    /*
                     * AclDataService
                     *
                     * Old Service Name: '\RcmUser\Acl\AclDataService'
                     *
                     * Required for accessing mappers
                     * This is designed to expose a simple facade
                     * for use in displaying and updating ACL data
                     * in views
                     */
                    \RcmUser\Acl\Service\AclDataService::class
                    => \RcmUser\Acl\Service\AclDataServiceFactory::class,

                    /**
                     * AclListeners
                     */
                    \RcmUser\Acl\Event\AclListeners::class
                    => \RcmUser\Acl\Event\AclListenersFactory::class,

                    /**
                     * IsAllowedErrorExceptionListener
                     */
                    \RcmUser\Acl\Event\IsAllowedErrorExceptionListener::class
                    => \RcmUser\Acl\Event\IsAllowedErrorExceptionListenerFactory::class,

                    /* ************************************** */
                    /* CORE ********************************* */
                    /* ************************************** */
                    /*
                     * Main service facade
                     * Uses:
                     *  UserDataService
                     *  UserPropertyService
                     *  UserAuthenticationService
                     *  AuthorizeService
                     */
                    \RcmUser\Service\RcmUserService::class
                    => \RcmUser\Service\RcmUserServiceFactory::class,

                    /**
                     * Simple Access to the current user
                     * Uses:
                     *  UserAuthenticationService
                     */
                    \RcmUser\Service\CurrentUser::class
                    => \RcmUser\Service\CurrentUserFactory::class,

                    /*
                     * Provides the Access Resources for this Module to ACL
                     * Required *
                     */
                    \RcmUser\Provider\RcmUserAclResourceProvider::class
                    => \RcmUser\Provider\RcmUserAclResourceProviderFactory::class,

                    /*
                     * Event Aggregation
                     *
                     * Old Service Name: '\RcmUser\Event\Listeners'
                     *
                     * Required *
                     */
                    \RcmUser\Event\ListenerCollection::class
                    => \RcmUser\Event\ListenerCollectionFactory::class,

                    /**
                     * UserEventManager
                     */
                    \RcmUser\Event\UserEventManager::class
                    => \RcmUser\Event\UserEventManagerFactory::class,

                    /*
                     * Logging
                     * Required *
                     */
                    \RcmUser\Log\Logger::class
                    => \RcmUser\Log\DoctrineLoggerFactory::class,

                    /*
                     * LoggerListeners
                     */
                    \RcmUser\Log\Event\LoggerListeners::class
                    => \RcmUser\Log\Event\LoggerListenersFactory::class,

                    /*
                     * AclDataService Log Listeners
                     */
                    \RcmUser\Log\Event\AclDataService\CreateAclRoleFailListener::class
                    => \RcmUser\Log\Event\AclDataService\CreateAclRoleFailListenerFactory::class,

                    \RcmUser\Log\Event\AclDataService\CreateAclRoleListener::class
                    => \RcmUser\Log\Event\AclDataService\CreateAclRoleListenerFactory::class,

                    \RcmUser\Log\Event\AclDataService\CreateAclRoleSuccessListener::class
                    => \RcmUser\Log\Event\AclDataService\CreateAclRoleSuccessListenerFactory::class,

                    \RcmUser\Log\Event\AclDataService\CreateAclRuleFailListener::class
                    => \RcmUser\Log\Event\AclDataService\CreateAclRuleFailListenerFactory::class,

                    \RcmUser\Log\Event\AclDataService\CreateAclRuleListener::class
                    => \RcmUser\Log\Event\AclDataService\CreateAclRuleListenerFactory::class,

                    \RcmUser\Log\Event\AclDataService\CreateAclRuleSuccessListener::class
                    => \RcmUser\Log\Event\AclDataService\CreateAclRuleSuccessListenerFactory::class,

                    \RcmUser\Log\Event\AclDataService\DeleteAclRoleFailListener::class
                    => \RcmUser\Log\Event\AclDataService\DeleteAclRoleFailListenerFactory::class,

                    \RcmUser\Log\Event\AclDataService\DeleteAclRoleListener::class
                    => \RcmUser\Log\Event\AclDataService\DeleteAclRoleListenerFactory::class,

                    \RcmUser\Log\Event\AclDataService\DeleteAclRoleSuccessListener::class
                    => \RcmUser\Log\Event\AclDataService\DeleteAclRoleSuccessListenerFactory::class,

                    \RcmUser\Log\Event\AclDataService\DeleteAclRuleFailListener::class
                    => \RcmUser\Log\Event\AclDataService\DeleteAclRuleFailListenerFactory::class,

                    \RcmUser\Log\Event\AclDataService\DeleteAclRuleListener::class
                    => \RcmUser\Log\Event\AclDataService\DeleteAclRuleListenerFactory::class,

                    \RcmUser\Log\Event\AclDataService\DeleteAclRuleSuccessListener::class
                    => \RcmUser\Log\Event\AclDataService\DeleteAclRuleSuccessListenerFactory::class,

                    /**
                     * AuthorizeService Log Listeners
                     */
                    \RcmUser\Log\Event\AuthorizeService\IsAllowedErrorListener::class
                    => \RcmUser\Log\Event\AuthorizeService\IsAllowedErrorListenerFactory::class,

                    \RcmUser\Log\Event\AuthorizeService\IsAllowedFalseListener::class
                    => \RcmUser\Log\Event\AuthorizeService\IsAllowedFalseListenerFactory::class,

                    \RcmUser\Log\Event\AuthorizeService\IsAllowedSuperAdminListener::class
                    => \RcmUser\Log\Event\AuthorizeService\IsAllowedSuperAdminListenerFactory::class,

                    \RcmUser\Log\Event\AuthorizeService\IsAllowedTrueListener::class
                    => \RcmUser\Log\Event\AuthorizeService\IsAllowedTrueListenerFactory::class,

                    /*
                     * UserAuthenticationService Log Listeners
                     */
                    \RcmUser\Log\Event\UserAuthenticationService\AuthenticateFailListener::class
                    => \RcmUser\Log\Event\UserAuthenticationService\AuthenticateFailListenerFactory::class,

                    \RcmUser\Log\Event\UserAuthenticationService\ValidateCredentialsFailListener::class
                    => \RcmUser\Log\Event\UserAuthenticationService\ValidateCredentialsFailListenerFactory::class,

                    /**
                     * UserDataService Log Listeners
                     */
                    \RcmUser\Log\Event\UserDataService\CreateUserFailListener::class
                    => \RcmUser\Log\Event\UserDataService\CreateUserFailListenerFactory::class,

                    \RcmUser\Log\Event\UserDataService\CreateUserListener::class
                    => \RcmUser\Log\Event\UserDataService\CreateUserListenerFactory::class,

                    \RcmUser\Log\Event\UserDataService\CreateUserSuccessListener::class
                    => \RcmUser\Log\Event\UserDataService\CreateUserSuccessListenerFactory::class,

                    \RcmUser\Log\Event\UserDataService\DeleteUserFailListener::class
                    => \RcmUser\Log\Event\UserDataService\DeleteUserFailListenerFactory::class,

                    \RcmUser\Log\Event\UserDataService\DeleteUserListener::class
                    => \RcmUser\Log\Event\UserDataService\DeleteUserListenerFactory::class,

                    \RcmUser\Log\Event\UserDataService\DeleteUserSuccessListener::class
                    => \RcmUser\Log\Event\UserDataService\DeleteUserSuccessListenerFactory::class,

                    \RcmUser\Log\Event\UserDataService\UpdateUserFailListener::class
                    => \RcmUser\Log\Event\UserDataService\UpdateUserFailListenerFactory::class,

                    \RcmUser\Log\Event\UserDataService\UpdateUserListener::class
                    => \RcmUser\Log\Event\UserDataService\UpdateUserListenerFactory::class,

                    \RcmUser\Log\Event\UserDataService\UpdateUserSuccessListener::class
                    => \RcmUser\Log\Event\UserDataService\UpdateUserSuccessListenerFactory::class,

                    /**
                     * UserRoleService Log Listeners
                     */
                    \RcmUser\Log\Event\UserRoleService\AddUserRoleFailListener::class
                    => \RcmUser\Log\Event\UserRoleService\AddUserRoleFailListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\AddUserRoleListener::class
                    => \RcmUser\Log\Event\UserRoleService\AddUserRoleListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\AddUserRoleSuccessListener::class
                    => \RcmUser\Log\Event\UserRoleService\AddUserRoleSuccessListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\CreateUserRolesFailListener::class
                    => \RcmUser\Log\Event\UserRoleService\CreateUserRolesFailListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\CreateUserRolesListener::class
                    => \RcmUser\Log\Event\UserRoleService\CreateUserRolesListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\CreateUserRolesSuccessListener::class
                    => \RcmUser\Log\Event\UserRoleService\CreateUserRolesSuccessListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\DeleteUserRolesFailListener::class
                    => \RcmUser\Log\Event\UserRoleService\DeleteUserRolesFailListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\DeleteUserRolesListener::class
                    => \RcmUser\Log\Event\UserRoleService\DeleteUserRolesListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\DeleteUserRolesSuccessListener::class
                    => \RcmUser\Log\Event\UserRoleService\DeleteUserRolesSuccessListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\RemoveUserRoleFailListener::class =>
                        \RcmUser\Log\Event\UserRoleService\RemoveUserRoleFailListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\RemoveUserRoleListener::class
                    => \RcmUser\Log\Event\UserRoleService\RemoveUserRoleListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\RemoveUserRoleSuccessListener::class
                    => \RcmUser\Log\Event\UserRoleService\RemoveUserRoleSuccessListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\UpdateUserRolesFailListener::class
                    => \RcmUser\Log\Event\UserRoleService\UpdateUserRolesFailListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\UpdateUserRolesListener::class
                    => \RcmUser\Log\Event\UserRoleService\UpdateUserRolesListenerFactory::class,

                    \RcmUser\Log\Event\UserRoleService\UpdateUserRolesSuccessListener::class
                    => \RcmUser\Log\Event\UserRoleService\UpdateUserRolesSuccessListenerFactory::class,
                ],
            ],
            'doctrine' => [
                /*
                 * Allows doctrine to generate tables as needed
                 * Only required if using doctrine entities and mappers
                 * And you want doctrine utilities to work correctly
                 */
                'driver' => [
                    'RcmUser' => [
                        'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                        // NOTE: this must be array or the TTL expiration MUST be used
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/../src/Acl/Entity',
                            __DIR__ . '/../src/User/Entity',
                            __DIR__ . '/../src/Log/Entity',
                        ]
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'RcmUser' => 'RcmUser'
                        ]
                    ]
                ]
            ],
            'RcmUser' => [
                'User\Config' => [
                    /*
                     * ValidUserStates
                     * Used for UI
                     */
                    'ValidUserStates' => [
                        'disabled',
                        // **REQUIRED for User entity**
                        'enabled',
                    ],

                    /*
                     * DefaultUserState
                     * Used in:
                     *  \RcmUser\User\Service\UserDataService
                     *
                     * This is the default user state that will
                     * be set on create/update if none is set.
                     */
                    'DefaultUserState' => 'enabled',

                    /*
                     * Encryptor.passwordCost
                     * Used in:
                     *  \RcmUser\User\Encryptor
                     *
                     * This should only be changed if you know what you are doing.
                     */
                    'Encryptor.passwordCost' => 14,

                    /*
                     * InputFilter
                     * Used in:
                     *  \RcmUser\User\Db\UserDataMapper
                     *
                     * This input filter will be applied
                     * to the User object on create and save.
                     */
                    'InputFilter' => [

                        'username' => [
                            'name' => 'username',
                            'required' => true,
                            'filters' => [
                                ['name' => 'StringTrim'],
                            ],
                            'validators' => [
                                [
                                    'name' => 'StringLength',
                                    'options' => [
                                        'encoding' => 'UTF-8',
                                        'min' => 3,
                                        'max' => 100,
                                    ],
                                ],
                                // Help protect from XSS
                                [
                                    'name' => 'Regex',
                                    'options' => [
                                        'pattern' => "/^[a-zA-Z0-9-_@'.]+$/",
                                        //'pattern' => "/[<>]/",
                                        'messageTemplates' => [
                                            \Zend\Validator\Regex::NOT_MATCH
                                            => "Username can only contain letters, numbers and charactors: . - _ @ '."
                                        ]
                                    ],
                                ],
                            ],
                        ],
                        'password' => [
                            'name' => 'password',
                            'required' => true,
                            'filters' => [],
                            'validators' => [
                                [
                                    'name' => 'StringLength',
                                    'options' => [
                                        'encoding' => 'UTF-8',
                                        'min' => 6,
                                        'max' => 100,
                                    ],
                                ],
                                /*
                                [
                                    'name' => 'Regex',
                                    'options' => [
                                        'pattern' => '^(?=.*\d](?=.*[a-zA-Z]]$'
                                    ],
                                ],
                                */
                            ],
                        ],
                        'email' => [
                            'name' => 'email',
                            'required' => true,
                            'filters' => [
                                ['name' => 'Zend\Filter\StripTags'],
                                // Help protect from XSS
                                ['name' => 'Zend\Filter\StringTrim'],
                            ],
                            'validators' => [
                                ['name' => 'Zend\Validator\EmailAddress'],
                            ],
                        ],
                        'name' => [
                            'name' => 'name',
                            'required' => true,
                            'filters' => [
                                ['name' => 'Zend\Filter\StripTags'],
                                // Help protect from XSS
                                ['name' => 'Zend\Filter\StringTrim'],
                            ],
                            'validators' => [],
                        ],
                    ],
                ],
                'Auth\Config' => [
                    'ObfuscatePasswordOnAuth' => true,
                ],
                'Acl\Config' => [
                    /*
                     * DefaultGuestRoleIds and DefaultUserRoleIds
                     * Used by:
                     *  \RcmUser\Acl\EventListeners
                     *
                     * These event listeners inject the ACL roles property
                     * for a user on the user data events
                     * in \RcmUser\User\Service\UserDataService.
                     */
                    'DefaultGuestRoleIds' => ['guest'],
                    'DefaultUserRoleIds' => ['user'],

                    /*
                     * SuperAdminRoleId
                     *
                     * If this is set, this role will get full permissions always
                     * Basically over-rides standard permission handling
                     */
                    'SuperAdminRoleId' => 'admin',

                    /**
                     * @todo work this out
                     */
                    'GuestRoleId' => 'guest',

                    /**
                     * AclListeners Config
                     * [{ServiceName} => {Priority}]
                     */
                    \RcmUser\Acl\Event\AclListeners::class => [
                        /**
                         * IsAllowedErrorExceptionListener
                         *
                         * This will throw an error when a missing resource is used for isAllowed
                         * Without this, isAllow will return false for missing resources
                         */
                        // \RcmUser\Acl\Event\IsAllowedErrorExceptionListener::class => 0,
                    ],

                    /*
                     * ResourceProviders
                     * Used in:
                     *  \RcmUser\Acl\Service\AclResourceService
                     *
                     * This aggregates resources that may be injected by any module,
                     * this module wraps the resources
                     * in a root resource with common privileges.
                     *
                     * IMPORTANT:
                     * - Parent resources must be first in the resource array
                     * - It is not possible to share parent or child resources
                     *   between different providers
                     *
                     * Format for each value of this array is:
                     *
                     * 'ProviderId(module namespace without back-slashes]' =>
                     * 'MyResource/ResourceProvider(extents ResourceProvider]'
                     *
                     * OR
                     *
                     * ProviderId(usually module namespace]' => [
                     *     'resourceId' => 'some-resource'
                     *     'parentResourceId' => null // Or a parent resourceId if needed
                     *     'privileges' => ['privilege1', 'privilege2', 'etc...'],
                     *     'name' => 'Human readable or translatable name',
                     *     'description' => 'Human readable or translatable description',
                     * ]
                     */
                    'ResourceProviders' => [
                        /**
                         * Root Resource Provider
                         */
                        'root' => \RcmUser\Acl\Provider\RootResourceProvider::class,

                        /*
                         * RcmUserAccess
                         * This module inject some of this module's resources.
                         * Also example of a Resource provider
                         */
                        'RcmUser' => \RcmUser\Provider\RcmUserAclResourceProvider::class,

                        /* example of resource providers as array *
                        'RcmUser' => [
                            'test-one' => [
                                'resourceId' => 'test-one',
                                'parentResourceId' => null,
                                'privileges' => [
                                    'read',
                                    'update',
                                    'create',
                                    'delete',
                                    'execute',
                                ],
                                'name' => 'Test resource one.',
                                'description' => 'test resource one desc.',
                            ],
                            'test-two' => [
                                'resourceId' => 'test-two',
                                'parentResourceId' => 'test-one',
                                'privileges' => [
                                    'read',
                                    'update',
                                    'create',
                                    'delete',
                                    'execute',
                                ],
                                'name' => 'Test resource two.',
                                'description' => 'test resource two desc.',
                            ]
                        ],
                        /* - example */
                    ],
                ],
                /**
                 * Register Zend\EventManager\ListenerAggregateInterface Services
                 */
                'EventListener\Config' => [
                    // AclListeners
                    \RcmUser\Acl\Event\AclListeners::class
                    => \RcmUser\Acl\Event\AclListeners::class,

                    // UserAuthenticationServiceListeners
                    \RcmUser\Authentication\Event\UserAuthenticationServiceListeners::class
                    => \RcmUser\Authentication\Event\UserAuthenticationServiceListeners::class,

                    // LoggerListeners
                    \RcmUser\Log\Event\LoggerListeners::class
                    => \RcmUser\Log\Event\LoggerListeners::class,

                    // UserDataServiceListeners
                    \RcmUser\User\Event\UserDataServiceListeners::class
                    => \RcmUser\User\Event\UserDataServiceListeners::class,

                    // UserRoleDataServiceListeners
                    \RcmUser\User\Event\UserRoleDataServiceListeners::class
                    => \RcmUser\User\Event\UserRoleDataServiceListeners::class,
                ],

                'Log\Config' => [
                    /**
                     * LoggerListeners Config
                     * [{ServiceName} => {Priority}]
                     */
                    \RcmUser\Log\Event\LoggerListeners::class => [
                        /* EXAMPLE - Some available logger listeners
                        // AclDataService Log Listeners
                        \RcmUser\Log\Event\AclDataService\CreateAclRoleFailListener::class => 0,
                        \RcmUser\Log\Event\AclDataService\CreateAclRoleListener::class => 0,
                        \RcmUser\Log\Event\AclDataService\CreateAclRoleSuccessListener::class => 0,

                        \RcmUser\Log\Event\AclDataService\CreateAclRuleFailListener::class => 0,
                        \RcmUser\Log\Event\AclDataService\CreateAclRuleListener::class => 0,
                        \RcmUser\Log\Event\AclDataService\CreateAclRuleSuccessListener::class => 0,

                        \RcmUser\Log\Event\AclDataService\DeleteAclRoleFailListener::class => 0,
                        \RcmUser\Log\Event\AclDataService\DeleteAclRoleListener::class => 0,
                        \RcmUser\Log\Event\AclDataService\DeleteAclRoleSuccessListener::class => 0,

                        \RcmUser\Log\Event\AclDataService\DeleteAclRuleFailListener::class => 0,
                        \RcmUser\Log\Event\AclDataService\DeleteAclRuleListener::class => 0,
                        \RcmUser\Log\Event\AclDataService\DeleteAclRuleSuccessListener::class => 0,

                        // AuthorizeService Log Listeners
                        \RcmUser\Log\Event\AuthorizeService\IsAllowedErrorListener::class => 0,
                        \RcmUser\Log\Event\AuthorizeService\IsAllowedFalseListener::class => 0,
                        \RcmUser\Log\Event\AuthorizeService\IsAllowedSuperAdminListener::class => 0,
                        \RcmUser\Log\Event\AuthorizeService\IsAllowedTrueListener::class => 0,

                        // UserAuthenticationService Log Listeners
                        \RcmUser\Log\Event\UserAuthenticationService\AuthenticateFailListener::class => 0,
                        \RcmUser\Log\Event\UserAuthenticationService\ValidateCredentialsFailListener::class => 0,

                        // UserDataService Log Listeners
                        \RcmUser\Log\Event\UserDataService\CreateUserFailListener::class => 0,
                        \RcmUser\Log\Event\UserDataService\CreateUserListener::class => 0,
                        \RcmUser\Log\Event\UserDataService\CreateUserSuccessListener::class => 0,

                        \RcmUser\Log\Event\UserDataService\DeleteUserFailListener::class => 0,
                        \RcmUser\Log\Event\UserDataService\DeleteUserListener::class => 0,
                        \RcmUser\Log\Event\UserDataService\DeleteUserSuccessListener::class => 0,

                        \RcmUser\Log\Event\UserDataService\UpdateUserFailListener::class => 0,
                        \RcmUser\Log\Event\UserDataService\UpdateUserListener::class => 0,
                        \RcmUser\Log\Event\UserDataService\UpdateUserSuccessListener::class => 0,

                        // UserRoleService Log Listeners
                        \RcmUser\Log\Event\UserRoleService\AddUserRoleFailListener::class => 0,
                        \RcmUser\Log\Event\UserRoleService\AddUserRoleListener::class => 0,
                        \RcmUser\Log\Event\UserRoleService\AddUserRoleSuccessListener::class => 0,

                        \RcmUser\Log\Event\UserRoleService\CreateUserRolesFailListener::class => 0,
                        \RcmUser\Log\Event\UserRoleService\CreateUserRolesListener::class => 0,
                        \RcmUser\Log\Event\UserRoleService\CreateUserRolesSuccessListener::class => 0,

                        \RcmUser\Log\Event\UserRoleService\DeleteUserRolesFailListener::class => 0,
                        \RcmUser\Log\Event\UserRoleService\DeleteUserRolesListener::class => 0,
                        \RcmUser\Log\Event\UserRoleService\DeleteUserRolesSuccessListener::class => 0,

                        \RcmUser\Log\Event\UserRoleService\RemoveUserRoleFailListener::class => 0,
                        \RcmUser\Log\Event\UserRoleService\RemoveUserRoleListener::class => 0,
                        \RcmUser\Log\Event\UserRoleService\RemoveUserRoleSuccessListener::class => 0,

                        \RcmUser\Log\Event\UserRoleService\UpdateUserRolesFailListener::class => 0,
                        \RcmUser\Log\Event\UserRoleService\UpdateUserRolesListener::class => 0,
                        \RcmUser\Log\Event\UserRoleService\UpdateUserRolesSuccessListener::class => 0,
                        */
                    ]
                ],
            ]
        ];
    }
}
