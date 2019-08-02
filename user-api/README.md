rcmuser-api
====================

ZF2 based APIs using REST/JSON

#### REST/JSON APIs ####

This module exposes several APIs for user administration.
The APIs are not comprehensive, but they do allow for some user and ACL management.
The admin APIs require access rules to be set in order to access (@see ACL section).

For a complete list of the APIs, please see the RcmUser/config/module.config.php file, routes section.
API standard return is a result object containing a code, message and the data.

#### Controller Plugins and View Helpers ####

- rcmUserIsAllowed($resourceId, $privilege = null, $providerId = null) (plugin and helper)
 - Alias of RcmUserService::isAllowed()

- rcmUserHasRoleBasedAccess($roleId) (plugin and helper)
 - Alias of RcmUserService::hasRoleBasedAccess()
 
- rcmUserGetCurrentUser($default = null) (plugin and helper)
 - Alias of RcmUserService::getIdentity()
  
controller plug-in and view helper for isAllowed (rcmUserIsAllowed for plug-in and helper)

#### View dependencies

- AngularJs (https://angularjs.org/)
- Bootstrap (http://getbootstrap.com/)
- UI Bootstrap (http://angular-ui.github.io/bootstrap/)

Project
-------

##### Company:
Copyright Reliv' International, Inc. 2015

##### Project homepage: #####
https://github.com/reliv/rcm-user-api

##### Project author: #####
James Jervis
jjervis@relivinc.com
https://github.com/reliv


@TODO
-----

#### More REST/JSON APIs ####

As DevOpts
I should have access to REST/JSON APIs
So that I may securely perform RcmUser actions VIA web clients

#### Security Updates ####

Story: 
As and Auditor 
I can access a log of actions performed on users and roles by administrators 
So that I track admin user changes
    
 - Implement logging audit trail for user creates and saves
 - might create event listeners or do at the service level
 - Logging of actions for security audits
