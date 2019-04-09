<?php
/**
 * rcm-core-config.php
 */
return [
    /**
     * @GammaRelease
     */
    'blocks' => [
        // 'module/path/', to block.json
    ],
    /**
     * defaultDomain to use if domain not found E.I. IP address is used
     */
    'defaultDomain' => null,

    /**
     * defaultLocale
     */
    'defaultLocale' => 'en_US',

    /**
     * defaultPluginIcon - Default icon in not defined by plugin
     */
    'defaultPluginIcon' => '/modules/rcm/images/no-plugin-icon.png',

    /**
     * Renderer aliases
     */
    'block-render' => [
        'rcm-plugin-bc' => \Rcm\Block\Renderer\RendererBc::class,
        'mustache' => \Rcm\Block\Renderer\RendererMustache::class,
        'clientReact' => \Rcm\Block\Renderer\RendererClientReact::class,
    ],

    /**
     * Available page types
     */
    'pageTypes' => [
        \Rcm\Page\PageTypes\PageTypes::NORMAL => [
            'type' => \Rcm\Page\PageTypes\PageTypes::NORMAL,
            'title' => 'Normal Page',
            'canClone' => true,
        ],
        \Rcm\Page\PageTypes\PageTypes::TEMPLATE => [
            'type' => \Rcm\Page\PageTypes\PageTypes::TEMPLATE,
            'title' => 'Template Page',
            'canClone' => true,
        ],
        \Rcm\Page\PageTypes\PageTypes::SYSTEM => [
            'type' => \Rcm\Page\PageTypes\PageTypes::SYSTEM,
            'title' => 'System Page',
            'canClone' => true,
        ],
    ],

    /**
     * NOTE: this only works the the PageRenderer
     *
     * If a page (substitutePage) is rendered instead of the page requested (requestedPage)
     * and the name of the substitutePage can be tied to a status
     *
     * for example:
     * requestedPageName='home' but does not exist
     * substitutePageName='not-found'
     *
     * status map has
     * ['not-found' => 404]
     *
     * so page will return with status 404 on substitution
     */
    'pageNameStatusMap' => [
        'not-found' => \Rcm\Page\PageStatus\PageStatus::STATUS_NOT_FOUND,
        '404' => \Rcm\Page\PageStatus\PageStatus::STATUS_NOT_FOUND,
        'not-authorized' => \Rcm\Page\PageStatus\PageStatus::STATUS_NOT_AUTHORIZED,
        '401' => \Rcm\Page\PageStatus\PageStatus::STATUS_NOT_AUTHORIZED,
    ],

    /**
     * successfulLoginUrl
     */
    'successfulLoginUrl' => '/',

    /**
     * Access Control config
     */
    'Acl' => [
        'sites' => [
            'resourceId' => \Rcm\Acl\ResourceName::RESOURCE_SITES,
            'parentResourceId' => null,
            'privileges' => [
                'read',
                'update',
                'create',
                'delete',
                'theme',
                'admin',
            ],
            'name' => 'Sites',
            'description' => 'Global resource for sites',
        ],
        'pages' => [
            'resourceId' => \Rcm\Acl\ResourceName::RESOURCE_PAGES,
            'parentResourceId' => null,
            'privileges' => [
                'read',
                'edit',
                'create',
                'delete',
                'copy',
                'approve',
                'layout',
                'revisions'
            ],
            'name' => 'Pages',
            'description' => 'Global resource for pages',
        ],
        'widgets' => [
            'resourceId' => 'widgets',
            'parentResourceId' => null,
            'privileges' => [
                'update',
            ],
            'name' => 'Widgets',
            'description' => 'Global resource for Rcm Widgets',
        ],
        // @deprecated <deprecated-site-wide-plugin>
        'widgets.siteWide' => [
            'resourceId' => 'widgets.siteWide',
            'parentResourceId' => 'widgets',
            'privileges' => [
                'update',
                'create',
                'delete',
            ],
            'name' => 'Sitewide Widgets',
            'description' => 'Global resource for Rcm Site Wide Widgets',
        ],
    ],

    /**
     * RcmCmsPageRouteNames
     */
    'RcmCmsPageRouteNames' => [
        'contentManager' => 'contentManager',
        'contentManagerWithPageType' => 'contentManagerWithPageType',
        'blog' => 'blog',
    ],

    /**
     * Scripts to be required always on every page
     */
    'HtmlIncludes' => [

        /**
         * Set the script key to use
         * Useful for setting up prebuilt (minimized and combined) files
         */
        'defaultScriptKey' => 'scripts',

        /**
         * Set the stylesheet key to use
         * Useful for setting up prebuilt (minimized and combined) files
         */
        'defaultStylesheetKey' => 'stylesheets',

        /**
         * This determines the order of the head sections, thus, loading order of scripts and css
         */
        'sections' => [
            'pre-config',
            'config',
            'post-config',
            'pre-libraries',
            'libraries',
            'post-libraries',
            'pre-rcm',
            'rcm',
            'post-rcm',
            'pre-modules',
            'modules',
            'post-modules',
        ],

        /**
         * Meta tags that will always be loaded
         * Example
         * 'keyValue' => [
         *  'content' => 'value',
         *  'modifiers' => [],
         * ],
         */
        'headMetaName' => [
            'X-UA-Compatible' => [
                'content' => 'IE=edge',
            ],
            'viewport' => [
                'content' => 'width=device-width, initial-scale=1',
            ],
        ],

        /**
         * @deprecated Use 'scripts'
         * Script files that will always be loaded
         * Example
         * '/script/url' => [
         *  'type' => 'text/javascript',
         *  'attrs' => []
         * ],
         */
        'headScriptFile' => [
        ],

        /**
         * Script files that will always be loaded
         * Example:
         * 'section' => [
         *  '/script/url' => [
         *   'type' => 'text/javascript',
         *   'attrs' => []
         *  ],
         * ],
         */
        'scripts' => [
            'pre-config' => [],
            'config' => [],
            'post-config' => [],
            'pre-libraries' => [],
            'libraries' => [
                //                '/bower_components/jquery/dist/jquery.min.js' => [], (moved to app webpack build)
                '/bower_components/jquery-migrate/jquery-migrate.min.js' => [],
                // @todo Move this config to the modules that use it
                '/bower_components/jquery-ui/jquery-ui.min.js' => [],
//                '/node_modules/angular/angular.min.js' => [], //moved to app webpack build
                '/bower_components/angular-route/angular-route.min.js' => [],
                '/bower_components/bootbox/bootbox.js' => [],
                '/bower_components/bootstrap/dist/js/bootstrap.min.js' => [],
                '/bower_components/tinymce/tinymce.min.js' => [],

                '/bower_components/rcm-js-lib/dist/rcm-js-lib.min.js' => [],
                '/bower_components/rcm-loading/dist/rcm-loading.min.js' => [],
                '/bower_components/rcm-loading/dist/angular-rcm-loading.min.js' => [],
                '/bower_components/rcm-loading/dist/jquery-loader.min.js' => [],
//                '/modules/rcm/core-js/dist/rcm-core-js.js' => [], //moved to webpack
            ],
            'post-libraries' => [],
            'pre-rcm' => [],
            'rcm' => [
//                '/modules/rcm/rcm.js' => [],
            ],
            'post-rcm' => [
                '/bower_components/angular-utils-pagination/dirPagination.js' => [],
            ],
            'pre-modules' => [
                '/bower_components/rcm-dialog/dist/rcm-dialog.min.js' => [],
//                '/modules/rcm/rcm-html-editor/dist/adapter-tinymce/rcm-html-editor.min.js' => [], //moved to webpack
//                '/modules/rcm/rcm-html-editor/dist/rcm-html-editor.min.js' => [],// moved to webpack
            ],
            'modules' => [
                '/modules/rcm/modules.js' => [],
            ],
            'post-modules' => [],
        ],

        /**
         * @deprecated Use 'stylesheets'
         * Stylesheet files that will always be loaded
         * Example
         * '/stylesheet/url' => [
         * 'media' => 'screen',
         * 'conditionalStylesheet' => '',
         * 'extras' => []
         * ],
         */
        'headLinkStylesheet' => [
        ],

        /**
         * Stylesheet files that will always be loaded
         * Example:
         * 'section' => [
         *  '/stylesheet/url' => [
         *   'media' => 'screen',
         *   'conditionalStylesheet' => '',
         *   'extras' => []
         *  ],
         * ],
         */
        'stylesheets' => [
            'pre-config' => [],
            'config' => [],
            'post-config' => [],
            'pre-libraries' => [],
            'libraries' => [
                '/bower_components/bootstrap/dist/css/bootstrap.min.css' => [],
            ],
            'post-libraries' => [],
            'pre-rcm' => [
                '/bower_components/jquery-bootstrap-theme/css/custom-theme/jquery-ui-1.10.3.custom.css' => [],
            ],
            'rcm' => [
//                '/modules/rcm/rcm.css' => ['media' => 'screen,print'],
            ],
            'post-rcm' => [
//                '/modules/rcm/rcm-html-editor/dist/adapter-tinymce/rcm-html-editor.min.css' => [],// moved to webpack
            ],
            'pre-modules' => [],
            'modules' => [
                '/modules/rcm/modules.css' => ['media' => 'screen,print'],
            ],
            'post-modules' => [],
        ],
    ],
    'siteExistsCheckIgnoredUrls' => []
];
