<?php
/**
 * Unit Test for the Plugin Manager Service
 *
 * This file contains the unit test for the Plugin Manager Service
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

use Rcm\Service\ContainerManager;
use \Zend\Cache\StorageFactory;

/**
 * Unit Test for the Plugin Manager Service
 *
 * Unit Test for the Plugin Manager Service
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class ContainerManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Service\ContainerManager */
    protected $containerManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPluginManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockEntityRepo;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockSiteManager;

    /** @var \Zend\Cache\Storage\Adapter\Memory */
    protected $cache;

    protected $siteId=1;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $mockPluginManager = $this
            ->getMockBuilder('\Rcm\Service\PluginManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager = $this
            ->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPluginManager = $mockPluginManager;
        $this->mockSiteManager = $mockSiteManager;

        $mockEntityRepo = $this->getMockBuilder('\Rcm\Repository\Container')
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

        /** @var \Rcm\Service\PluginManager $mockPluginManager */
        /** @var \Rcm\Repository\Container  $mockEntityRepo */
        /** @var \Rcm\Service\SiteManager   $mockSiteManager */
        $this->containerManager = new ContainerManager(
            $mockPluginManager,
            $mockEntityRepo,
            $this->cache,
            $mockSiteManager
        );

    }

    /**
     * Test the constructor
     *
     * @return void
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(
            '\Rcm\Service\ContainerManager',
            $this->containerManager
        );
    }

    /**
     * Test Get Revision Info No Cache
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getRevisionInfo
     * @covers \Rcm\Service\ContainerAbstract::getRevisionInfo
     */
    public function testGetRevisionInfo()
    {
        $pageName = 'my-test';
        $type = 'z';
        $revisionId = 100;

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockEntityRepo->expects($this->never())
            ->method('getPublishedRevisionId');

        $this->mockEntityRepo->expects($this->never())
            ->method('getStagedRevisionId');

        $pageInfo['revision']['pluginInstances'] = array(
            1 => array(
                'instance' => array (
                    'pluginInstanceId' => 1,
                    'canCache' => true,
                ),
            ),
            2 => array(
                'instance' => array (
                    'pluginInstanceId' => 2,
                    'canCache' => true,
                ),
            ),
        );

        $this->mockEntityRepo->expects($this->once())
            ->method('getRevisionDbInfo')
            ->with(
                $this->equalTo($this->siteId),
                $this->equalTo($pageName),
                $this->equalTo($revisionId),
                $this->equalTo($type)
            )
            ->will($this->returnValue($pageInfo));

        $map = array(
            array(1, '<p>Plugin One</p>'),
            array(2, '<p>Plugin Two</p>')
        );

        $this->mockPluginManager->expects($this->exactly(2))
            ->method('getPluginByInstanceId')
            ->will($this->returnValueMap($map));

        $expected = $pageInfo;
        $expected['revision']['pluginInstances'][1]['instance']['renderedData']
            = '<p>Plugin One</p>';
        $expected['revision']['pluginInstances'][2]['instance']['renderedData']
            = '<p>Plugin Two</p>';

        $result = $this->containerManager->getRevisionInfo(
            $pageName,
            $revisionId,
            $type,
            false
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Revision Info From Cache
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getRevisionInfo
     * @covers \Rcm\Service\ContainerAbstract::getRevisionInfo
     */
    public function testGetRevisionInfoFromCache()
    {
        $pageName = 'my-test';
        $type = 'z';
        $revisionId = 100;
        $cacheKey = 'Rcm\Service\ContainerManager_'
            .$this->siteId.'_'
            .$type.'_'
            .$pageName.'_'
            .$revisionId;

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockEntityRepo->expects($this->never())
            ->method('getPublishedRevisionId');

        $this->mockEntityRepo->expects($this->never())
            ->method('getStagedRevisionId');

        $pageInfo['revision']['pluginInstances'] = array(
            1 => array(
                'instance' => array (
                    'pluginInstanceId' => 1,
                    'canCache' => true,
                ),
            ),
            2 => array(
                'instance' => array (
                    'pluginInstanceId' => 2,
                    'canCache' => true,
                ),
            ),
        );

        $this->mockEntityRepo->expects($this->never())
            ->method('getRevisionDbInfo');

        $this->mockPluginManager->expects($this->never())
            ->method('getPluginByInstanceId');

        $expected = $pageInfo;
        $expected['revision']['pluginInstances'][1]['instance']['renderedData']
            = '<p>Plugin One</p>';
        $expected['revision']['pluginInstances'][2]['instance']['renderedData']
            = '<p>Plugin Two</p>';

        $this->cache->setItem($cacheKey, $expected);

        $result = $this->containerManager->getRevisionInfo(
            $pageName,
            $revisionId,
            $type,
            false
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Revision Throws exception on invalid site
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getRevisionInfo
     * @covers \Rcm\Service\ContainerAbstract::getRevisionInfo
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testGetRevisionInfoInvalidSiteId()
    {
        $pageName = 'my-test';
        $type = 'z';
        $revisionId = 100;

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(false));

        $this->containerManager->getRevisionInfo(
            $pageName,
            $revisionId,
            $type,
            false,
            22
        );
    }

    /**
     * Test Get Staged Revision Info
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getRevisionInfo
     * @covers \Rcm\Service\ContainerAbstract::getRevisionInfo
     */
    public function testGetStagedRevisionInfo()
    {
        $pageName = 'my-test';
        $type = 'z';
        $revisionId = 100;

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockEntityRepo->expects($this->never())
            ->method('getPublishedRevisionId');

        $this->mockEntityRepo->expects($this->once())
            ->method('getStagedRevisionId')
            ->will($this->returnValue($revisionId));

        $pageInfo['revision']['pluginInstances'] = array(
            1 => array(
                'instance' => array (
                    'pluginInstanceId' => 1,
                    'canCache' => true,
                ),
            ),
            2 => array(
                'instance' => array (
                    'pluginInstanceId' => 2,
                    'canCache' => true,
                ),
            ),
        );

        $this->mockEntityRepo->expects($this->once())
            ->method('getRevisionDbInfo')
            ->with(
                $this->equalTo($this->siteId),
                $this->equalTo($pageName),
                $this->equalTo($revisionId),
                $this->equalTo($type)
            )
            ->will($this->returnValue($pageInfo));

        $map = array(
            array(1, '<p>Plugin One</p>'),
            array(2, '<p>Plugin Two</p>')
        );

        $this->mockPluginManager->expects($this->exactly(2))
            ->method('getPluginByInstanceId')
            ->will($this->returnValueMap($map));

        $expected = $pageInfo;
        $expected['revision']['pluginInstances'][1]['instance']['renderedData']
            = '<p>Plugin One</p>';
        $expected['revision']['pluginInstances'][2]['instance']['renderedData']
            = '<p>Plugin Two</p>';

        $result = $this->containerManager->getRevisionInfo(
            $pageName,
            null,
            $type,
            true
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Published Revision Info
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getRevisionInfo
     * @covers \Rcm\Service\ContainerAbstract::getRevisionInfo
     */
    public function testGetPublishedRevisionInfo()
    {
        $pageName = 'my-test';
        $type = 'z';
        $revisionId = 100;

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockEntityRepo->expects($this->once())
            ->method('getPublishedRevisionId')
            ->will($this->returnValue($revisionId));

        $this->mockEntityRepo->expects($this->never())
            ->method('getStagedRevisionId');

        $pageInfo['revision']['pluginInstances'] = array(
            1 => array(
                'instance' => array (
                    'pluginInstanceId' => 1,
                    'canCache' => true,
                ),
            ),
            2 => array(
                'instance' => array (
                    'pluginInstanceId' => 2,
                    'canCache' => true,
                ),
            ),
        );

        $this->mockEntityRepo->expects($this->once())
            ->method('getRevisionDbInfo')
            ->with(
                $this->equalTo($this->siteId),
                $this->equalTo($pageName),
                $this->equalTo($revisionId),
                $this->equalTo($type)
            )
            ->will($this->returnValue($pageInfo));

        $map = array(
            array(1, '<p>Plugin One</p>'),
            array(2, '<p>Plugin Two</p>')
        );

        $this->mockPluginManager->expects($this->exactly(2))
            ->method('getPluginByInstanceId')
            ->will($this->returnValueMap($map));

        $expected = $pageInfo;
        $expected['revision']['pluginInstances'][1]['instance']['renderedData']
            = '<p>Plugin One</p>';
        $expected['revision']['pluginInstances'][2]['instance']['renderedData']
            = '<p>Plugin Two</p>';

        $result = $this->containerManager->getRevisionInfo(
            $pageName,
            null,
            $type,
            false
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Revision Info Failure
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getRevisionInfo
     * @covers \Rcm\Service\ContainerAbstract::getRevisionInfo
     * @expectedException \Rcm\Exception\ContainerNotFoundException
     */
    public function testGetRevisionInfoException()
    {
        $pageName = 'my-test';
        $type = 'z';

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockEntityRepo->expects($this->once())
            ->method('getPublishedRevisionId')
            ->will($this->returnValue(null));

        $this->mockEntityRepo->expects($this->once())
            ->method('getStagedRevisionId')
            ->will($this->returnValue(null));

        $this->mockEntityRepo->expects($this->never())
            ->method('getRevisionDbInfo');

        $this->mockPluginManager->expects($this->never())
            ->method('getPluginByInstanceId');

        $this->containerManager->getRevisionInfo(
            $pageName,
            null,
            $type,
            true
        );
    }

    /**
     * Test Get Published Revision Id Info No Cache
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getPublishedRevisionId
     * @covers \Rcm\Service\ContainerAbstract::getPublishedRevisionId
     */
    public function testGetPublishedRevisionId()
    {
        $expected = 100;

        $pageName = 'my-test';
        $type = 'z';

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockEntityRepo->expects($this->once())
            ->method('getPublishedRevisionId')
            ->with(
                $this->equalTo($this->siteId),
                $this->equalTo($pageName),
                $this->equalTo($type)
            )
            ->will($this->returnValue($expected));

        $result = $this->containerManager->getPublishedRevisionId(
            $pageName,
            $type
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Published Revision Id Info From Cache
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getPublishedRevisionId
     * @covers \Rcm\Service\ContainerAbstract::getPublishedRevisionId
     */
    public function testGetPublishedRevisionIdFromCache()
    {
        $expected = 100;

        $pageName = 'my-test';
        $type = 'z';

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $cacheKey = 'Rcm\Service\ContainerManager_'
            .$this->siteId.'_'
            .$type.'_'
            .$pageName
            .'_currentRevision';

        $this->cache->setItem($cacheKey, $expected);

        $this->mockEntityRepo->expects($this->never())
            ->method('getStagedRevisionId');

        $result = $this->containerManager->getPublishedRevisionId(
            $pageName,
            $type
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Published Revision Id Info Throws exception with invalid site
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getPublishedRevisionId
     * @covers \Rcm\Service\ContainerAbstract::getPublishedRevisionId
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testGetPublishedRevisionIdWithInvalidSiteId()
    {
        $pageName = 'my-test';
        $type = 'z';

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(false));

        $this->containerManager->getPublishedRevisionId(
            $pageName,
            $type,
            22
        );
    }

    /**
     * Test Get Staged Revision Id Info No Cache
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getStagedRevisionId
     * @covers \Rcm\Service\ContainerAbstract::getStagedRevisionId
     */
    public function testGetStagedRevisionId()
    {
        $expected = 100;

        $pageName = 'my-test';
        $type = 'z';

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockEntityRepo->expects($this->once())
            ->method('getStagedRevisionId')
            ->with(
                $this->equalTo($this->siteId),
                $this->equalTo($pageName),
                $this->equalTo($type)
            )
            ->will($this->returnValue($expected));

        $result = $this->containerManager->getStagedRevisionId(
            $pageName,
            $type
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Staged Revision Id Info From Cache
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getStagedRevisionId
     * @covers \Rcm\Service\ContainerAbstract::getStagedRevisionId
     */
    public function testGetStagedRevisionIdFromCache()
    {
        $expected = 100;

        $pageName = 'my-test';
        $type = 'z';

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $cacheKey = 'Rcm\Service\ContainerManager_'
            .$this->siteId.'_'
            .$type.'_'
            .$pageName
            .'_stagedRevision';

        $this->cache->setItem($cacheKey, $expected);

        $this->mockEntityRepo->expects($this->never())
            ->method('getStagedRevisionId');

        $result = $this->containerManager->getStagedRevisionId(
            $pageName,
            $type
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Staged Revision Id Throws Exception With Invalid Site
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getStagedRevisionId
     * @covers \Rcm\Service\ContainerAbstract::getStagedRevisionId
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testGetStagedRevisionIdWithInvalidSite()
    {
        $pageName = 'my-test';
        $type = 'z';

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(false));

        $this->containerManager->getStagedRevisionId(
            $pageName,
            $type,
            22
        );
    }

    /**
     * Test Get Revision Db Info No Cache No Memory
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getRevisionDbInfo
     * @covers \Rcm\Service\ContainerAbstract::getRevisionDbInfo
     */
    public function testGetRevisionDbInfo()
    {
        $expected = array('instanceId' => 1);

        $pageName = 'my-test';
        $revisionId = 1;
        $type = 'z';

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockEntityRepo->expects($this->once())
            ->method('getRevisionDbInfo')
            ->with(
                $this->equalTo($this->siteId),
                $this->equalTo($pageName),
                $this->equalTo($revisionId),
                $this->equalTo($type)
            )
            ->will($this->returnValue($expected));

        $result = $this->containerManager->getRevisionDbInfo(
            $pageName,
            $revisionId,
            $type
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Revision Db Info from Memory
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getRevisionDbInfo
     * @covers \Rcm\Service\ContainerAbstract::getRevisionDbInfo
     */
    public function testGetRevisionDbInfoFromMemory()
    {
        $expected = array('instanceId' => 1);

        $pageName = 'my-test';
        $revisionId = 1;
        $type = 'z';

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $storedContainers['data'][$this->siteId][$type][$pageName][$revisionId]
            = $expected;

        $reflectedClass = new \ReflectionClass($this->containerManager);
        $reflectedProp = $reflectedClass->getProperty('storedContainers');
        $reflectedProp->setAccessible(true);
        $reflectedProp->setValue($this->containerManager, $storedContainers);

        $this->mockEntityRepo->expects($this->never())
            ->method('getRevisionDbInfo');

        $result = $this->containerManager->getRevisionDbInfo(
            $pageName,
            $revisionId,
            $type
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Revision Db Info from Memory
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getRevisionDbInfo
     * @covers \Rcm\Service\ContainerAbstract::getRevisionDbInfo
     */
    public function testGetRevisionDbInfoFromCache()
    {
        $expected = array('instanceId' => 1);

        $pageName = 'my-test';
        $revisionId = 1;
        $type = 'z';

        $this->mockSiteManager->expects($this->any())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(1));

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $cacheKey = 'Rcm\Service\ContainerManager_data_'
            .$this->siteId.'_'
            .$type.'_'
            .$pageName.'_'
            .$revisionId;

        $this->cache->setItem($cacheKey, $expected);

        $storedContainers = null;

        $reflectedClass = new \ReflectionClass($this->containerManager);
        $reflectedProp = $reflectedClass->getProperty('storedContainers');
        $reflectedProp->setAccessible(true);
        $reflectedProp->setValue($this->containerManager, $storedContainers);

        $this->mockEntityRepo->expects($this->never())
            ->method('getRevisionDbInfo');

        $result = $this->containerManager->getRevisionDbInfo(
            $pageName,
            $revisionId,
            $type
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Get Revision Db Info Throws Exception with invalid site
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getRevisionDbInfo
     * @covers \Rcm\Service\ContainerAbstract::getRevisionDbInfo
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testGetRevisionDbInfoWithInvalidSite()
    {
        $pageName = 'my-test';
        $revisionId = 1;
        $type = 'z';

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(false));

        $this->containerManager->getRevisionDbInfo(
            $pageName,
            $revisionId,
            $type,
            22
        );
    }

    /**
     * Test Can Cache Revision returns True
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::getPluginRenderedInstances
     * @covers \Rcm\Service\ContainerAbstract::getPluginRenderedInstances
     */
    public function testGetPluginRenderedInstances()
    {
        $revisionData['pluginInstances'][1]['instance']['pluginInstanceId'] = 1;
        $revisionData['pluginInstances'][2]['instance']['pluginInstanceId'] = 2;

        $expected = $revisionData;
        $expected['pluginInstances'][1]['instance']['renderedData']
            = '<p>Plugin One</p>';
        $expected['pluginInstances'][2]['instance']['renderedData']
            = '<p>Plugin Two</p>';

        $map = array(
            array(1, '<p>Plugin One</p>'),
            array(2, '<p>Plugin Two</p>')
        );

        $this->mockPluginManager->expects($this->exactly(2))
            ->method('getPluginByInstanceId')
            ->will($this->returnValueMap($map));

        $reflectedService = new \ReflectionClass($this->containerManager);

        $reflectedMethod
            = $reflectedService->getMethod('getPluginRenderedInstances');

        $reflectedMethod->setAccessible(true);

        $reflectedMethod->invokeArgs(
            $this->containerManager,
            array(&$revisionData)
        );

        $this->assertEquals($expected, $revisionData);

    }

    /**
     * Test Can Cache Revision returns True
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::canCacheRevision
     * @covers \Rcm\Service\ContainerAbstract::canCacheRevision
     */
    public function testCanCacheRevision()
    {
        $revisionData['pluginInstances'][0]['instance']['canCache'] = true;

        $reflectedService = new \ReflectionClass($this->containerManager);
        $reflectedMethod = $reflectedService->getMethod('canCacheRevision');
        $reflectedMethod->setAccessible(true);

        $result = $reflectedMethod->invokeArgs(
            $this->containerManager,
            array(&$revisionData)
        );

        $this->assertTrue($result);
    }

    /**
     * Test Can Cache Revision returns False
     *
     * @return void
     *
     * @covers \Rcm\Service\ContainerManager::canCacheRevision
     * @covers \Rcm\Service\ContainerAbstract::canCacheRevision
     */
    public function testCanCacheRevisionReturnsFalse()
    {
        $revisionData['pluginInstances'][0]['instance']['canCache'] = false;

        $reflectedService = new \ReflectionClass($this->containerManager);
        $reflectedMethod = $reflectedService->getMethod('canCacheRevision');
        $reflectedMethod->setAccessible(true);

        $result = $reflectedMethod->invokeArgs(
            $this->containerManager,
            array(&$revisionData)
        );

        $this->assertFalse($result);
    }
}










