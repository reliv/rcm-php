RcmUser
=======

Introduction
------------

The main goal of this module is to expose a simple and configurable user object as well as the services related to user storage, access control and authentication.

See TODO.md for future goals.


Features
--------

### User ###

#### User class ####

The User class is the module's main user entity.

The User class has the properties of:

- id
 - A unique identifier, by default this is generated on create by the DbUserDataPreparer.

- username
 - A unique username.

- password
 - A password, by default this is hashed by the Encryptor on create/update by the DbUserDataPreparer.
 - The Auth UserAdapter also uses the same Encryptor to authenticate password.

- state
 - State is used to provide a tag for the users state.
 - There is only one state provided ('disabled'), any other state my be created and utilized as needed.

- email
 - An email address
 - Validations may be set in the config
 - By default this is not required to be unique

- name
 - A display name
 - Validations may be set in the config

- properties
 - An aggregation of arbitrary properties
 - These can be injected into the User object by using event listeners for the User data events or the property events.
 - These can also be injected directly in the data mappers if you provide your own.

 By default, there is a ACL roles property injected for the User.  This property is the one which is used by this module's ACL.

### RcmUserService ###

RcmUserService is a high level service/facade that exposes many useful methods for manipulating a User object.

- PHP APIs that a developer might need regularly
- Methods for basic create, read, update, delete
- Methods for accessing User->properties on demand (properties that are only populated as needed)
- Methods for doing authentication of user (log in, log out, credential checks)
- Methods for checking user access (ACL)
- Other utility and helpful methods are also provided

### CurrentUser Service ###

Exposes a simple service for getting the current user

CurrentUser->get({default if no user}) or CurrentUser({default if no user})

##### Data Methods #####

- getUser(User $user)
 - Returns a user from the data source based on the data in the provided User object (User::id and User::username)

- userExists(User $user)
 - Returns true if the user exists in the data source

- readUser(User $user, $includeResult = true)
 - Returns a Result object containing the a User object if user is found, null if not found.
 - Returns the User object or null if $includeResult == false

- createUser(User $user, $includeResult = true)
 - Returns a Result object containing the a User object if user is created with a success message.
 - Returns the User object if $includeResult == false

- updateUser(User $user, $includeResult = true)
 - Returns a Result object containing the a User object if user is updated with a success message.
 - Returns the User object if $includeResult == false
  - $user = {request user object}
  - $includeResult = {if true, will return Result Object containing code, message and data (User)}

- deleteUser(User $user, $includeResult = true)
 - Returns a Result object containing the an  empty User object if user is updated with a success message.
 - Returns the User object if $includeResult == false
 - $user = {request user object}
 - $includeResult = {if true, will return Result Object containing code, message and data (User)}

- getUserProperty(User $user, $propertyNameSpace, $default = null, $refresh = false)
 - OnDemand loading of a user property.  I a way of populating User::property using events.
 - Some user properties are not loaded with the user to increase speed.  Use this method to load these properties.
 - $user = {request user object}
 - $propertyNameSpace = {unique id of the requested property}
 - $default = {return value if property not set}
 - $refresh = {will force retrieval of property, even if it is already set}

- getCurrentUserProperty($propertyNameSpace, $default = null, $refresh = false)
 - OnDemand loading of a CURRENT user property.  I a way of populating User::property using events.
 - Some user properties are not loaded with the user to increase speed.  Use this method to load these properties.
 - $propertyNameSpace = {unique id of the requested property}
 - $default = {return value if property not set}
 - $refresh = {will force retrieval of property, even if it is already set}

##### Authentication Methods #####

- validateCredentials(User $user)
 - Allows the validation of user credentials (username and password) without creating an auth session.
 - Helpful for doing non-login authentication checks.
 - $user = {request user object}

- authenticate(User $user)
 - Creates auth session (logs in user) if credentials provided in the User object are valid.
 - $user = {request user object}

- clearIdentity()
 - Clears auth session (logs out user)

- hasIdentity()
 - Check if any User is auth'ed (logged in)

