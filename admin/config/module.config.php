<?php

/**
 * ZF2 Module Config file for Rcm
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 */
return [
    /* asset_manager */
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-admin/' => __DIR__ . '/../public/',
            ],
            'collections' => [
//                'modules/rcm/rcm.js' => [
//                    'vendor/angular-utils-pagination/dirPagination.js',
//                ],
                'modules/rcm-admin/admin.js' => [

                    /* <core> */
                    'modules/rcm-admin/core/rcm-admin-api.js',
                    // RcmUser services - include using ZF2
                    'modules/rcm-user/rcm-user-roles-service.js',
                    'modules/rcm-user/rcm-user-role-selector.js',
                    'modules/rcm-admin/core/rcm-permissions.js',
                    /* </core> */

                    /* <rcm-file-chooser> */
                    'modules/rcm-admin/rcm-file-chooser/rcm-file-chooser-module.js',
                    'modules/rcm-admin/rcm-file-chooser/rcm-file-chooser.js',
                    'modules/rcm-admin/rcm-file-chooser/rcm-file-chooser-service.js',
                    'modules/rcm-admin/rcm-file-chooser/elfinder-file-chooser.js',
                    /* </rcm-file-chooser> */

                    /* <rcm-input> */
                    'modules/rcm-admin/rcm-input/rcm-input-module.js',
                    'modules/rcm-admin/rcm-input/rcm-input-image-directive.js',
                    'modules/rcm-admin/rcm-input/rcm-input-link-url-directive.js',
                    /* </rcm-input> */

                    // general service - requires rcm-core
                    'modules/rcm-admin/rcm-page-admin-panel/rcm-admin-menu.js',
                    'modules/rcm-admin/rcm-column-resize/rcm-column-resize.js',
                    /* <rcm-page-admin> */
                    'modules/rcm-admin/rcm-page-admin/rcm-block-editor-field-dialog-factory.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-block-editor-field-dialog.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-block-editor-legacy-factory.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-block-editor-noop-factory.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-admin-block-editor-registry.js',

                    'modules/rcm-admin/rcm-page-admin/rcm-admin-service-config.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-admin-model.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-admin-view-model.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-admin-plugin-edit-js.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-admin-plugin.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-admin-container.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-admin-page.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-admin-service.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-admin-service-edit-button-action.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-admin-service-html-editor-link.js',
                    'modules/rcm-admin/rcm-page-admin/angular-rcm-admin.js',
                    'modules/rcm-admin/rcm-page-admin/available-plugins-menu.js',
                    'modules/rcm-admin/rcm-page-admin/plugin-drag.js',
                    'modules/rcm-admin/rcm-page-admin/rcm-session-keep-alive.js',
                    'modules/rcm-admin/rcm-page-admin/edit-check-warning.js',
                    'modules/rcm-admin/rcm-page-admin/page-not-found.js',
                    /* </rcm-page-admin> */

                    'modules/rcm-admin/plugin-admin/ajax-plugin-edit-helper.js',
                    'modules/rcm-admin/plugin-admin/jquery-dialog-inputs.js',
                    'modules/rcm-admin/page-permissions/page-permissions.js',
                    'modules/rcm-admin/manage-sites/rcm-admin-manage-sites.js',
                    'modules/rcm-admin/create-site/rcm-admin-create-site.js',
                    'modules/rcm-admin/site-page-copy/rcm-admin-site-page-copy.js',
                    'modules/rcm-admin/save-ajax-admin-window/rcm-save-ajax-admin-window.js',

                    // features
                    'modules/rcm-admin/page-properties/page-properties.js',
                    'modules/rcm-admin/page-delete/page-delete.js',
                ],
                'modules/rcm-admin/admin.css' => [
                    'modules/rcm-admin/core/styles.css',
                    'modules/rcm-admin/plugin-admin/admin-jquery-ui.css',
                    /* <rcm-input> */
                    'modules/rcm-admin/rcm-input/rcm-input.css',
                    /* </rcm-input> */
                    'modules/rcm-admin/rcm-page-admin/layout-editor.css',
                    'modules/rcm-admin/rcm-page-admin-panel/panel.css',
                    'modules/rcm-admin/rcm-page-admin-panel/navigation.css',
                    'modules/rcm-admin/rcm-column-resize/style.css',
                    // RcmUser services - CSS
                    'modules/rcm-user/rcm-user-role-selector.css',
                    'modules/rcm-admin/page-permissions/permissions.css',
                ],
            ],
        ],
    ],
    /* controllers */
    'controllers' => [
        'factories' => [
            RcmAdmin\Controller\ApiAdminSitesCloneController::class
            => RcmAdmin\Factory\ApiAdminSitesCloneControllerFactory::class,

            RcmAdmin\Controller\ApiAdminManageSitesController::class
            => RcmAdmin\Factory\ApiAdminManageSitesControllerFactory::class,

            RcmAdmin\Controller\ApiAdminSitePageController::class
            => RcmAdmin\Factory\ApiAdminSitePageControllerFactory::class,

            RcmAdmin\Controller\ApiAdminSitePageCloneController::class
            => RcmAdmin\Factory\ApiAdminSitePageCloneControllerFactory::class,

            RcmAdmin\Controller\AvailableBlocksJsController::class
            => RcmAdmin\Factory\AvailableBlocksJsControllerFactory::class,

            RcmAdmin\Controller\PageController::class
            => RcmAdmin\Factory\PageControllerFactory::class,
        ],
        'invokables' => [
            RcmAdmin\Controller\PagePermissionsController::class
            => RcmAdmin\Controller\PagePermissionsController::class,

            RcmAdmin\Controller\PageViewPermissionsController::class
            => RcmAdmin\Controller\PageViewPermissionsController::class,

            RcmAdmin\Controller\ApiAdminCurrentSiteController::class
            => RcmAdmin\Controller\ApiAdminCurrentSiteController::class,

            RcmAdmin\Controller\ApiAdminLanguageController::class
            => RcmAdmin\Controller\ApiAdminLanguageController::class,

            RcmAdmin\Controller\ApiAdminThemeController::class
            => RcmAdmin\Controller\ApiAdminThemeController::class,

            RcmAdmin\Controller\ApiAdminCountryController::class
            => RcmAdmin\Controller\ApiAdminCountryController::class,

            RcmAdmin\Controller\ApiAdminPageTypesController::class
            => RcmAdmin\Controller\ApiAdminPageTypesController::class,

            RcmAdmin\Controller\RpcAdminCanEdit::class
            => RcmAdmin\Controller\RpcAdminCanEdit::class,

            RcmAdmin\Controller\RpcAdminKeepAlive::class
            => RcmAdmin\Controller\RpcAdminKeepAlive::class,

//            RcmAdmin\Controller\ApiAdminCheckPermissionsController::class
//            => RcmAdmin\Controller\ApiAdminCheckPermissionsController::class,
        ],
    ],
    /* form_elements */
    'form_elements' => [
        'invokables' => [
            'mainLayout' => RcmAdmin\Form\Element\MainLayout::class,
        ],
        'factories' => [
            RcmAdmin\Form\NewPageForm::class
            => RcmAdmin\Factory\NewPageFormFactory::class,
//Disabled durring immutable history project in 2018-10 since no-one is using it
            //            RcmAdmin\Form\CreateTemplateFromPageForm::class
            //            => RcmAdmin\Factory\CreateTemplateFromPageFormFactory::class,
        ],
    ],
    /* includeFileManager */
    'includeFileManager' => [
        'files' => [
            'style.css' => [
                'destination' => __DIR__ . '/../../../../public/css',
                'header' => __DIR__ . '/../../../../public/css/styleHeader.css',
            ],
            'editStyle.css' => [
                'destination' => __DIR__ . '/../../../../public/css',
                'header' =>
                    __DIR__ . '/../../../../public/css/editStyleHeader.css',
            ],
            'script.js' => [
                'destination' => __DIR__ . '/../../../../public/js',
                'header' => __DIR__ . '/../../../../public/js/scriptHeader.js',
            ],
            'editScript.js' => [
                'destination' => __DIR__ . '/../../../../public/js',
                'header' =>
                    __DIR__ . '/../../../../public/js/editScriptHeader.js',
            ],
        ],
    ],
    /* navigation */
    'navigation' => [
        'RcmAdminMenu' => [
            'Page' => [
                'label' => 'Page',
                'uri' => '#',
                'pages' => [
                    'New Page' => [
                        'label' => 'New Page',
                        'route' => 'RcmAdmin\Page\New',
                        'class' => 'rcmAdminMenu RcmFormDialog icon-after new-page',
                        'title' => 'New Page',
                    ],
                    'Edit' => [
                        'label' => 'Edit',
                        'uri' => '#',
                        'pages' => [
                            'PageProperties' => [
                                'label' => 'Page Properties',
                                'class' => 'rcmAdminMenu RcmBlankDialog',
                                'title' => 'Page Properties',
                                'uri' => '/modules/rcm-admin/page-properties/page-properties.html',
                            ],
                            'PagePermissions' => [
                                'label' => 'Page Permissions',
                                'class' => 'rcmAdminMenu RcmBlankDialog',
                                'title' => 'Page Permissions',
                                'route' => 'RcmAdmin\Page\PagePermissions',
                                'params' => [
                                    'rcmPageName' => ':rcmPageName',
                                    'rcmPageType' => ':rcmPageType',
                                ],
                            ],
                        ],
                    ],
//Disabled durring immutable history project since no-one is using it
                    //                    'Copy To' => [
                    //                        'label' => 'Copy To...',
                    //                        'uri' => '#',
                    //                        'rcmOnly' => true,
                    //                        'pages' => [
                    //                            'Page' => [
                    //                                'label' => 'Template',
                    //                                'route' => 'RcmAdmin\Page\CreateTemplateFromPage',
                    //                                'class' => 'rcmAdminMenu RcmFormDialog',
                    //                                'title' => 'Copy To Template',
                    //                                'params' => [
                    //                                    'rcmPageName' => ':rcmPageName',
                    //                                    'rcmPageType' => ':rcmPageType',
                    //                                    'rcmPageRevision' => ':rcmPageRevision'
                    //                                ],
                    //                                'acl' => [
                    //                                    'providerId' => \Rcm\Acl\ResourceProvider::class,
                    //                                    'resource'
                    //                                    => \Rcm\Acl\ResourceName::RESOURCE_SITES . '.:siteId'
                    //                                        . '.' . \Rcm\Acl\ResourceName::RESOURCE_PAGES . '.create'
                    //                                ]
                    //                            ],
                    //                        ],
                    //                    ],
                    'Drafts' => [
                        'label' => 'Drafts',
                        'uri' => '#',
                        'class' => 'drafts',
                        'rcmIncludeRevisions' => [
                            [
                                'published' => false,
                                'limit' => 10,
                                'page' => [
                                    'label' => ':revisionCreatedDate - :revisionAuthor',
                                    'route' => 'contentManagerWithPageType',
                                    'class' => 'icon-before revision-page',
                                    'text_domain' => 'DO_NOT_TRANSLATE',
                                    'params' => [
                                        'page' => ':rcmPageName',
                                        'pageType' => ':rcmPageType',
                                        'revision' => ':rcmPageRevision',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'Restore' => [
                        'label' => 'Restore',
                        'uri' => '#',
                        'class' => 'restore',
                        'rcmIncludeRevisions' => [
                            [
                                'published' => true,
                                'limit' => 10,
                                'page' => [
                                    'label' => ':revisionPublishedDate - :revisionAuthor',
                                    'route' => 'RcmAdmin\Page\PublishPageRevision',
                                    'class' => 'icon-before restore-page',
                                    'text_domain' => 'DO_NOT_TRANSLATE',
                                    'params' => [
                                        'rcmPageName' => ':rcmPageName',
                                        'rcmPageType' => ':rcmPageType',
                                        'rcmPageRevision' => ':rcmPageRevision',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'Delete' => [
                        'label' => 'Delete',
                        'class' => 'rcmAdminMenu RcmBlankDialog icon-after delete-page',
                        'title' => 'Delete Current Page',
                        'uri' => '/modules/rcm-admin/page-delete/page-delete.html',
                    ],
                ],
            ],
            'Site' => [
                'label' => 'Site',
                'uri' => '#',
                'pages' => [
                    'Manage Sites' => [
                        'label' => 'Manage Sites',
                        'class' => 'rcmAdminMenu rcmStandardDialog icon-after manage-sites',
                        'uri' => '/modules/rcm-admin/manage-sites/manage-sites.html',
                        'title' => 'Manage Sites',
                    ],
                    'Create Site' => [
                        'label' => 'Create Site',
                        'class' => 'rcmAdminMenu rcmStandardDialog icon-after create-site',
                        'uri' => '/modules/rcm-admin/create-site/create-site.html',
                        'title' => 'Create Site',
                    ],
                    'Copy Pages' => [
                        'label' => 'Copy Pages',
                        'class' => '',
                        'uri' => '#/admin/copy-pages',
                        'title' => 'Copy Pages',
                    ],
                    'Change Domain Name' => [
                        'label' => 'Change Domain Name',
                        'uri' => '#/admin/change-current-host',
                    ],
                ],
            ],
            'User' => [
                'label' => 'Users',
                'uri' => '#',
                'pages' => [
                    'Content Change Log' => [
                        'label' => 'Content Change Log',
                        'uri' => '/rcm/change-log?days=30&content-type=text%2Fhtml',
                    ],
                ],
            ],
        ],
    ],
    /* rcmAdmin Config */
    'rcmAdmin' => [
        'createBlankPagesErrors' => [
            'missingItems' => 'Please make sure to include a Page Name and select the'
                . 'layout you wish to use.',
            'pageExists' => 'The page URL provided already exists',
        ],
        'saveAsTemplateErrors' => [
            'missingItems' => 'Please make sure to include a Page Name',
            'pageExists' => 'The page URL provided already exists',
            'revisionNotFound' => 'Unable to locate page revision.  '
                . 'Please contact the administrator.',
        ],
        'createSiteErrors' => [
            'missingItems' => 'Some needed information is missing.  '
                . 'Please check and make sure to include'
                . ' a domain, country, and language.',
            'countryNotFound' => 'Unable to locate country to save.  '
                . 'Please contact and administrator or try again.',
            'languageNotFound' => 'Unable to locate language to save.  '
                . 'Please contact and administrator or try again.',
            'domainInvalid' => 'Domain exists or is invalid.',
            'newSiteNotImplemented' => 'Creating a new blank site has not'
                . ' been implemented yet.',
            'siteNotFound' => 'Unable to locate the site to clone.  '
                . 'Please contact and administrator or try again.',
        ],
        'adminRichEditor' => 'tinyMce',
        'defaultSiteSettings' => [
            'siteLayout' => "GuestSitePage",
            'siteTitle' => "My Site",
            'languageIso6392t' => "eng",
            'countryId' => "USA",
            'status' => "A",
            'favIcon' => "/images/favicon.ico",
            'loginPage' => "/login",
            'notAuthorizedPage' => "/not-authorized",
            'notFoundPage' => "not-found",
            'containers' => [
                'guestTopNavigation',
                'guestMainNavigation',
                'guestRightColumn',
                'guestFooter',
            ],
            'pages' => [
                [
                    'name' => 'index',
                    'pageType' => 'n',
                    'description' => 'Home Page.',
                    'pageTitle' => 'Home',
                    'plugins' => [
                        [
                            'plugin' => 'RcmHtmlArea',
                            'displayName' => 'Home Page Area',
                            'instanceConfig' => [],
                            'layoutContainer' => '4',
                            'saveData' => [
                                'html' => '<h1>Home</h1>',
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'login',
                    'pageType' => 'n',
                    'description' => 'Login Page.',
                    'pageTitle' => 'Login',
                    'plugins' => [
                        [
                            'plugin' => 'RcmLogin',
                            'displayName' => 'Login Area',
                            'instanceConfig' => [],
                            'layoutContainer' => '4',
                            'saveData' => [],
                        ],
                    ],
                ],
                [
                    'name' => 'not-authorized',
                    'pageType' => 'n',
                    'description' => 'Not Authorized Page.',
                    'pageTitle' => 'Not Authorized',
                    'plugins' => [
                        [
                            'plugin' => 'RcmHtmlArea',
                            'displayName' => 'Access Denied Area',
                            'instanceConfig' => [],
                            'layoutContainer' => '4',
                            'saveData' => [
                                'html' => '<h1>Access Denied</h1>',
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'not-found',
                    'pageType' => 'n',
                    'description' => 'Not Found Page.',
                    'pageTitle' => 'Not Found',
                    'plugins' => [
                        [
                            'plugin' => 'RcmHtmlArea',
                            'displayName' => 'Page Not Found Area',
                            'instanceConfig' => [],
                            'layoutContainer' => '4',
                            'saveData' => [
                                'html' => '<h1>Page Not Found</h1>',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'routes' => [
        [
            'path' => '/api/rcm/site/current/domain',
            'middleware' => [
                \Rcm\HttpLib\JsonBodyParserMiddleware::class,
                \RcmAdmin\Controller\SiteDomainNameController::class,
            ],
            'allowed_methods' => ['PUT'],
        ],
        [
            'path' => '/api/rcm/layout-choices',
            'middleware' => [
                \RcmAdmin\Controller\LayoutChoicesController::class,
            ],
            'allowed_methods' => ['GET'],
        ],
    ],
    /* router */
    'router' => [
        'routes' => [
            'RcmAdmin\Page\New' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route' => '/rcm-admin/page/new',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\PageController::class,
                        'action' => 'new',
                    ],
                ],
            ],
//Disabled durring immutable history project since no-one is using it
            //            'RcmAdmin\Page\CreateTemplateFromPage' => [
            //                'type' => 'Zend\Mvc\Router\Http\Segment',
            //                'options' => [
            //                    'route'
            //                    => '/rcm-admin/page/create-template-from-page/:rcmPageType/:rcmPageName[/[:rcmPageRevision]]',
            //                    'defaults' => [
            //                        'controller' => RcmAdmin\Controller\PageController::class,
            //                        'action' => 'createTemplateFromPage',
            //                    ],
            //                ],
            //            ],
            'RcmAdmin\Page\PublishPageRevision' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-admin/page/publish-page-revision/:rcmPageType/:rcmPageName/:rcmPageRevision',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\PageController::class,
                        'action' => 'publishPageRevision',
                    ],
                ],
            ],
            'RcmAdminApiCurrentSite' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/current-site[/:id]',
                    'defaults' => [
                        'id' => 'current',
                        'controller' => RcmAdmin\Controller\ApiAdminCurrentSiteController::class,
                    ],
                ],
            ],
            'ApiAdminManageSitesController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/manage-sites[/:id]',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\ApiAdminManageSitesController::class,
                    ],
                ],
            ],
            'ApiAdminSitesCloneController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/site-copy[/:id]',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\ApiAdminSitesCloneController::class,
                    ],
                ],
            ],
            'ApiAdminLanguageController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/language',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\ApiAdminLanguageController::class,
                    ],
                ],
            ],
            'ApiAdminThemeController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/theme',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\ApiAdminThemeController::class,
                    ],
                ],
            ],
            'ApiAdminCountryController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/country',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\ApiAdminCountryController::class,
                    ],
                ],
            ],
            'ApiAdminSitePageController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/sites/:siteId/pages[/:id]',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\ApiAdminSitePageController::class,
                    ],
                ],
            ],
            'ApiAdminSitePageCloneController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/sites/:siteId/page-copy[/:id]',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\ApiAdminSitePageCloneController::class,
                    ],
                ],
            ],
            'ApiAdminPageTypesController' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/pagetypes',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\ApiAdminPageTypesController::class,
                    ],
                ],
            ],
            'RcmAdmin\\RpcAdminCanEdit' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/rpc/rcm-admin/can-edit',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\RpcAdminCanEdit::class,
                    ],
                ],
            ],
            'RcmAdmin\\RpcAdminKeepAlive' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/rpc/rcm-admin/keep-alive[/:id]',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\RpcAdminKeepAlive::class,
                    ],
                ],
            ],
            'RcmAdmin\Page\SavePage' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-admin/page/save-page/:rcmPageType/:rcmPageName/:rcmPageRevision',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\PageController::class,
                        'action' => 'savePage',
                    ],
                ],
            ],
            'RcmAdmin\Page\PagePermissions' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-admin/page-permissions/:rcmPageType/:rcmPageName',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\PagePermissionsController::class,
                        'action' => 'pagePermissions',
                    ],
                ],
            ],
            'RcmAdmin\Page\GetPermissions' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/page/permissions/[:id]',
                    'constraints' => [
                        'id' => '[a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\PageViewPermissionsController::class,
                    ],
                ],
            ],
