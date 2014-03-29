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
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
return array(

    'Rcm' => array(
        'successfulLoginUrl' => '/'
    ),

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
        'display_not_found_reason' => false,
        'display_exceptions' => false,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml'
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(

        ),
    ),

    'router' => array(
        'routes' => array(

            'contentManager' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm[/:page][/:revision]',
                    'defaults' => array(
                        'controller' => 'rcmIndexController',
                        'action' => 'index',
                    )
                ),
            ),

            'contentManagerWithPageType' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm/:pageType/:page[/:revision]',
                    'constraints' => array(
                        'pageType' => '[a-z]',
                    ),
                    'defaults' => array(
                        'controller' => 'rcmIndexController',
                        'action' => 'index',
                    )
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
                        'controller' => 'rcmPluginProxyController',
                        'action' => 'ajaxProxy',
                    )
                ),
            ),

            'blog' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/blog[/:page]',
                    'defaults' => array(
                        'controller' => 'rcmIndexController',
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
                'metadata_cache' => 'doctrine_cache',
                'query_cache' => 'doctrine_cache',
                'result_cache' => 'doctrine_cache',
            )
        ),
    ),

    'rcmCache' => array(
        'adapter' => 'Memory',
        'plugins' => array(),
        'options' => array(
            //'namespace' => 'RcmCache'
        )
    ),

    'rcmLogger' => array(
        'writer' => 'rcmLogWriterStub'
    ),

    'rcmLogWriter' => array(
        'logPath' => '',
    )

);