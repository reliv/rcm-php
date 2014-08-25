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
require_once __DIR__ . '/../../../autoload.php';

use Rcm\Service\PluginManager;
use Zend\Cache\Storage\Adapter\Memory;
use Zend\Http\PhpEnvironment\Request;
use Zend\ModuleManager\Listener;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Renderer\PhpRenderer;

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
class PluginManagerTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \Rcm\Service\PluginManager */
    protected $pluginManager;

    protected $instanceCounter = 1000000;

    protected $newPlugins = array();

    protected $removeCounter = 0;

    /** @var  \Zend\Cache\Storage\StorageInterface */
    protected $cache;

    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getEmMock();

        /** @var \Zend\ServiceManager\ServiceManager $sm */
        $sm = $this->startZf2();

        /** @var \Zend\View\Resolver\ResolverInterface $resolver */
        $resolver = $sm->get('ViewResolver');

        $render = new PhpRenderer();
        $render->setResolver($resolver);

        /** @var \Zend\View\Helper\BasePath $basePath */
        $basePath = $render->plugin('basepath');
        $basePath->setBasePath('/');

        /** @var \Zend\Cache\Storage\Adapter\Memory $cache */
        $cache = new Memory();

        $sm->setService('Rcm\Service\Cache', $cache);

        $cache->flush();

        $this->pluginManager = new PluginManager(
            $em,
            $sm->get('config'),
            $sm,
            $render,
            new Request(),
            $cache
        );

        $this->cache = $cache;
    }

    /**
     * Used during setup to start the ZF2 environment
     *
     * @return ServiceManager
     */
    protected function startZf2()
    {

        $applicationConfig = array(
            'modules' => array(
                'RcmMockPlugin'
            ),
            'module_listener_options' => array(
                'module_paths' => array(
                    __DIR__ . '/../../../',
                ),
            ),
        );
        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $applicationConfig);
        $serviceManager->get('ModuleManager')->loadModules();

        return $serviceManager;
    }


    /**
     * Get a mock EM object
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEmMock()
    {
        $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $repoMock = $this
            ->getMockBuilder('\Doctrine\Common\Persistence\ObjectRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $repoMock->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnCallback(array($this, 'emMockEntityCallback')));

        $em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repoMock));

        $em->expects($this->any())
            ->method('remove')
            ->will($this->returnCallback(array($this, 'removeCallback')));

        $em->expects($this->any())
            ->method('persist')
            ->will($this->returnCallback(array($this, 'persistCallback')));

        return $em;
    }

    /**
     * Call back for remove counter
     *
     * @return void
     */
    public function removeCallback()
    {
        $this->removeCounter++;
        return;
    }

    /**
     * Counter for persist calls to EM
     *
     * @return void
     */
    public function persistCallback()
    {
        $args = func_get_args();

        if (!empty($args[0]) && is_a($args[0], 'Rcm\Entity\PluginInstance')) {
            /** @var \Rcm\Entity\PluginInstance $pluginInstance */
            $pluginInstance = $args[0];

            $id = $pluginInstance->getInstanceId();

            if (empty($id)) {
                $pluginInstance->setInstanceId($this->instanceCounter);
                $this->newPlugins[$this->instanceCounter] = $pluginInstance;
                $this->instanceCounter++;
            }
        }
    }

    /**
     * Gets a mock plugin for testing
     *
     * @return null|\Rcm\Entity\PluginInstance
     */
    public function emMockEntityCallback()
    {
        $args = func_get_args();

        $pluginInstanceId = null;

        if (!empty($args[0]) && !empty($args[0]['pluginInstanceId'])) {
            $pluginInstanceId = $args[0]['pluginInstanceId'];
        }

        switch ($pluginInstanceId) {
            case 5000000:
                return $this->setupMockEntity(false, 5000000);
            case 2:
                return $this->setupMockEntity(true, 2);
            case 1:
                return $this->setupMockEntity(false, 1);
            default:
                if (!empty($this->newPlugins[$pluginInstanceId])) {
                    return $this->newPlugins[$pluginInstanceId];
                }
                return null;
        }

    }

    /**
     * Prep Mock Entity Data
     *
     * @param bool $siteWide set site wide
     * @param int  $instanceId instance id
     *
     * @return \Rcm\Entity\PluginInstance
     */
    protected function setupMockEntity($siteWide = false, $instanceId = 1)
    {
        $pluginInstance = new \Rcm\Entity\PluginInstance();
        $pluginInstance->setPlugin('RcmMockPlugin');
        $pluginInstance->setInstanceId($instanceId);
        $pluginInstance->setMd5('91f65ba866e687ed8f482192cce57bd1');

        if ($siteWide) {
            $pluginInstance->setSiteWide();
            $pluginInstance->setDisplayName('Test Site Wide Instance');
        }

        return $pluginInstance;
    }

    /**
     * Test Get Plugin Controller Method
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::getPluginController
     */
    public function testGetPluginController()
    {
        $pluginController = $this->pluginManager->getPluginController(
            'RcmMockPlugin'
        );

        $this->assertTrue(
            is_a($pluginController, '\Rcm\Plugin\PluginInterface')
        );
    }

    /**
     * Test Getting a new Entity
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::getNewEntity
     */
    public function testGetNewEntity()
    {
        $viewData = $this->pluginManager->getNewEntity('RcmMockPlugin');

        $this->assertArrayHasKey('html', $viewData);
        $this->assertArrayHasKey('css', $viewData);
        $this->assertArrayHasKey('js', $viewData);
        $this->assertArrayHasKey('editJs', $viewData);
        $this->assertArrayHasKey('editCss', $viewData);
        $this->assertArrayHasKey('displayName', $viewData);
        $this->assertArrayHasKey('tooltip', $viewData);
        $this->assertArrayHasKey('icon', $viewData);
        $this->assertArrayHasKey('fromCache', $viewData);
        $this->assertArrayHasKey('siteWide', $viewData);
        $this->assertArrayHasKey('pluginName', $viewData);
        $this->assertArrayHasKey('md5', $viewData);
        $this->assertArrayHasKey('canCache', $viewData);


        $this->assertContains(
            '{"instanceData":"<p>This is a instance id -1<\/p>"}',
            $viewData['html']
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/style.css',
            $viewData['css'][0]
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/test.js',
            $viewData['js'][0]
        );
        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertFalse($viewData['siteWide']);
        $this->assertFalse($viewData['fromCache']);
    }

    /**
     * Test Getting a Plugin by Instance ID
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::getPluginByInstanceId
     */
    public function testGetPluginByInstanceId()
    {
        $viewData = $this->pluginManager->getPluginByInstanceId(1);

        $this->assertArrayHasKey('html', $viewData);
        $this->assertArrayHasKey('css', $viewData);
        $this->assertArrayHasKey('js', $viewData);
        $this->assertArrayHasKey('editJs', $viewData);
        $this->assertArrayHasKey('editCss', $viewData);
        $this->assertArrayHasKey('displayName', $viewData);
        $this->assertArrayHasKey('tooltip', $viewData);
        $this->assertArrayHasKey('icon', $viewData);
        $this->assertArrayHasKey('fromCache', $viewData);
        $this->assertArrayHasKey('siteWide', $viewData);
        $this->assertArrayHasKey('pluginName', $viewData);
        $this->assertArrayHasKey('md5', $viewData);
        $this->assertArrayHasKey('canCache', $viewData);

        $this->assertContains(
            '{"instanceData":"<p>This is a instance id 1<\/p>"}',
            $viewData['html']
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/style.css',
            $viewData['css'][0]
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/test.js',
            $viewData['js'][0]
        );
        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertFalse($viewData['siteWide']);
        $this->assertFalse($viewData['fromCache']);
    }

    /**
     * Test Getting a plugin from Cache
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::getPluginByInstanceId
     */
    public function testCacheForGetPluginByInstanceId()
    {
        $this->pluginManager->getPluginByInstanceId(1);

        $viewData = $this->pluginManager->getPluginByInstanceId(1);

        $this->assertArrayHasKey('html', $viewData);
        $this->assertArrayHasKey('css', $viewData);
        $this->assertArrayHasKey('js', $viewData);
        $this->assertArrayHasKey('editJs', $viewData);
        $this->assertArrayHasKey('editCss', $viewData);
        $this->assertArrayHasKey('displayName', $viewData);
        $this->assertArrayHasKey('tooltip', $viewData);
        $this->assertArrayHasKey('icon', $viewData);
        $this->assertArrayHasKey('fromCache', $viewData);
        $this->assertArrayHasKey('siteWide', $viewData);
        $this->assertArrayHasKey('pluginName', $viewData);
        $this->assertArrayHasKey('md5', $viewData);
        $this->assertArrayHasKey('canCache', $viewData);

        $this->assertContains(
            '{"instanceData":"<p>This is a instance id 1<\/p>"}',
            $viewData['html']
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/style.css',
            $viewData['css'][0]
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/test.js',
            $viewData['js'][0]
        );
        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertFalse($viewData['siteWide']);
        $this->assertTrue($viewData['fromCache']);

    }

    /**
     * Test Getting a site wide plugin
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::getPluginByInstanceId
     */
    public function testGetSiteWide()
    {
        $viewData = $this->pluginManager->getPluginByInstanceId(2);

        $this->assertArrayHasKey('html', $viewData);
        $this->assertArrayHasKey('css', $viewData);
        $this->assertArrayHasKey('js', $viewData);
        $this->assertArrayHasKey('editJs', $viewData);
        $this->assertArrayHasKey('editCss', $viewData);
        $this->assertArrayHasKey('displayName', $viewData);
        $this->assertArrayHasKey('tooltip', $viewData);
        $this->assertArrayHasKey('icon', $viewData);
        $this->assertArrayHasKey('fromCache', $viewData);
        $this->assertArrayHasKey('siteWide', $viewData);
        $this->assertArrayHasKey('pluginName', $viewData);
        $this->assertArrayHasKey('md5', $viewData);
        $this->assertArrayHasKey('canCache', $viewData);

        $this->assertContains(
            '{"instanceData":"<p>This is a instance id 2<\/p>"}',
            $viewData['html']
        );

        $this->assertContains(
            '/modules/rcm-mock-plugin/style.css',
            $viewData['css'][0]
        );

        $this->assertContains(
            '/modules/rcm-mock-plugin/test.js',
            $viewData['js'][0]
        );

        $this->assertContains(
            'Test Site Wide Instance',
            $viewData['displayName'],
            'Failed. value: ' . print_r($viewData, true)
        );

        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertTrue($viewData['siteWide']);
        $this->assertFalse($viewData['fromCache']);
    }

    /**
     * Test invalid instance ID
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::getPluginByInstanceId
     * @expectedException Rcm\Exception\PluginInstanceNotFoundException
     */
    public function testGetPluginByInstanceIdException()
    {
        $this->pluginManager->getPluginByInstanceId(10000);
    }

    /**
     * Test Getting a new plugin Entity
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::getNewPluginInstanceEntity
     */
    public function testGetNewPluginInstanceEntity()
    {
        $instance = $this->pluginManager->getNewPluginInstanceEntity(
            'RcmMockPlugin'
        );

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $instance);

        $this->assertTrue($instance->getPlugin() == 'RcmMockPlugin');
        $this->assertTrue(
            $instance->getDisplayName() == 'Mock Object Display Name',
            'Display Name incorrect.  Display name set as '
            . $instance->getDisplayName()
        );
        $this->assertFalse($instance->isSiteWide());
        $this->assertEmpty($instance->getMd5());
    }

    /**
     * Test Saving a new Instance
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::saveNewInstance
     */
    public function testSaveNewInstance()
    {
        $instanceConfig = array('html' => 'This is a test');

        $newInstance = $this->pluginManager->saveNewInstance(
            'RcmMockPlugin',
            $instanceConfig
        );

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $newInstance);

        $this->assertTrue($newInstance->getPlugin() == 'RcmMockPlugin');
        $this->assertTrue(
            $newInstance->getDisplayName() == 'Mock Object Display Name',
            'Display Name incorrect.  Display name set as '
            . $newInstance->getDisplayName()
        );
        $this->assertGreaterThan(0, $newInstance->getInstanceId());
        $this->assertContains(
            md5(serialize($instanceConfig)),
            $newInstance->getMd5()
        );
        $this->assertFalse($newInstance->isSiteWide());
    }

    /**
     * Test Saving a new Site Wide Instance
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::saveNewInstance
     */
    public function testSaveNewSiteWideInstance()
    {
        $instanceConfig = array('html' => 'This is a test');

        $newInstance = $this->pluginManager->saveNewInstance(
            'RcmMockPlugin',
            $instanceConfig,
            true,
            'Test Display Name'
        );

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $newInstance);

        $this->assertTrue($newInstance->getPlugin() == 'RcmMockPlugin');
        $this->assertTrue(
            $newInstance->getDisplayName() == 'Test Display Name',
            'Display Name incorrect.  Display name set as '
            . $newInstance->getDisplayName()
        );
        $this->assertGreaterThan(0, $newInstance->getInstanceId());
        $this->assertContains(
            md5(serialize($instanceConfig)),
            $newInstance->getMd5()
        );
        $this->assertTrue($newInstance->isSiteWide());
    }

    /**
     * Test Saving a new plugin
     *
     * @return void
     *
     * @covers  \Rcm\Service\PluginManager::saveNewInstance
     * @depends testSaveNewInstance
     */
    public function testSavePlugin()
    {
        $instanceConfig = array('html' => 'This is a test');

        $testInstance = $this->pluginManager->saveNewInstance(
            'RcmMockPlugin',
            $instanceConfig
        );

        $testInstanceId = $testInstance->getInstanceId();

        $newInstanceConfig = array('html' => 'This is a test too');

        $savedInstance = $this->pluginManager->savePlugin(
            $testInstanceId,
            $newInstanceConfig
        );

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $savedInstance);

        $this->assertTrue($savedInstance->getPlugin() == 'RcmMockPlugin');
        $this->assertTrue(
            $savedInstance->getDisplayName() == 'Mock Object Display Name',
            'Display Name incorrect.  Display name set as '
            . $savedInstance->getDisplayName()
        );
        $this->assertGreaterThan(0, $savedInstance->getInstanceId());
        $this->assertContains(
            md5(serialize($newInstanceConfig)),
            $savedInstance->getMd5()
        );
        $this->assertFalse($savedInstance->isSiteWide());

        $this->assertNotEquals(
            $testInstanceId,
            $savedInstance->getInstanceId()
        );
    }

    /**
     * Test Saving a plugin with no change should be skipped
     *
     * @return void
     *
     * @covers  \Rcm\Service\PluginManager::saveNewInstance
     * @depends testSaveNewInstance
     */
    public function testSavePluginWithSameData()
    {
        $instanceConfig = array('html' => 'This is a test');

        $testInstance = $this->pluginManager->saveNewInstance(
            'RcmMockPlugin',
            $instanceConfig
        );

        $testInstanceId = $testInstance->getInstanceId();

        $savedInstance = $this->pluginManager->savePlugin(
            $testInstanceId,
            $instanceConfig
        );

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $savedInstance);

        $this->assertTrue($savedInstance->getPlugin() == 'RcmMockPlugin');
        $this->assertTrue(
            $savedInstance->getDisplayName() == 'Mock Object Display Name',
            'Display Name incorrect.  Display name set as '
            . $savedInstance->getDisplayName()
        );
        $this->assertGreaterThan(0, $savedInstance->getInstanceId());
        $this->assertContains(
            md5(serialize($instanceConfig)),
            $savedInstance->getMd5()
        );
        $this->assertFalse($savedInstance->isSiteWide());

        $this->assertEquals($testInstanceId, $savedInstance->getInstanceId());
    }

    /**
     * Test Deleting a Plugin
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::deletePluginInstance
     */
    public function testDeleteInstanceId()
    {
        $this->pluginManager->deletePluginInstance(1);
        $this->assertEquals(1, $this->removeCounter);
    }

    /**
     * Test Deleting a Non-existing Plugin
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::deletePluginInstance
     * @expectedException \Rcm\Exception\PluginInstanceNotFoundException
     */
    public function testDeleteInstanceIdNotFound()
    {
        $this->pluginManager->deletePluginInstance(40000000);
        $this->assertEquals(2, $this->removeCounter);
    }

    /**
     * Test Deleting a Plugin causes exception
     *
     * @return void
     *
     * @covers \Rcm\Service\PluginManager::deletePluginInstance
     * @expectedException \RcmMockPlugin\Exception\RuntimeException
     */
    public function testDeleteInstanceIdPluginControllerThrowsException()
    {
        $this->pluginManager->deletePluginInstance(5000000);
        $this->assertEquals(3, $this->removeCounter);
    }

    /**
     * @covers \Rcm\Service\PluginManager::listAvailablePluginsByType
     */
    public function testListAvailablePluginsByType()
    {
        $result = $this->pluginManager->listAvailablePluginsByType();
        $this->assertEquals(
            'Mock Object Display Name',
            $result['Common']['RcmMockPlugin']['displayName']
        );
    }
}