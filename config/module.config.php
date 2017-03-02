<?php
/**
 * ZF2 Module Config file for Rcm
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 */
return [
    /* asset_manager */
    'asset_manager' => require __DIR__ . '/asset_manager.php',
    /* controller_plugins */
    'controller_plugins' => [
        'factories' => [
            'shouldShowRevisions'
            => 'Rcm\Factory\ShouldShowRevisionsPluginFactory',
            'rcmIsAllowed' =>
                'Rcm\Factory\RcmIsAllowedFactory',
            'rcmIsSiteAdmin' =>
                'Rcm\Factory\IsSiteAdminPluginFactory',
            'rcmIsPageAllowed' =>
                '\Rcm\Factory\RcmIsPageAllowedPluginFactory',
        ],
        'invokables' => [
            'redirectToPage'
            => 'Rcm\Controller\Plugin\RedirectToPage',
            'urlToPage'
            => 'Rcm\Controller\Plugin\UrlToPage',
        ],
    ],
    /* controllers */
    'controllers' => require __DIR__ . '/controllers.php',
    /* doctrine */
    'doctrine' => require __DIR__ . '/doctrine.php',
    /* Rcm Config */
    'Rcm' => require __DIR__ . '/rcm-core-config.php',
    /* Blocks */
    'rcmPlugin' => [],
    /* rcmCache */
    'rcmCache' => [
        'adapter' => 'Memory',
        'plugins' => [],
        'options' => [
            //'namespace' => 'RcmCache'
        ]
    ],
    /* RcmUser Config */
    'RcmUser' => require __DIR__ . '/rcm-user-config.php',
    /* route_manager */
    'route_manager' => [
        'invokables' => [
            'Rcm\Route\Cms' => 'Rcm\Route\Cms'
        ],
    ],
    /* router */
    'router' => require __DIR__ . '/router.php',
    /* service_manager */
    'service_manager' => require __DIR__ . '/service_manager.php',
    /* view_helpers */
    'view_helpers' => [
        'factories' => [
            'rcmContainer'
            => 'Rcm\Factory\ContainerViewHelperFactory',
            'rcmTextEdit' => 'Rcm\Factory\TextEditFactory',
            'rcmRichEdit' => 'Rcm\Factory\RichEditFactory',
            'rcmHtmlIncludes' => 'Rcm\Factory\RcmHtmlIncludesHelperFactory',
        ],
        'invokables' => [
            /* <OVER-RIDE ZF2 HELPERS> */
            'headmeta'
            => 'Rcm\View\Helper\HeadMeta',
            'headtitle'
            => 'Rcm\View\Helper\HeadTitle',
            'headlink'
            => 'Rcm\View\Helper\HeadLink',
            /* </OVER-RIDE ZF2 HELPERS> */
            'rcmOutOfDateBrowserWarning'
            => 'Rcm\View\Helper\OutOfDateBrowserWarning',
            'urlToPage'
            => 'Rcm\View\Helper\UrlToPage',
            'revisionHelper'
            => 'Rcm\View\Helper\RevisionHelper',

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
];
