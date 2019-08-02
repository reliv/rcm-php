# Future Features #

#### DB and Query Optimization ####

 - Optimize mappers
 - Pagination/filtering for DataMappers

#### Translations ####

 - Result messages
 - Translation of other page content

#### Protection of default and special roles ####

As an ACL Administrator
I should not be able to delete super admin, guest or default roles
So that rules for default roles will not be removed

 - Should not be able to delete super admin, guest or default roles
 - OR should be able to restore them easily (even if they are deleted they are still there)

#### Full Deny rule support ####

Story: 
As a Role Administrator 
I should be able to create Deny rules so 
I may deny a role access

There may be inconsistent or undeterminable access for users with multiple roles.  
    
There needs be a way to determine which state the rule is using.

    Possible states
    
    - Explicit Allow (rule set for role)
    - Implicit Allow (inherited rule)
    - Explicit Deny (rule set for role)
    - Implicit Deny (inherited rule or no rule)
            
Active Directory uses the following rules, in our system this might be configurable:
 - Inherited Deny permissions do not prevent access to an object if the object has an explicit Allow permission entry.
 - Explicit permissions take precedence over inherited permissions, even inherited Deny permissions.

To fix:
 - Refactor AuthorizeService
 - Support getAccess() return type (allow, deny or none) in RcmUserACL extended
 - might look at moving some of AuthorizeService into RcmUserACL
 - may decouple from ZF2 ACL class

#### Guest User Identity features (maybe) ####

Story: 
As a consumer of RcmUser
I would like to have a guest user that functions just like a non-guest user 
So that guest user and non-gest user objects are seemless

 - Concept: There is alway a user object tied to session (even if not logged it).
Data may be tracked against the user object properties and may be synced to a user
on log in.
 - Guest user/guestIdentity 
 - if getIdentity is empty return guest?
    allow save updates in session so we can make updates to guest
 - On authenticate, we can try to merge guest user back into auth user
    if session id is the same and should have a flag (only do if requested)
 - Guest may have a time limit so we dont cross pollinate wrong guest
 - Clear both on log out?
 - Might use a event listeners (crud and auth)

#### ACL Exception handling ####
            
Story: 
As a user 
I should be denied access when a role or a resource is not defined withou an exception being thrown
So that my experince is seemless

 - May add suppresion of RcmException when a privilege of resource is not found
 - May add logging of this error

#### AclResourceService Refactor ####

Story: 
As Developer 
I need to refactor AclResourceService 
So that my code is clean, simple and efficient

 - Refactor AclResourceService
 - Use service manager for instantiation only (might be that way currently)
 - AclResourceService only need deal with ResourceProvider and AclResource objects
 - Build ResourceProvider populate method and take array on construct
 - Build AclResource populate method and take array on construct
 
#### Manage Orphaned Resources in for rules ####
 
 Story:
 As ACL
 I should remove rules if the resource no longer exists
 So that I do not retain unused data
 
 - Might do this on rules read
 - This is not a security issue
 
