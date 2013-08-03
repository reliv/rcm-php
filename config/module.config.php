<?php

/**
 * ZF2 Module Config file for Rcm
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   ContentManager\ZF2
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
return array(

    'reliv' => array(

        'createBlankPagesErrors' => array(
            'missingItems' => 'Please make sure to include a Page Name and select the layout you wish to use.',
            'pageExists' => 'The page URL provided already exists'
        ),

        'saveAsTemplateErrors' => array(
            'missingItems' => 'Please make sure to include a Page Name',
            'pageExists' => 'The page URL provided already exists',
            'revisionNotFound' => 'Unable to locate page revision.  Please contact the administrator.'
        ),

        'createSiteErrors' => array(
            'missingItems' => 'Some needed information is missing.  Please check and make sure to include a domain, country, and language.',
            'countryNotFound' => 'Unable to locate country to save.  Please contact and administrator or try again.',
            'languageNotFound' => 'Unable to locate language to save.  Please contact and administrator or try again.',
            'domainInvalid' => 'Domain exists or is invalid.',
            'newSiteNotImplemented' => 'Creating a new blank site has not been implemented yet.',
            'siteNotFound' => 'Unable to locate the site to clone.  Please contact and administrator or try again.',
        ),

       'adminRichEditor' => 'ckEditor',
//        'adminRichEditor' => 'tinyMce',
        //'adminRichEditor' => 'aloha',

        'adminPanel' => array(
            'Page' => array(
                'display' => 'Page',
                'aclGroups' => 'admin',
                'cssClass' => '',
                'href' => '#',
                'links' => array(
                    'New' => array(
                        'display' => 'New',
                        'aclGroups' => 'admin',
                        'cssClass' => 'newPageIcon',
                        'href' => '#',
                        'links' => array(
                            /*'Blog Post' => array(
                                'display' => 'Blog Post',
                                'aclGroups' => 'admin',
                                'cssClass' => 'blogIcon',
                                'href' => "#",
                            ),*/

                            'Page' => array(
                                'display' => 'Page',
                                'aclGroups' => 'admin',
                                'cssClass' => 'rcmNewPageIcon rcmNewPage',
                                'href' => '#',
                                'onclick' => "rcmEdit.adminPopoutWindow('/rcm-admin-create-blank-page', 430, 740, 'Add New Page'); return false;"
                            ),
                        )
                    ),



                    'Edit' => array(
                        'display' => 'Edit',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'links' => array(
                            'Page' => array(
                                'display' => 'Edit Content',
                                'aclGroups' => 'admin',
                                'cssClass' => 'rcmEditPageIcon rcmEditPage',
                                'href' => '#',
                            ),
                            'Page Layout' => array(
                                'display' => 'Add/Remove Plugins on Page',
                                'aclGroups' => 'admin',
                                'cssClass' => 'rcmLayoutIcon rcmShowLayoutEditor',
                                'href' => '#',
                            ),

                            'Page Properties' => array(
                                'display' => 'Page Properties',
                                'aclGroups' => 'admin',
                                'cssClass' => 'PagePropertiesIcon rcmPageProperties',
                                'href' => '#',
                            ),
                        ),
                    ),

