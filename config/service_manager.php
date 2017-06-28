<?php
/**
 * service_manager.php
 */
return [
    'factories' => [
        'doctrine.cache.doctrine_cache'
        => \Rcm\Factory\DoctrineCacheFactory::class,
        // WAS: 'Rcm\Acl\CmsPermissionsChecks'
        \Rcm\Acl\CmsPermissionChecks::class
        => \Rcm\Factory\CmsPermissionsChecksFactory::class,

        \Rcm\Acl\ResourceProvider::class
        => \Rcm\Factory\AclResourceProviderFactory::class,

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

        \Rcm\EventListener\EventWrapper::class
        => \Rcm\Factory\EventWrapperFactory::class,
        \Rcm\EventListener\RouteListener::class
        => \Rcm\Factory\RouteListenerFactory::class,
        \Rcm\EventListener\DispatchListener::class
        => \Rcm\Factory\DispatchListenerFactory::class,
        \Rcm\EventListener\EventFinishListener::class
        => \Rcm\Factory\EventFinishListenerFactory::class,
        \Rcm\EventListener\ViewEventListener::class
        => \Rcm\Factory\ViewEventListenerFactory::class,
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

        \Rcm\Service\AssetManagerCache::class
        => \Rcm\Factory\AssetManagerCacheFactory::class,
        Rcm\Service\Cache::class
        => Rcm\Factory\CacheFactory::class,
        \Rcm\Service\CurrentSite::class
        => \Rcm\Factory\CurrentSiteFactory::class,
        \Rcm\Service\DomainRedirectService::class
        => \Rcm\Factory\ServiceDomainRedirectServiceFactory::class,
        \Rcm\Service\DomainService::class
        => \Rcm\Factory\ServiceDomainServiceFactory::class,
        \Rcm\Service\HtmlIncludes::class
        => \Rcm\Factory\RcmHtmlIncludesServiceFactory::class,
        \Rcm\Service\LayoutManager::class
        => \Rcm\Factory\LayoutManagerFactory::class,
        \Rcm\Service\LocaleService::class
        => \Rcm\Factory\ServiceLocaleServiceFactory::class,
        \Rcm\Service\Logger::class
        => \Rcm\Factory\LoggerFactory::class,
        \Rcm\Service\PluginManager::class
        => \Rcm\Service\PluginManagerFactory::class,
        \Rcm\Service\RcmUser::class
        => \Rcm\Factory\RcmUserFactory::class,
        \Rcm\Service\RedirectService::class
        => \Rcm\Factory\ServiceRedirectServiceFactory::class,
        \Rcm\Service\ResponseHandler::class
        => \Rcm\Factory\ResponseHandlerFactory::class,
        // WAS: "Rcm\Service\SessionMgr"
        \Rcm\Service\SessionManager::class
        => \Rcm\Factory\SessionManagerFactory::class,
        \Rcm\Service\SiteService::class
        => \Rcm\Factory\ServiceSiteServiceFactory::class,
        \Rcm\Service\ZendLogger::class
        => \Rcm\Factory\ZendLogFactory::class,
        \Rcm\Service\ZendLogWriter::class
        => \Rcm\Factory\ZendLogWriterFactory::class,
        // NOTE: this is state-full and should be cloned before use
        \Rcm\Validator\Page::class
        => \Rcm\Factory\PageValidatorFactory::class,
        // NOTE: this is state-full and should be cloned before use
        \Rcm\Validator\PageTemplate::class
        => \Rcm\Factory\PageTemplateFactory::class,
        // NOTE: this is state-full and should be cloned before use
        \Rcm\Validator\MainLayout::class
        => \Rcm\Factory\MainLayoutValidatorFactory::class,
    ],
    'invokables' => [
        // @todo This does NOT belong in this module, should be separated
        \Rcm\Service\DisplayCountService::class => \Rcm\Service\DisplayCountService::class,
        \Rcm\Block\Instance\InstanceConfigMerger::class => \Rcm\Block\Instance\InstanceConfigMerger::class
    ],
    'aliases' => [
        'rcmLogger' => \Rcm\Service\Logger::class,
        'Rcm\Service\SessionMgr' => \Rcm\Service\SessionManager::class,
        'Rcm\Acl\CmsPermissionsChecks' => \Rcm\Acl\CmsPermissionChecks::class,
    ]
];
