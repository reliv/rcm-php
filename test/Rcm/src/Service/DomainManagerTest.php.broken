<?php
/**
 * Unit Test for the Domain Manager Service
 *
 * This file contains the unit test for the Domain Manager Service
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
 * @link      http://github.com/reliv
 */

namespace RcmTest\Service;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Service\DomainManager;
use Zend\Cache\StorageFactory;

/**
 * Unit Test for the Domain Manager Service
 *
 * Unit Test for the Domain Manager Service
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class DomainManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Service\DomainManager */
    protected $domainManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockEntityRepo;

    /** @var \Zend\Cache\Storage\Adapter\Memory */
    protected $cache;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $mockEntityRepo = $this->getMockBuilder('\Rcm\Repository\Domain')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockEntityRepo = $mockEntityRepo;

        /** @var \Zend\Cache\Storage\Adapter\Memory $cache */
        $cache = StorageFactory::factory(
            array(
                'adapter' => array(
                    'name' => 'Memory',
                    'options' => array(),
                ),
                'plugins' => array(),
            )
        );

        $this->cache = $cache;

        $this->cache->flush();

        /** @var \Rcm\Repository\Domain $mockEntityRepo */
        $this->domainManager = new DomainManager(
            $mockEntityRepo,
            $this->cache
        );

    }

    /**
     * Test the constructor
     *
     * @return void
     *
     * @covers \Rcm\Service\DomainManager::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(
            '\Rcm\Service\DomainManager',
            $this->domainManager
        );
    }

    /**
     * Test Get Active Domain List with Info No Cache
     *
     * @return void
     *
     * @covers \Rcm\Service\DomainManager::getActiveDomainList
     */
    public function testGetActiveDomainList()
    {
        $domains = array(
            'local.reliv.com' => array(
                'domain' => 'local.reliv.com',
                'primaryDomain' => null,
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            ),
            'local.reliv.fr' => array(
                'domain' => 'local.reliv.fr',
                'primaryDomain' => null,
                'languageId' => 'fre',
                'siteId' => 3,
                'countryId' => 'FRA',
            ),
        );

        $this->mockEntityRepo->expects($this->once())
            ->method('getActiveDomainList')
            ->will($this->returnValue($domains));

        $expected = $domains;

        $result = $this->domainManager->getActiveDomainList();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Revision Info From Cache
     *
     * @return void
     *
     * @covers \Rcm\Service\DomainManager::getActiveDomainList
     */
    public function testGetActiveDomainListFromCache()
    {

        $domains = array(
            'local.reliv.com' => array(
                'domain' => 'reliv.com',
                'primaryDomain' => null,
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            ),
            'local.reliv.fr' => array(
                'domain' => 'local.reliv.fr',
                'primaryDomain' => null,
                'languageId' => 'fre',
                'siteId' => 3,
                'countryId' => 'FRA',
            ),
        );

        $cacheKey = 'rcm_active_domain_list';

        $expected = $domains;

        $this->cache->setItem($cacheKey, $expected);

        $result = $this->domainManager->getActiveDomainList();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Active Domain List from array set from db with No Cache.
     * Ensures that we are only calling the DB once to get the info.
     *
     * @return void
     *
     * @covers \Rcm\Service\DomainManager::getActiveDomainList
     */
    public function testGetActiveDomainListFromArraySetByDb()
    {
        $domains = array(
            'local.reliv.com' => array(
                'domain' => 'local.reliv.com',
                'primaryDomain' => null,
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            ),
            'local.reliv.fr' => array(
                'domain' => 'local.reliv.fr',
                'primaryDomain' => null,
                'languageId' => 'fre',
                'siteId' => 3,
                'countryId' => 'FRA',
            ),
        );

        $this->mockEntityRepo->expects($this->once())
            ->method('getActiveDomainList')
            ->will($this->returnValue($domains));

        $expected = $domains;

        $this->domainManager->getActiveDomainList();

        $result = $this->domainManager->getActiveDomainList();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Active Domain List from array set from Cache.  Ensures that we are
     * only calling the cache once for the info.
     *
     * @return void
     *
     * @covers \Rcm\Service\DomainManager::getActiveDomainList
     */
    public function testGetActiveDomainListFromArraySetByCache()
    {

        $domains = array(
            'local.reliv.com' => array(
                'domain' => 'reliv.com',
                'primaryDomain' => null,
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            ),
            'local.reliv.fr' => array(
                'domain' => 'local.reliv.fr',
                'primaryDomain' => null,
                'languageId' => 'fre',
                'siteId' => 3,
                'countryId' => 'FRA',
            ),
        );

        $mockCache = $this->getMockBuilder('\Zend\Cache\Storage\Adapter\Memory')
            ->disableOriginalConstructor()
            ->getMock();

        $mockCache->expects($this->once())
            ->method('hasItem')
            ->will($this->returnValue(true));

        $mockCache->expects($this->once())
            ->method('getItem')
            ->will($this->returnValue($domains));

        $this->mockEntityRepo->expects($this->never())
            ->method('getActiveDomainList');

        /** @var \Rcm\Repository\Domain $mockEntityRepo */
        $domainManager = new DomainManager(
            $this->mockEntityRepo,
            $mockCache
        );

        $domainManager->getActiveDomainList();
        $result = $domainManager->getActiveDomainList();

        $this->assertEquals($domains, $result);
    }

    /**
     * Test Get Domain Info from Db.
     *
     * @return void
     *
     * @covers \Rcm\Service\DomainManager::getDomainInfo
     */
    public function testGetActiveDomainInfoFromDb()
    {
        $domain = array(
            'domain' => 'reliv.com',
            'primaryDomain' => null,
            'languageId' => 'eng',
            'siteId' => 1,
            'countryId' => 'USA',
        );

        $this->mockEntityRepo->expects($this->once())
            ->method('getDomainInfo')
            ->will($this->returnValue($domain));

        $result = $this->domainManager->getDomainInfo('reliv.com');

        $this->assertEquals($domain, $result);
    }

    /**
     * Test Get Domain Info from Cache.
     *
     * @return void
     *
     * @covers \Rcm\Service\DomainManager::getDomainInfo
     */
    public function testGetActiveDomainInfoFromCache()
    {
        $domain = array(
            'domain' => 'reliv.com',
            'primaryDomain' => null,
            'languageId' => 'eng',
            'siteId' => 1,
            'countryId' => 'USA',
        );

        $cacheKey = 'rcm_domain_reliv.com';

        $expected = $domain;

        $this->cache->setItem($cacheKey, $expected);

        $this->mockEntityRepo->expects($this->never())
            ->method('getDomainInfo');

        $result = $this->domainManager->getDomainInfo('reliv.com');

        $this->assertEquals($domain, $result);
    }

    /**
     * Test Get Domain Info from memory set by Db.  This ensures that we are only
     * calling the DB once.
     *
     * @return void
     *
     * @covers \Rcm\Service\DomainManager::getDomainInfo
     */
    public function testGetActiveDomainInfoFromMemorySetByDb()
    {
        $domain = array(
            'domain' => 'reliv.com',
            'primaryDomain' => null,
            'languageId' => 'eng',
            'siteId' => 1,
            'countryId' => 'USA',
        );

        $mockCache = $this->getMockBuilder('\Zend\Cache\Storage\Adapter\Memory')
            ->disableOriginalConstructor()
            ->getMock();

        $mockCache->expects($this->once())
            ->method('hasItem')
            ->will($this->returnValue(false));

        $this->mockEntityRepo->expects($this->once())
            ->method('getDomainInfo')
            ->will($this->returnValue($domain));

        /** @var \Rcm\Repository\Domain $mockEntityRepo */
        $domainManager = new DomainManager(
            $this->mockEntityRepo,
            $mockCache
        );

        $domainManager->getDomainInfo('reliv.com');
        $result = $domainManager->getDomainInfo('reliv.com');

        $this->assertEquals($domain, $result);
    }

    /**
     * Test Get Domain Info from Memory set by cache.  This ensures that we are
     * only calling the cache once.
     *
     * @return void
     *
     * @covers \Rcm\Service\DomainManager::getDomainInfo
     */
    public function testGetActiveDomainInfoFromMemorySetByCache()
    {
        $domain = array(
            'domain' => 'reliv.com',
            'primaryDomain' => null,
            'languageId' => 'eng',
            'siteId' => 1,
            'countryId' => 'USA',
        );

        $mockCache = $this->getMockBuilder('\Zend\Cache\Storage\Adapter\Memory')
            ->disableOriginalConstructor()
            ->getMock();

        $mockCache->expects($this->once())
            ->method('hasItem')
            ->will($this->returnValue(true));

        $mockCache->expects($this->once())
            ->method('getItem')
            ->will($this->returnValue($domain));

        $this->mockEntityRepo->expects($this->never())
            ->method('getDomainInfo');

        /** @var \Rcm\Repository\Domain $mockEntityRepo */
        $domainManager = new DomainManager(
            $this->mockEntityRepo,
            $mockCache
        );

        $domainManager->getDomainInfo('reliv.com');
        $result = $domainManager->getDomainInfo('reliv.com');

        $this->assertEquals($domain, $result);
    }

    /**
     * Test Get Domain Info with previous call to get Active Domain List
     *
     * @return void
     *
     * @covers \Rcm\Service\DomainManager::getDomainInfo
     */
    public function testGetDomainInfoAfterActiveDomainList()
    {
        $domains = array(
            'local.reliv.com' => array(
                'domain' => 'local.reliv.com',
                'primaryDomain' => null,
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            ),
            'local.reliv.fr' => array(
                'domain' => 'local.reliv.fr',
                'primaryDomain' => null,
                'languageId' => 'fre',
                'siteId' => 3,
                'countryId' => 'FRA',
            ),
        );

        $this->mockEntityRepo->expects($this->once())
            ->method('getActiveDomainList')
            ->will($this->returnValue($domains));

        $expected = $domains['local.reliv.com'];

        $this->domainManager->getActiveDomainList();
        $result = $this->domainManager->getDomainInfo('local.reliv.com');

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Domain Info with previous call to get Active Domain List
     *
     * @return void
     *
     * @covers \Rcm\Service\DomainManager::getDomainInfo
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testGetDomainInfoException()
    {
        $this->domainManager->getDomainInfo(null);
    }
}