//            'RcmAdmin\ApiAdminCheckPermissions' => [
//                'type' => 'Zend\Mvc\Router\Http\Segment',
//                'options' => [
//                    'route' => '/api/admin/check-permissions/:resourceId/:privileges/:id',
//                    'constraints' => [
//                        'id' => '[a-zA-Z0-9._-]+',
//                        'resourceId' => '[a-zA-Z0-9._-]+',
//                        'privileges' => '[a-zA-Z0-9._-]+',
//                    ],
//                    'defaults' => [
//                        'controller' => RcmAdmin\Controller\ApiAdminCheckPermissionsController::class,
//                    ],
//                ],
//            ],
            'rcm-admin.available-block.js' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-admin/available-block.js',
                    'defaults' => [
                        'controller' => RcmAdmin\Controller\AvailableBlocksJsController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    /* service_manager */
    'service_manager' => [
        'config_factories' => [
            \Rcm\SecurityPropertiesProvider\SiteSecurityPropertiesProvider::class => [],
            \Rcm\SecurityPropertiesProvider\PageSecurityPropertiesProvider::class => [
                'arguments' => [
                    \Doctrine\ORM\EntityManager::class
                ]
            ],
            \RcmAdmin\Controller\LayoutChoicesController::class => [
                'arguments' => [
                    \Rcm\Api\GetSiteByRequest::class
                ],
            ],
            \RcmAdmin\Controller\SiteDomainNameController::class => [
                'arguments' => [
                    \Rcm\RequestContext\RequestContext::class,
                    \Rcm\Service\CurrentSite::class
                ],
            ],
            \RcmAdmin\Api\GetPageData::class => [],
        ],
        'factories' => [
            RcmAdmin\EventListener\DispatchListener::class
            => RcmAdmin\Factory\DispatchListenerFactory::class,

            RcmAdmin\Controller\AdminPanelController::class
            => RcmAdmin\Factory\AdminPanelControllerFactory::class,

            'RcmAdminNavigation' => RcmAdmin\Factory\AdminNavigationFactory::class,

            RcmAdmin\Service\RendererAvailableBlocksJs::class
            => RcmAdmin\Service\RendererAvailableBlocksJsFactory::class,
        ],
    ],
    /* view_manager */
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    /* view_helpers */
    'view_helpers' => [
        'invokables' => [
            'displayErrors' => RcmAdmin\View\Helper\DisplayErrors::class,
        ],
    ],
];
