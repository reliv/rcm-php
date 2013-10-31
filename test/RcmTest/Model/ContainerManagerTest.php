<?php

use \Rcm\Tests\Base\DoctrineTestCase;
use \Rcm\Model\ContainerManager;

class ContainerManagerTest extends DoctrineTestCase
{
    /** @var  \Rcm\Model\ContainerManager */
    protected $containerManager;

    public function setUp()
    {
        parent::setUp();

        /** @var \Zend\ServiceManager\ServiceManager $sm */
        $sm = Bootstrap::getServiceManager();

        $mockPluginManager = $this->getMockBuilder('\Rcm\Model\PluginManager2');
        $mockPluginManager->disableOriginalConstructor();

        /** @var Zend\Cache\Storage\StorageInterface $cache */
        $cache = $sm->get('rcmCache');

        $cache->clearByNamespace('RcmCache');


        $this->containerManager = new ContainerManager(
            $this->entityManager,
            $mockPluginManager->getMock(),
            $cache
        );
    }

    private function setupContainerEntity(){
        $pluginInstance = new \Rcm\Entity\PluginInstance();
        $pluginInstance->setInstanceId(1);
        $pluginInstance->setDisplayName('Rss Feed Reader');
        $pluginInstance->setPlugin('RcmRssFeed');
        $this->entityManager->persist($pluginInstance);

        $containerPlugin = new \Rcm\Entity\ContainerPlugin();
        $containerPlugin->setContainer('top');
        $containerPlugin->setContainerPluginId(1);
        $containerPlugin->setRenderOrderNumber(0);
        $containerPlugin->setInstance($pluginInstance);
        $this->entityManager->persist($containerPlugin);

        $pluginInstanceTwo = new \Rcm\Entity\PluginInstance();
        $pluginInstanceTwo->setInstanceId(2);
        $pluginInstanceTwo->setDisplayName('Rss Feed Reader');
        $pluginInstanceTwo->setPlugin('RcmRssFeed');
        $this->entityManager->persist($pluginInstanceTwo);

        $containerPluginTwo = new \Rcm\Entity\ContainerPlugin();
        $containerPluginTwo->setContainer('top');
        $containerPluginTwo->setContainerPluginId(1);
        $containerPluginTwo->setRenderOrderNumber(10);
        $containerPluginTwo->setInstance($pluginInstanceTwo);
        $this->entityManager->persist($containerPluginTwo);

        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    private function setupDefaultMock(Array $map)
    {
        $mockBuilder = $this->getMockBuilder('\Rcm\Model\PluginManager2');
        $mockBuilder->disableOriginalConstructor();

        $stub = $mockBuilder->getMock();
        $stub->expects($this->any())
            ->method('getPluginByInstanceId')
            ->will($this->returnValueMap($map));

        $this->containerManager->setPluginManager($stub);
    }

    private function getDefaultMockResult()
    {
        return array(
            0 => array(
                'html' => '<h2 data-textEdit="headline">Planet PHP Feed</h2>',
                'css' => array (
                    '0' => '/modules/rcm-rss-feed/style.css',
                ),
                'js' => array (
                    '0' => '/modules/rcm-rss-feed/RssReader.js',
                ),
                'editJs' => '/modules/rcm-rss-feed/edit.js',
                'editCss' => '',
                'displayName' => 'Rss Feed Reader',
                'tooltip' => 'Rss Reader and Display',
                'icon' => '',
                'siteWide' => false,
                'md5' => '',
                'fromCache' => false,
                'canCache' => true,
                'pluginName' => 'RcmRssFeed',
                'instanceId' => 1,
            ),

            1 => array(
                'html' => '<h2 data-textEdit="headline">Planet PHP Feed</h2>',
                'css' => array (
                    '0' => '/modules/rcm-rss-feed/style.css',
                ),
                'js' => array (
                    '0' => '/modules/rcm-rss-feed/RssReader.js',
                ),
                'editJs' => '/modules/rcm-rss-feed/edit.js',
                'editCss' => '',
                'displayName' => 'Rss Feed Reader',
                'tooltip' => 'Rss Reader and Display',
                'icon' => '',
                'siteWide' => false,
                'md5' => '',
                'fromCache' => false,
                'canCache' => true,
                'pluginName' => 'RcmRssFeed',
                'instanceId' => 2,
            ),
        );
    }

    protected function getDefaultExpectedResult($mockPluginData)
    {
        return array(
            'containerPlugins' => array (
                '0' => array (
                    'containerPluginId' => 1,
                    'container' => 'top',
                    'renderOrder' => 0,
                    'height' => null,
                    'width' => null,
                    'divFloat' => 'left',
                    'plugin_instance_id' => 1,
                    'pluginData' => $mockPluginData[0],
                ),

                '1' => array (
                    'containerPluginId' => 2,
                    'container' => 'top',
                    'renderOrder' => 10,
                    'height' => null,
                    'width' => null,
                    'divFloat' => 'left',
                    'plugin_instance_id' => 2,
                    'pluginData' => $mockPluginData[1]
                ),

            ),

            'fromCache' => null,
            'canCache' => true,
        );
    }

    public function testGetContainerPlugins()
    {
        $this->setupContainerEntity();

        $return = $this->containerManager->getContainerPlugins('top');

        $this->assertArrayHasKey('containerPlugins', $return);
        $this->assertArrayHasKey('fromCache', $return);
        $this->assertArrayHasKey('containerPluginId', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('container', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('renderOrder', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('height', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('width', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('divFloat', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('plugin_instance_id', $return['containerPlugins'][0]);

        $expected = array (
            'containerPluginId' => 1,
            'container' => 'top',
            'renderOrder' => 0,
            'height' => null,
            'width' => null,
            'divFloat' => 'left',
            'plugin_instance_id' => 1,
        );

        $this->assertEquals($expected, $return['containerPlugins'][0]);

        $this->assertFalse($return['fromCache']);
    }

    public function testGetContainerPluginsFromCache()
    {
        $this->setupContainerEntity();

        $this->containerManager->getContainerPlugins('top');

        $return = $this->containerManager->getContainerPlugins('top');

        $this->assertArrayHasKey('containerPlugins', $return);
        $this->assertArrayHasKey('fromCache', $return);
        $this->assertArrayHasKey('containerPluginId', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('container', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('renderOrder', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('height', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('width', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('divFloat', $return['containerPlugins'][0]);
        $this->assertArrayHasKey('plugin_instance_id', $return['containerPlugins'][0]);

        $expected = array (
            'containerPluginId' => 1,
            'container' => 'top',
            'renderOrder' => 0,
            'height' => null,
            'width' => null,
            'divFloat' => 'left',
            'plugin_instance_id' => 1,
        );

        $this->assertEquals($expected, $return['containerPlugins'][0]);

        $this->assertTrue($return['fromCache']);
    }

    public function testGetContainerPluginsWithUndefinedContainer()
    {
        $this->setupContainerEntity();

        $return = $this->containerManager->getContainerPlugins('thisShouldBeEmpty');

        $this->assertArrayHasKey('containerPlugins', $return);
        $this->assertArrayHasKey('fromCache', $return);

        $this->assertTrue(empty($return['containerPlugins']));
        $this->assertFalse($return['fromCache']);

    }

    public function testGetContainerPluginsVerifyCacheNotSavedForUndefinedContainers()
    {
        $this->setupContainerEntity();

        $this->containerManager->getContainerPlugins('thisShouldBeEmpty');
        $return = $this->containerManager->getContainerPlugins('thisShouldBeEmpty');

        $this->assertArrayHasKey('containerPlugins', $return);
        $this->assertArrayHasKey('fromCache', $return);

        $this->assertTrue(empty($return['containerPlugins']));
        $this->assertFalse($return['fromCache']);
    }

    public function testGetPageContainer()
    {
        $this->setupContainerEntity();

        $mockResults = $this->getDefaultMockResult();

        $map = array(
            array(1, $mockResults[0]),
            array(2, $mockResults[1])
        );

        $this->setupDefaultMock($map);

        $return = $this->containerManager->getPageContainer('top');

        $this->assertArrayHasKey('containerPlugins', $return);
        $this->assertArrayHasKey('fromCache', $return);
        $this->assertArrayHasKey('canCache', $return);

        $this->assertTrue(is_array($return['containerPlugins']));
        $this->assertTrue(is_array($return['containerPlugins'][0]['pluginData']));
        $this->assertFalse($return['fromCache']);
        $this->assertTrue($return['canCache']);

        $this->assertEquals($this->getDefaultExpectedResult($mockResults), $return);
    }

    public function testGetPageContainerWithCache()
    {
        $this->setupContainerEntity();

        $mockResults = $this->getDefaultMockResult();

        $map = array(
            array(1, $mockResults[0]),
            array(2, $mockResults[1])
        );

        $this->setupDefaultMock($map);

        $this->containerManager->getPageContainer('top');
        $return = $this->containerManager->getPageContainer('top');

        $this->assertArrayHasKey('containerPlugins', $return);
        $this->assertArrayHasKey('fromCache', $return);
        $this->assertArrayHasKey('canCache', $return);

        $this->assertTrue(is_array($return['containerPlugins']));
        $this->assertTrue(is_array($return['containerPlugins'][0]['pluginData']));
        $this->assertTrue($return['fromCache']);
        $this->assertTrue($return['canCache']);

        $expected = $this->getDefaultExpectedResult($mockResults);
        $expected['fromCache'] = true;
        $expected['canCache'] = true;

        $this->assertEquals($expected, $return);
    }

    public function testGetPageContainerValidateNoCacheWithUncacheablePlugin()
    {
        $this->setupContainerEntity();

        $mockResults = $this->getDefaultMockResult();

        $mockResults[1]['canCache'] = false;

        $map = array(
            array(1, $mockResults[0]),
            array(2, $mockResults[1])
        );

        $this->setupDefaultMock($map);

        $this->containerManager->getPageContainer('top');
        $return = $this->containerManager->getPageContainer('top');

        $this->assertArrayHasKey('containerPlugins', $return);
        $this->assertArrayHasKey('fromCache', $return);
        $this->assertArrayHasKey('canCache', $return);

        $this->assertTrue(is_array($return['containerPlugins']));
        $this->assertTrue(is_array($return['containerPlugins'][0]['pluginData']));
        $this->assertFalse($return['fromCache']);
        $this->assertFalse($return['canCache']);

        $expected = $this->getDefaultExpectedResult($mockResults);
        $expected['fromCache'] = false;
        $expected['canCache'] = false;

        $this->assertEquals($expected, $return);
    }

    public function testGetPageContainerWithNoPluginInstanceDataReturned()
    {

    }
}