- isIdentity(User $user)
 - Check if the requested user in the user that is currently in the auth session
 - $user = {request user object}

- setIdentity(User $user)
 - Force a User into the auth'd session.
 - $user = {request user object}
 - WARNING: this by-passes the authentication process and should only be used with extreme caution

- refreshIdentity()
 - Will reload the current User that is Auth'd into the auth'd session.
 - Is a way of refreshing the session user without log-out, then log-in

- getIdentity($default = null)
 - Get the current User (logged in User) from Auth'd session or returns $default is there is no User Auth'd
 - $default = {return this value if no User is auth'd}

##### Access Control Methods ACL (Access Control Layer) Based #####

- isAllowed($resourceId, $privilege = null, $providerId = null)
 - Check if the current Auth'd User has access to a resource with a privilege provided by provider id.
 - This is use to validate a users access based on their role and the rules set by ACL
 - $resourceId = {a string resource id as defined by a resource provider (may be another module)}
 - $privilege = {a privilege of the resource to check access against for this User},
 - $providerId = {unique identifier of the provider of the resource and privilege definition}

- isUserAllowed($resourceId, $privilege = null, $providerId = null, $user = null)
 - Check if the requested User has access to a resource with a privilege provided by provider id.
 - This is use to validate a users access based on their role and the rules set by ACL
 - $resourceId = {a string resource id as defined by a resource provider (may be another module)}
 - $privilege = {a privilege of the resource to check access against for this User},
 - $providerId = {unique identifier of the provider of the resource and privilege definition}
 - $user = {request user object}
 
##### Access Control Methods RBA (Role Based Access) #####

- hasRoleBasedAccess($roleId)
 - Check if the current Auth'd User has the requested role or the role exists in the role lineage
 - Returns Bool
 
- hasUserRoleBasedAccess($user, $roleId)
 - Check if the requested User has the requested role or the role exists in the role lineage
 - Returns Bool

##### Utility Methods #####

- buildNewUser()
 - Factory method to build new User object populated with defaults from event listeners

- buildUser(User $user)
 - Populate a User with defaults from event listeners
 - $user = {request user object}

#### DataMappers ####

The UserMappers are adapters used to populate and store the user data.
By default this module uses the Doctrine DataMappers.
Any data mapper can be written and configured so that data may be stored based on your requirements.

> NOTE: If you decide to write you own data mappers, you may find the implementation test (W.I.P.) in RcmUser/test/ImplementationTest helpful.
> The implementation test is NOT to be run in PROD as it creates and destroys data.

### Authentication ###

This module uses the ZF2 Authentication libraries.  This requires it to provide:

- AuthenticationService
 - By default, this module uses the ZF2 class without modification.
 - You may inject your own as required.
- Adapter
 - By default, this module uses it's UserAdapter which requires Encryptor and UserDataService.
 - You may inject your own as required.
- Storage
 - By default, this module uses UserSession which is a session storage with $namespace = 'RcmUser', $member = 'user' and the default session container.

### ACL ###

This module wraps resources in a root schema and provides data mappers for storage of roles and rules.
This module also provides a service for isAllowed

This module also creates some ACL resources that are used to allow access to APIs and Views.

ProviderId:
- RcmUser

Resources and Privileges:
- rcmuser

 - rcmuser-user-administration
      - read
      - update
      - create
      - delete
      - update_credentials

 - rcmuser-acl-administration
      - read
      - update
      - create
      - delete

Requirements
------------

- php 5.5.* (not tested on lesser versions)
- zendframework 2.2.x
- rwoverdijk/assetmanager 1.*

Optional based on configuration

- doctrine 2.x
- mysql 5.6.x (not tested on lesser versions)

Installation
------------

### Manual Install ###

- Download or clone from GitHub into your ZF2 skeleton app
- Configure module
- Run install.sql (as needed)

### Composer Install ###

@future

Configuration
-------------

### Module Config Tree ###

```php
// @see config/dependencies.php
// @see config/rcm-user.php
```
