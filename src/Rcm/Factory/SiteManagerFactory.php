<?php
/**
 * Factory for the Site Manager
 *
 * This file contains the factory needed to generate a Site Manager.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace Rcm\Factory;

use Rcm\Service\ContainerManager;
use Rcm\Service\PageManager;
use Rcm\Service\SiteManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Http\PhpEnvironment\Request as PhpEnvironmentRequest;

/**
 * Service Factory for the Site Manager
 *
 * Factory for the Site Manager.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class SiteManagerFactory implements FactoryInterface
{
    protected $cache;
    protected $request;
    protected $config;

    /** @var  \Rcm\Service\SiteManager */
    protected $siteManager;

    /** @var \Rcm\Service\PluginManager */
    protected $pluginManager;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    protected $entityManager;

    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return SiteManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $this->entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $siteRepo = $this->entityManager->getRepository('\Rcm\Entity\Site');

        $this->siteManager = new SiteManager($siteRepo);

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get('Rcm\Service\CurrentSite');
        $this->siteManager->setCurrentSiteId($currentSite->getSiteId());

        /*
         * Get Needed Dependencies
         */
        $this->config = $serviceLocator->get('config');

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $this->request = $serviceLocator->get('request');


        $this->pluginManager = $this->serviceLocator->get('Rcm\Service\PluginManager');
        $this->siteManager->setPluginManager($this->pluginManager);

        $this->cache = $serviceLocator->get('Rcm\Service\Cache');
        $this->siteManager->setCache($this->cache);

        $this->siteManager->setPageManager($this->constructPageManager());
        $this->siteManager->setContainerManager($this->constructContainerManager());

        return $this->siteManager;
    }

    private function constructPageManager()
    {
        /** @var \Doctrine\ORM\EntityRepository $repository */
        $repository = $this->entityManager->getRepository('\Rcm\Entity\Page');

        /** @var \Rcm\Validator\MainLayout $layoutValidator */
        $layoutValidator = $this->serviceLocator->get('Rcm\Validator\MainLayout');

        return new PageManager(
            $this->pluginManager,
            $repository,
            $this->cache,
            $this->siteManager,
            $layoutValidator
        );
    }

    private function constructContainerManager()
    {
        /** @var \Doctrine\ORM\EntityRepository $repository */
        $repository = $this->entityManager->getRepository('\Rcm\Entity\Container');

        return new ContainerManager(
            $this->pluginManager,
            $repository,
            $this->cache,
            $this->siteManager
        );
    }

    /**
     * Get the current Domain from Request
     *
     * @return string|null
     */
    private function getCurrentDomain()
    {
        if (!$this->request instanceof PhpEnvironmentRequest) {
            return null;
        }

        $serverParams = $this->request->getServer();

        return $serverParams->get('HTTP_HOST');
    }
}
