<?php

namespace RcmTest\Acl;

use Rcm\Acl\ResourceNameRcm;
use Rcm\Acl\ResourceProvider;
use Rcm\Entity\Domain;
use Rcm\Entity\Page;
use Rcm\Entity\Site;



/**
 * Unit Test for the ResourceProviderTest
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class ResourceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var
     */
    protected $config;

    /**
     * Setup config for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->config = $this->getConfig();
    }

    /**
     * Get Main Config Array
     *
     * @return array
     */
    protected function getConfig()
    {
        return [
            'sites' => [
                'resourceId' => \Rcm\Acl\ResourceName::RESOURCE_SITES,
                'parentResourceId' => null,
                'privileges' => [
                    'read',
                    'edit',
                    'create',
                    'delete',
                    'theme',
                    'admin',
                ],
                'name' => 'sites',
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
                ],
                'name' => 'pages',
                'description' => 'Global resource for pages',
            ],
        ];
    }

    /**
     * Get resource provider with mocks
     *
     * @param bool $skipSite Skip Site Mock setup
     *
     * @return ResourceProvider
     */
    protected function getProviderWithMocks($skipSite = false)
    {
        $mockReturn = [];

        $userId = 'user123';

        $currdomain = new Domain($userId);
        $currdomain->setDomainName('curr.reliv.com');

        $currpage = new Page($userId);
        $currpage->setName('curr-page');

        $currsite = new Site($userId);
        $currsite->setDomain($currdomain);
        $currsite->setSiteId(1);
        $currsite->addPage($currpage);

        if (!$skipSite) {
            $domain = new Domain($userId);
            $domain->setDomainName('test.reliv.com');

            $page = new Page($userId);
            $page->setName('test-page');

            $site = new Site($userId);
            $site->setDomain($domain);
            $site->setSiteId(10);
            $site->addPage($page);
            $mockReturn[] = $site;
        }

        /** @var \Rcm\Repository\Site $mockSiteRepo */
        /** @var \Rcm\Service\PluginManager $mockPluginManager */
        return new ResourceProvider(
            $this->config,
            $currsite,
            new ResourceNameRcm()
        );
    }

    /**
     * Test Get Resources Method With Sites And Pages
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResources
     * @covers \Rcm\Acl\ResourceProvider::getSiteResources
     * @covers \Rcm\Acl\ResourceProvider::getPageResources
     */
    public function testGetResourcesWithSites()
    {
        $siteExpected = [
            'sites.1.pages.n.curr-page' =>
                [
                    'resourceId' => 'sites.1.pages.n.curr-page',
                    'parentResourceId' => 'sites.1.pages',
                    'privileges' =>
                        [
                            0 => 'read',
                            1 => 'edit',
                            2 => 'create',
                            3 => 'delete',
                            4 => 'copy',
                            5 => 'approve',
                            6 => 'layout',
                        ],
                        'name' => 'curr.reliv.com - pages - curr-page',
                        'description' => 'Global resource for pages',
                ],
                'sites.1' =>
                [
                    'resourceId' => 'sites.1',
                    'parentResourceId' => 'sites',
                    'privileges' =>
                        [
                            0 => 'read',
                            1 => 'edit',
                            2 => 'create',
                            3 => 'delete',
                            4 => 'theme',
                            5 => 'admin',
                        ],
                        'name' => 'curr.reliv.com',
                        'description' => 'Global resource for sites',
                ],
                'sites.1.pages' =>
                [
                    'resourceId' => 'sites.1.pages',
                    'parentResourceId' => 'sites.1',
                    'privileges' =>
                        [
                            0 => 'read',
                            1 => 'edit',
                            2 => 'create',
                            3 => 'delete',
                            4 => 'copy',
                            5 => 'approve',
                            6 => 'layout',
                        ],
                        'name' => 'curr.reliv.com - pages',
                        'description' => 'Global resource for pages',
                ],
                'sites' =>
                [
                    'resourceId' => 'sites',
                    'parentResourceId' => null,
                    'privileges' =>
                        [
                            0 => 'read',
                            1 => 'edit',
                            2 => 'create',
                            3 => 'delete',
                            4 => 'theme',
                            5 => 'admin',
                        ],
                        'name' => 'sites',
                        'description' => 'Global resource for sites',
                ],
                'pages' =>
                [
                    'resourceId' => 'pages',
                    'parentResourceId' => null,
                    'privileges' =>
                        [
                            0 => 'read',
                            1 => 'edit',
                            2 => 'create',
                            3 => 'delete',
                            4 => 'copy',
                            5 => 'approve',
                            6 => 'layout',
                        ],
                        'name' => 'pages',
                        'description' => 'Global resource for pages',
                ],
        ];

        $expected = array_merge($siteExpected, $this->config);

        $resourceProvider = $this->getProviderWithMocks();

        $return = $resourceProvider->getResources();

        foreach ($expected as $key => $expect) {
            $this->assertTrue(array_key_exists($key, $return));
        }
    }

    /**
     * Test Get Resource with resource defined in config
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResource
     */
    public function testGetResourceInConfig()
    {
        $resourceProvider = $this->getProviderWithMocks(true);

        $sitesResource = $resourceProvider->getResource('sites');
        $pagesResource = $resourceProvider->getResource('pages');

        $this->assertEquals($this->config['sites'], $sitesResource);
        $this->assertEquals($this->config['pages'], $pagesResource);
    }

    /**
     * Test Get Resource using dynamic site
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResource
     * @covers \Rcm\Acl\ResourceProvider::dynamicResourceMapper
     * @covers \Rcm\Acl\ResourceProvider::siteResourceMapper
     */
    public function testGetResourceForSite()
    {
        $expected = [
            'resourceId' => 'sites.10',
            'parentResourceId' => 'sites',
            'privileges' => [
                'read',
                'edit',
                'create',
                'delete',
                'theme',
                'admin',
            ],
            'name' => 'sites',
            'description' => 'Global resource for sites',
        ];

        $resourceProvider = $this->getProviderWithMocks();

        $siteResource = $resourceProvider->getResource('sites.10');

        $this->assertEquals($expected, $siteResource);
    }

    /**
     * Test Get Resource using dynamic site
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResource
     * @covers \Rcm\Acl\ResourceProvider::dynamicResourceMapper
     * @covers \Rcm\Acl\ResourceProvider::pageResourceMapper
     */
    public function testGetResourceForPage()
    {
        $expected = [
            'resourceId' => 'sites.10.pages.n.test-page',
            'parentResourceId' => 'sites.10.pages',
            'privileges' => [
                'read',
                'edit',
                'create',
                'delete',
                'copy',
                'approve',
                'layout',
            ],
            'name' => 'pages',
            'description' => 'Global resource for pages',
        ];

        $resourceProvider = $this->getProviderWithMocks();

        $siteResource = $resourceProvider->getResource(
            'sites.10.pages.n.test-page'
        );

        $this->assertEquals($expected, $siteResource);
    }

    /**
     * Test Get Resource Resource Not Found
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResource
     * @covers \Rcm\Acl\ResourceProvider::dynamicResourceMapper
     * @covers \Rcm\Acl\ResourceProvider::siteResourceMapper
     * @covers \Rcm\Acl\ResourceProvider::pageResourceMapper
     */
    public function testGetResourceNoMatch()
    {
        $resourceProvider = $this->getProviderWithMocks();

        $siteResource = $resourceProvider->getResource('Does.Not.Match');

        $this->assertNull($siteResource);
    }

    /**
     * Test Set Provider Id
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::setProviderId
     */
    public function testSetProviderId()
    {
        $expected = \Rcm\Acl\ResourceProvider::class;

        $resourceProvider = $this->getProviderWithMocks();

        $reflectedClass = new \ReflectionClass(
            get_class($resourceProvider)
        );

        $property = $reflectedClass->getProperty('providerId');
        $property->setAccessible(true);

        $resourceProvider->setProviderId('Does.Not.Match');

        $providerId = $property->getValue($resourceProvider);

        $this->assertEquals($expected, $providerId);
    }
}
