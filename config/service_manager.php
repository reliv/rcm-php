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

        \Rcm\Acl\ResourceName::class
        => \Rcm\Acl\ResourceNameRcmFactory::class,

        \Rcm\Acl\ResourceProvider::class
        => \Rcm\Factory\AclResourceProviderFactory::class,

        /**
         * API =====================================
         */
        \Rcm\Api\Repository\Container\FindContainers::class
        => \Rcm\Api\Repository\Container\FindContainersFactory::class,

        \Rcm\Api\Repository\Country\FindCountryByIso2::class
        => \Rcm\Api\Repository\Country\FindCountryByIso2Factory::class,

        \Rcm\Api\Repository\Country\FindCountryByIso3::class
        => \Rcm\Api\Repository\Country\FindCountryByIso3Factory::class,

        \Rcm\Api\Repository\Domain\FindDomainByName::class
        => \Rcm\Api\Repository\Domain\FindDomainByNameFactory::class,

        \Rcm\Api\Repository\Domain\FindDomainsLike::class
        => \Rcm\Api\Repository\Domain\FindDomainsLikeFactory::class,

        \Rcm\Api\Repository\Language\FindLanguageByIso6391::class
        => \Rcm\Api\Repository\Language\FindLanguageByIso6391Factory::class,

        \Rcm\Api\Repository\Page\FindPage::class
        => \Rcm\Api\Repository\Page\FindPageFactory::class,

        \Rcm\Api\Repository\Page\FindPages::class
        => \Rcm\Api\Repository\Page\FindPagesFactory::class,

        \Rcm\Api\Repository\Page\FindPagesByType::class
        => \Rcm\Api\Repository\Page\FindPagesByTypeFactory::class,

        \Rcm\Api\Repository\Page\PageExists::class
        => \Rcm\Api\Repository\Page\PageExistsFactory::class,

        \Rcm\Api\Repository\Page\SavePage::class
        => \Rcm\Api\Repository\Page\SavePageFactory::class,

        \Rcm\Api\Repository\Redirect\CreateRedirect::class
        => \Rcm\Api\Repository\Redirect\CreateRedirectFactory::class,

        \Rcm\Api\Repository\Redirect\FindAllSiteRedirects::class
        => \Rcm\Api\Repository\Redirect\FindAllSiteRedirectsFactory::class,

        \Rcm\Api\Repository\Redirect\FindGlobalRedirects::class
        => \Rcm\Api\Repository\Redirect\FindGlobalRedirectsFactory::class,

        \Rcm\Api\Repository\Redirect\FindRedirect::class
        => \Rcm\Api\Repository\Redirect\FindRedirectFactory::class,

        \Rcm\Api\Repository\Redirect\FindRedirects::class
        => \Rcm\Api\Repository\Redirect\FindRedirectsFactory::class,

        \Rcm\Api\Repository\Redirect\FindSiteRedirects::class
        => \Rcm\Api\Repository\Redirect\FindSiteRedirectsFactory::class,

        \Rcm\Api\Repository\Redirect\RemoveRedirect::class
        => \Rcm\Api\Repository\Redirect\RemoveRedirectFactory::class,

        \Rcm\Api\Repository\Redirect\UpdateRedirect::class
        => \Rcm\Api\Repository\Redirect\UpdateRedirectFactory::class,

        \Rcm\Api\Repository\Setting\FindSettingByName::class
        => \Rcm\Api\Repository\Setting\FindSettingByNameFactory::class,

        \Rcm\Api\Repository\Site\CopySite::class
        => \Rcm\Api\Repository\Site\CopySiteFactory::class,

        \Rcm\Api\Repository\Site\CreateSite::class
        => \Rcm\Api\Repository\Site\CreateSiteFactory::class,

        \Rcm\Api\Repository\Site\FindActiveSites::class
        => \Rcm\Api\Repository\Site\FindActiveSitesFactory::class,

        \Rcm\Api\Repository\Site\FindAllSites::class
        => \Rcm\Api\Repository\Site\FindAllSitesFactory::class,

        \Rcm\Api\Repository\Site\FindSite::class
        => \Rcm\Api\Repository\Site\FindSiteFactory::class,

        \Rcm\Api\GetSiteByRequest::class
        => \Rcm\Api\GetSiteByRequestFactory::class,

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
        => \Rcm\EventListener\EventWrapperFactory::class,
        \Rcm\EventListener\RouteListener::class
        => \Rcm\EventListener\RouteListenerFactory::class,
        \Rcm\EventListener\DispatchListener::class
        => \Rcm\EventListener\DispatchListenerFactory::class,
        \Rcm\EventListener\EventFinishListener::class
        => \Rcm\EventListener\EventFinishListenerFactory::class,
        \Rcm\EventListener\ViewEventListener::class
        => \Rcm\EventListener\ViewEventListenerFactory::class,
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

        /* Zend Over-ride */
        \Rcm\Zend\Mvc\ResponseSender\HttpResponseSender::class
        => \Rcm\Zend\Mvc\ResponseSender\HttpResponseSenderFactory::class
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
