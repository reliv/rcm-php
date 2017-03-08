<?php
/**
 * service_manager.php
 */
return [
    'factories' => [
        'doctrine.cache.doctrine_cache'
        => 'Rcm\Factory\DoctrineCacheFactory',
        'Rcm\Acl\CmsPermissionsChecks'
        => '\Rcm\Factory\CmsPermissionsChecksFactory',
        \Rcm\Acl\ResourceProvider::class
        => 'Rcm\Factory\AclResourceProviderFactory',

        /* BLOCK */
        /* @GammaRelease */
        \Rcm\Block\Config\ConfigFields::class
        => \Rcm\Block\Config\ConfigFieldsFactory::class,
        /* @GammaRelease */
        \Rcm\Block\Config\ConfigRepository::class
        => \Rcm\Block\Config\ConfigRepositoryBcFactory::class,
        /* @GammaRelease */
        \Rcm\Block\Config\ConfigRepositoryJson::class
        => \Rcm\Block\Config\ConfigRepositoryJsonFactory::class,
        /* @GammaRelease */
        \Rcm\Block\DataProvider\DataProviderRepository::class
        => \Rcm\Block\DataProvider\DataProviderRepositoryBasicFactory::class,
        /* @GammaRelease */
        \Rcm\Block\DataProvider\DataService::class
        => \Rcm\Block\DataProvider\DataServiceFactory::class,
        /* @GammaRelease */
        \Rcm\Block\InstanceWithData\InstanceWithDataService::class
        => \Rcm\Block\InstanceWithData\InstanceWithDataServiceFactory::class,
        /* @GammaRelease */
        \Rcm\Block\Instance\InstanceRepository::class
        => \Rcm\Block\Instance\InstanceRepositoryBcFactory::class,
        /* @GammaRelease */
        \Rcm\Block\Renderer\RendererRepository::class
        => \Rcm\Block\Renderer\RendererRepositoryBasicFactory::class,
        /* @GammaRelease */
        \Rcm\Block\Renderer\RendererService::class
        => \Rcm\Block\Renderer\RendererServiceFactory::class,

        \Rcm\Block\Renderer\RendererBc::class => \Rcm\Block\Renderer\RendererBcFactory::class,

        \Rcm\Block\Renderer\RendererMustache::class => \Rcm\Block\Renderer\RendererMustacheFactory::class,
        //@TODO decouple service name from renderer name

        'Rcm\EventListener\EventWrapper'
        => 'Rcm\Factory\EventWrapperFactory',
        'Rcm\EventListener\RouteListener'
        => 'Rcm\Factory\RouteListenerFactory',
        'Rcm\EventListener\DispatchListener'
        => 'Rcm\Factory\DispatchListenerFactory',
        'Rcm\EventListener\EventFinishListener'
        => 'Rcm\Factory\EventFinishListenerFactory',
        'Rcm\EventListener\ViewEventListener'
        => 'Rcm\Factory\ViewEventListenerFactory',
        \Rcm\Middleware\DomainCheck::class
        => \Rcm\Factory\MiddlewareDomainCheckFactory::class,
        \Rcm\Middleware\LocaleSetter::class
        => \Rcm\Factory\MiddlewareLocaleSetterFactory::class,
        \Rcm\Middleware\RedirectCheck::class
        => \Rcm\Factory\MiddlewareRedirectCheckFactory::class,

        /* PAGE *
        /* @GammaRelease */
        \Rcm\Page\Renderer\PageRendererBc::class
        => \Rcm\Page\Renderer\PageRendererBcFactory::class,
        /* @GammaRelease */
        \Rcm\Page\PageData\PageDataService::class
        => \Rcm\Page\PageData\PageDataServiceFactory::class,
        /* @GammaRelease */
        \Rcm\Page\PageStatus\PageStatus::class
        => \Rcm\Page\PageStatus\PageStatusFactory::class,
        /* @GammaRelease */
        \Rcm\Page\PageTypes\PageTypes::class
        => \Rcm\Page\PageTypes\PageTypesFactory::class,

        'Rcm\Service\AssetManagerCache'
        => 'Rcm\Factory\AssetManagerCacheFactory',
        Rcm\Service\Cache::class
        => Rcm\Factory\CacheFactory::class,
        'Rcm\Service\CurrentSite'
        => '\Rcm\Factory\CurrentSiteFactory',
        \Rcm\Service\DomainRedirectService::class
        => \Rcm\Factory\ServiceDomainRedirectServiceFactory::class,
        \Rcm\Service\DomainService::class
        => \Rcm\Factory\ServiceDomainServiceFactory::class,
        'Rcm\Service\HtmlIncludes'
        => 'Rcm\Factory\RcmHtmlIncludesServiceFactory',
        'Rcm\Service\LayoutManager'
        => 'Rcm\Factory\LayoutManagerFactory',
        \Rcm\Service\LocaleService::class
        => \Rcm\Factory\ServiceLocaleServiceFactory::class,
        'Rcm\Service\Logger'
        => 'Rcm\Factory\LoggerFactory',
        \Rcm\Service\PluginManager::class => \Rcm\Service\PluginManagerFactory::class,
        'Rcm\Service\RcmUser'
        => 'Rcm\Factory\RcmUserFactory',
        \Rcm\Service\RedirectService::class
        => \Rcm\Factory\ServiceRedirectServiceFactory::class,
        'Rcm\Service\ResponseHandler'
        => 'Rcm\Factory\ResponseHandlerFactory',
        'Rcm\Service\SessionMgr'
        => 'Rcm\Factory\SessionManagerFactory',
        \Rcm\Service\SiteService::class
        => \Rcm\Factory\ServiceSiteServiceFactory::class,
        'Rcm\Service\ZendLogger'
        => '\Rcm\Factory\ZendLogFactory',
        'Rcm\Service\ZendLogWriter'
        => '\Rcm\Factory\ZendLogWriterFactory',
        'Rcm\Validator\Page'
        => 'Rcm\Factory\PageValidatorFactory',
        'Rcm\Validator\PageTemplate'
        => 'Rcm\Factory\PageTemplateFactory',
        'Rcm\Validator\MainLayout'
        => 'Rcm\Factory\MainLayoutValidatorFactory',
    ],
    'invokables' => [
        'Rcm\Service\DisplayCountService' => 'Rcm\Service\DisplayCountService',
        \Rcm\Block\Instance\InstanceConfigMerger::class => \Rcm\Block\Instance\InstanceConfigMerger::class
    ],
    'aliases' => [
        'rcmLogger' => 'Rcm\Service\Logger',
    ]
];