//                    'Save' => array(
//                        'display' => 'Save',
//                        'aclGroups' => 'admin',
//                        'cssClass' => 'rcmSaveIcon rcmSaveMenu',
//                        'href' => '#',
//                        'links' => array(
//                            'Save Draft' => array(
//                                'display' => 'Save Draft',
//                                'aclGroups' => 'admin',
//                                'cssClass' => 'rcmSaveIcon rcmSave',
//                                'href' => '#',
//                            ),
//
//                            'Save and Publish Now' => array(
//                                'display' => 'Save Draft',
//                                'aclGroups' => 'admin',
//                                'cssClass' => 'rcmSaveIcon rcmSave',
//                                'href' => '#',
//                            ),
//
//                            'Save As...' => array(
//                                'display' => 'Save As...',
//                                'aclGroups' => 'admin',
//                                'cssClass' => 'rcmSaveAsIcon',
//                                'href' => '#',
//                            ),
//                        )
//                    ),











                    /*'Go To Page...' => array(
                        'display' => 'Go To Page...',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                    )*/

                    'Publish' => array(
                        'display' => 'Publish',
                        'aclGroups' => 'admin',
                        'cssClass' => 'publishIcon',
                        'href' => '#',
                        'links' => array(
                            'Stage' => array(
                                'display' => 'Stage (Only Admins Will See)',
                                'aclGroups' => 'admin',
                                'cssClass' => 'stageIcon',
                                'href' => '#',
                            ),

                            /*'Add To Test Case' => array(
                                'display' => 'Add To Test Case',
                                'aclGroups' => 'admin',
                                'cssClass' => 'testIcon',
                                'href' => '#',
                            ),*/

                            'Publish Now' => array(
                                'display' => 'Publish Now',
                                'aclGroups' => 'admin',
                                'cssClass' => 'publishIcon',
                                'href' => '#',
                            ),
                        ),
                    ),

                    'Copy To...' => array(
                        'display' => 'Copy To...',
                        'aclGroups' => 'admin',
                        'cssClass' => 'copyToIcon',
                        'href' => '#',
                        'links' => array(
                            'Template' => array(
                                'display' => 'Template',
                                'aclGroups' => 'admin',
                                'cssClass' => 'saveAsTemplate',
                                'href' => "#",
                                'onclick' => "rcmEdit.adminPopoutWindow('/rcm-admin-get-save-as-template', 150, 430, 'Copy to Template'); return false;"
                            ),
                        ),
                    ),

                    'Drafts' => array(
                        'display' => 'Drafts',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'links' => array()
                    ),

                    'Restore' => array(
                        'display' => 'Restore',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'links' => array()
                    ),

                ),
            ),

            'Site' => array(
                'display' => 'Site',
                'aclGroups' => 'admin',
                'cssClass' => 'draftsIcon',
                'href' => '#',
                'links' => array(
                    'New Site' => array(
                        'display' => 'New Site',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'onclick' => "rcmEdit.adminPopoutWindow('/rcm-admin-create-site', 430, 740, 'Add New Site'); return false;"
                    ),

                    'Site-Wide Plugins' => array(
                        'display' => 'Site-Wide Plugins',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'links' => array(
                            'Edit Only Site-Wide Plugins' => array(
                                'display' => 'Edit Site-Wide Plugin Content',
                                'aclGroups' => 'admin',
                                'cssClass' => 'rcmEditSiteWideIcon rcmEditSiteWide',
                                'href' => '#',
                            ),
//
//                            'Rollback Site-Wide Plugin' => array(
//                                'display' => 'Rollback Site-Wide Plugin',
//                                'aclGroups' => 'admin',
//                                'cssClass' => 'rcmEditSiteWideIcon rcmEditSiteWide',
//                                'href' => '#',
//                            ),
                        ),
                    ),

                    'Site Properties' => array(
                        'display' => 'Site Properties',
                        'aclGroups' => 'admin',
                        'cssClass' => 'rcmEditSiteWideIcon rcmEditSiteWide',
                        'href' => '#',
                    ),
                ),
            ),
