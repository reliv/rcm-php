# HOW TO DEFINE SuperUser #

```php
    <?php
    // Create a User:
    
    $user = new User();
    $user->setUsername('{yourusername}');
    $user->setPassword('{yourpassword}');
    // Any other data can be set for $user
    
    $user = RcmUserService->createUser(User $requestUser, false);
    
    // Create Role super admin role:
    
    $superAdminRoleId = AclDataService->getSuperAdminRoleId()
    
    $superAdminRole = new AclRole();
    $superAdminRole->setRoleId($superAdminRoleId)
    $superAdminRole->setDescription('Super Admin')
    
    AclDataService->createRole($superAdminRole);
    
    // There is a way to do this with a service, but I think it is protected currently
    DoctrineUserRoleDataMapper->addRole($user, $superAdminRoleId)
```
# HOW TO DEFINE Default Guest Role #

```php
    <?php
    // Create default guest role:
    
    $guestRoleId = AclDataService->getGuestRoleId()
    
    $guestRole = new AclRole();
    $guestRole->setRoleId($guestRoleId);
    $guestRole->setDescription('Default Guest'); 
    AclDataService->createRole($guestRole);
```
