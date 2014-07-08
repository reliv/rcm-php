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
                'domain' => 'reliv.com',
                'primaryDomain' => null,
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            )
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
            )
        );

        $cacheKey = 'rcm_active_domain_list';

        $expected = $domains;

        $this->cache->setItem($cacheKey, $expected);

        $result = $this->domainManager->getActiveDomainList();

        $this->assertEquals($expected, $result);
    }
}










