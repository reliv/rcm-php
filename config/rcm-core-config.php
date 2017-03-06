<?php
/**
 * rcm-core-config.php
 */
return [
    /**
     * @GammaRelease
     */
    'block-default-render' => \Rcm\Block\Renderer\RendererBc::class,

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

    'defaultLocale' => 'en_US',

    /**
     * defaultPluginIcon - Default icon in not defined by plugin
     */
    'defaultPluginIcon' => '/modules/rcm/images/no-plugin-icon.png',

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
            'resourceId' => 'sites',
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
            'resourceId' => 'pages',
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
            'libraries' => [],
            'post-libraries' => [],
            'pre-rcm' => [],
            'rcm' => [
                '/modules/rcm/rcm.js' => [],
            ],
            'post-rcm' => [],
            'pre-modules' => [],
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
            'libraries' => [],
            'post-libraries' => [],
            'pre-rcm' => [],
            'rcm' => [
                '/modules/rcm/rcm.css' => [],
            ],
            'post-rcm' => [],
            'pre-modules' => [],
            'modules' => [
                '/modules/rcm/modules.css' => [],
            ],
            'post-modules' => [],
        ],
    ]
];