//            'Users' => array(
//                'display' => 'Users',
//                'aclGroups' => 'admin',
//                'cssClass' => 'draftsIcon',
//                'href' => '#',
//                'links' => array(
//                    'New' => array(
//                        'display' => 'New',
//                        'aclGroups' => 'admin',
//                        'cssClass' => 'rcmNewPageIcon rcmNewPage',
//                        'href' => '#',
//                        'onclick' => "rcmEdit.adminPopoutWindow('/rcm-admin-create-new-user', 430, 740, 'Add New User'); return false;"
//                    ),
//
//                    'Modify' => array(
//                        'display' => 'Page',
//                        'aclGroups' => 'admin',
//                        'cssClass' => 'rcmNewPageIcon rcmNewPage',
//                        'href' => '#',
//                        'onclick' => "rcmEdit.adminPopoutWindow('/rcm-admin-create-blank-page', 430, 740, 'Add New Page'); return false;"
//                    ),
//
//                    'Suspend' => array(
//                        'display' => 'Page',
//                        'aclGroups' => 'admin',
//                        'cssClass' => 'rcmNewPageIcon rcmNewPage',
//                        'href' => '#',
//                        'onclick' => "rcmEdit.adminPopoutWindow('/rcm-admin-create-blank-page', 430, 740, 'Add New Page'); return false;"
//                    ),
//
//                    'Delete' => array(
//                        'display' => 'Page',
//                        'aclGroups' => 'admin',
//                        'cssClass' => 'rcmNewPageIcon rcmNewPage',
//                        'href' => '#',
//                        'onclick' => "rcmEdit.adminPopoutWindow('/rcm-admin-create-blank-page', 430, 740, 'Add New Page'); return false;"
//                    ),
//                )
//            ),
//
//            'Help' => array(
//                'display' => 'Help',
//                'aclGroups' => 'admin',
//                'cssClass' => 'draftsIcon',
//                'href' => '#',
//                'links' => array(
//                    'Contents' => array(
//                        'display' => 'Contents',
//                        'aclGroups' => 'admin',
//                        'cssClass' => 'draftsIcon',
//                        'href' => '#',
//                    ),
//
//                    'About Wespress' => array(
//                        'display' => 'About Wespress',
//                        'aclGroups' => 'admin',
//                        'cssClass' => 'draftsIcon',
//                        'href' => '#',
//                    )
//                )
//            ),

        ),
    ),

    'includeFileManager' => array(
        'files' => array(
            'style.css' => array(
                'destination' => __DIR__ . '/../../../../public/css',
                'header' => __DIR__ . '/../../../../public/css/styleHeader.css',
            ),
            'editStyle.css' => array(
                'destination' => __DIR__ . '/../../../../public/css',
                'header' =>
                __DIR__ . '/../../../../public/css/editStyleHeader.css',
            ),
            'script.js' => array(
                'destination' => __DIR__ . '/../../../../public/js',
                'header' => __DIR__ . '/../../../../public/js/scriptHeader.js',
            ),
            'editScript.js' => array(
                'destination' => __DIR__ . '/../../../../public/js',
                'header' =>
                __DIR__ . '/../../../../public/js/editScriptHeader.js',
            ),
        ),
    ),


    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml'
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'getContentBasePath' => '\Rcm\View\Helper\GetContentBasePath',
            'getLayoutContainer' => '\Rcm\View\Helper\GetLayoutContainer',
            'addAdminNavigation' => '\Rcm\View\Helper\AddAdminNavigation',
            'renderLayoutEditorContainers'
                => '\Rcm\View\Helper\RenderLayoutEditorContainers',
            'adminTitleBar' => '\Rcm\View\Helper\AdminTitleBar',
            'rcmViewInit' => '\Rcm\View\Helper\RcmViewInit',
            'renderPlugin' => 'Rcm\View\Helper\RenderPlugin',
            'rcmJsLoginManager'=>'\Rcm\View\Helper\JsLoginManager',
        ),
    ),

    'router' => array(
        'routes' => array(

            'contentManager' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm[/:page][/:language][/:revision]',
                    'defaults' => array(
                        'controller' => 'rcmIndexController',
                        'action' => 'index',
                    )
                ),
            ),

            'contentManagerWithPageType' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm/:pageType/:page[/:language][/:revision]',
                    'constraints' => array(
                        'pageType' => '[a-z]',
                    ),
                    'defaults' => array(
                        'controller' => 'rcmIndexController',
                        'action' => 'index',
                    )
                ),
            ),


            'contentManagerSave' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-save/:page/:pageType/:language/:revision',
                    'defaults' => array(
                        'controller' => 'rcmAdminController',
                        'action' => 'savePage',
                    ),
                ),
            ),

            'contentManagerNewInstanceAjax' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-get-instance[/:type[/:instanceId]]',
                    'defaults' => array(
                        'controller' => 'rcmAdminController',
                        'action' => 'getNewInstance',
                    ),
                ),
            ),

            'rcm-admin-checkpage' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-checkpage/:language',
                    'defaults' => array(
                        'controller' => 'rcmAdminController',
                        'action' => 'checkPageNameJson',
                    ),
                ),
            ),

            'rcm-admin-domain' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-check-domain/:language',
                    'defaults' => array(
                        'controller' => 'rcmAdminController',
                        'action' => 'checkDomainJson',
                    ),
                ),
            ),

            'rcm-admin-check-user-name' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-check-user/:language',
                    'defaults' => array(
                        'controller' => 'rcmAdminController',
                        'action' => 'checkUserNameJson',
                    ),
                ),
            ),

            'rcm-api-states' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-api/counties/:country/states',
                    'defaults' => array(
                        'controller' => 'rcmStateApiController',
                        'action' => 'listStates',
                    ),
                ),
            ),

            'rcm-admin-check-emailAddress' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-check-emailAddress/:language',
                    'defaults' => array(
                        'controller' => 'rcmAdminController',
                        'action' => 'checkEmailAddressJson',
                    ),
                ),
            ),

            'rcm-admin-create-blank-page' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-create-blank-page[/:language]',
                    'defaults' => array(
                        'controller'=> 'rcmAdminController',
                        'action' => 'newPageWizard',
                    ),
                ),
            ),

            'rcm-admin-create-new-user' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-create-new-user[/:language]',
                    'defaults' => array(
                        'controller'=> 'rcmAdminController',
                        'action' => 'createNewUser',
                    ),
                ),
            ),

            'rcm-admin-create-site' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-create-site[/:language]',
                    'defaults' => array(
                        'controller'=> 'rcmAdminController',
                        'action' => 'newSiteWizard',
                    ),
                ),
            ),

            'rcm-admin-create-site-create' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-create-site/create/:language',
                    'defaults' => array(
                        'controller'=> 'rcmAdminController',
                        'action' => 'createSite',
                    ),
                ),
            ),

            'rcm-admin-create-blank-page_create' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-create-blank-page/create/:language',
                    'defaults' => array(
                        'controller'=> 'rcmAdminController',
                        'action' => 'createBlankPage',
                    ),
                ),
            ),

            'rcm-admin-create-from-template' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-create-from-template/:language',
                    'defaults' => array(
                        'controller' => 'rcmAdminController',
                        'action' => 'newFromTemplate',
                    ),
                ),
            ),

            'rcm-admin-get-save-as-template' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-get-save-as-template/:language',
                    'defaults' => array(
                        'controller' => 'rcmAdminController',
                        'action' => 'getSaveAsTemplate',
                    ),
                ),
            ),

            'rcm-admin-save-as-template' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-save-as-template/:language',
                    'defaults' => array(
                        'controller' => 'rcmAdminController',
                        'action' => 'saveAsTemplate',
                    ),
                ),
            ),

            'contentManagerPublish' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-publish/:pageType/:page/:language/:revision',
                    'defaults' => array(
                        'controller'=> 'rcmAdminController',
                        'action' => 'publishPage',
                    ),
                ),
            ),

            'contentManagerStage' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-stage/:pageType/:page/:language/:revision',
                    'defaults' => array(
                        'controller'=> 'rcmAdminController',
                        'action' => 'stagePage',
                    ),
                ),
            ),

            'rcm-plugin-admin-proxy' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' =>
                    '/rcm-plugin-admin-proxy/:pluginName/:instanceId/:pluginActionName',
                    'defaults' => array(
                        'controller' => 'rcmPluginProxyController',
                        'action' => 'adminProxy',
                    )
                ),
            ),

            'plugin-ajax-proxy' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' =>
                    '/plugin-ajax-proxy/:pluginName/:instanceId/:pluginActionName',
                    'defaults' => array(
                        'controller' =>'rcmPluginProxyController',
                        'action' => 'ajaxProxy',
                    )
                ),
            ),

            'moduleAssets' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' =>
                    '/assets/modules/:moduleName/',
                    'defaults' => array(
                        'controller' => 'Rcm\Controller\AssetsController',
                        'action' => 'modules',
                    )
                ),
                //Had to add child wildcard route to allow file paths at end
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => array(
                            'key_value_delimiter' => 'no_key_value_delimiter',
                            'param_delimiter' => 'no_param_delimiter',
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),

            'blog' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/blog[/:page[/:language]]',
                    'defaults' => array(
                        'controller' => 'rcmIndexController',
                        'action' => 'index',
                    )
                ),
            ),

            'rcm-page-title-search' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-page-search/title/[:query]',
                    'defaults' => array(
                        'controller' => 'rcmPageSearchApiController',
                        'action' => 'siteTitleSearch',
                    )
                ),
            ),

            'rcm-page-search' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-page-search[/:language]',
                    'defaults' => array(
                        'controller' => 'rcmPageSearchApiController',
                        'action' => 'allSitePages',
                    )
                ),
            ),

            'adminContentProxy' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' =>
                    '/content/',
                    'defaults' => array(
                        'controller' => 'AdminContentController',
                        'action' => 'content',
                    )
                ),
                //Had to add child wildcard route to allow file paths at end
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => array(
                            'key_value_delimiter' => 'no_key_value_delimiter',
                            'param_delimiter' => 'no_param_delimiter',
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),

            /**
             * Do not change the name of this route. Doing so will break the
             * override-based disabling of this route
             */
            'rcmInstall' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm/install',
                    'defaults' => array(
                        'controller' => 'rcmInstallController',
                        'action' => 'index',
                    )
                ),
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'relivContentManager' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Rcm/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Rcm' => 'relivContentManager'
                )
            )
        ),
        'configuration' => array(
            'orm_default' => array(
                'metadata_cache'    => 'doctrine_cache',
                'query_cache'       => 'doctrine_cache',
                'result_cache'      => 'doctrine_cache',
            )
        ),
    ),

    'rcmCache' => array(
        'adapter' => 'Memory',
        'plugins' => array(),
        'options' => array(
            'namespace' => 'RcmCache'
        )
    )
);