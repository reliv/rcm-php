HTML views/pages for RcmUser
====================

Features
--------

### UI ###

There are a limited amount of included HTML views/pages.

Views are designed using Twitter Bootstrap and AngularJS.

Views are design to be mostly independent of the framework (MVC move to Angular and data is deliver VIA REST/JSON API).

#### Available Views ####

- Rcm User Admin Acl:
 - View for creating and editing roles and rules
 - Requires access to resource: rcmuser-acl-administration

- Rcm User Admin Users:
 - View for administrating User data
 - Requires access to resource: rcmuser-user-administration

Project
-------

##### Company:
Copyright Reliv' International, Inc. 2015

##### Project homepage: #####
https://github.com/reliv/rcm-user-ui

##### Project author: #####
James Jervis
jjervis@relivinc.com
https://github.com/reliv

@TODO
-----

#### Addition Default Views ####

Story: 
As a user 
I should have access to edit my own user profile for certain fields based on configurable rules
So that I may update my profile data
    
 - User can edit own profile
 
As a user 
I should have access to a login page
So that i can log in to the site

As a user 
I should have be able to securely reset my password
So that I can get into my account if I forget or lose my password

- Standard email link to password reset

#### Admin Edit Profile Updates ####

Story:
As User when I access a user profile edit page
I should see a list of links or tabs to other profile data
So I can have quick access to all my user properties
    
 - User property links on user edit/profile pages:
 - Simple Interface to register profiles with links
 
#### Admin Role and Rule UI Updates ####

Story: 
As a Administrator 
I should be able to paginate and filter Role and User lists on the Admin screens
So that I can quickly and efficiently edit Users and Roles

- Admin User list should paginate and filter from server-side
- Implement data mapper method for:
- findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
