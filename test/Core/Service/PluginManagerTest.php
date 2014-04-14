<?php

require_once __DIR__ . '/../../Base/DoctrineTestCase.php';

use \RcmTest\Base\BaseTestCase;
use \Rcm\Service\PluginManager;

class PluginManagerTest extends BaseTestCase
{

    /** @var  \Rcm\Service\PluginManager */
    protected $pluginManager;

    protected $instanceCounter = 1000000;

    protected $newPlugins = array();

    protected $removeCounter = 0;

    /** @var  \Zend\Cache\Storage\StorageInterface */
    protected $cache;

    public function setUp()
    {
        $this->addModule('RcmMockPlugin');

        parent::setUp();

        $em = $this->getEmMock();

        /** @var \Zend\ServiceManager\ServiceManager $sm */
        $sm = $this->getServiceManager();

        $render = $this->getRenderer();

        /** @var \Zend\Cache\Storage\StorageInterface $cache */
        $cache = $sm->get('Rcm\\Service\\Cache');
        $cache->clearByNamespace('RcmCache');

        $moduleManager = $sm->get('ModuleManager');

        $this->pluginManager = new PluginManager(
            $em,
            $sm->get('config'),
            $sm,
            $moduleManager,
            $render,
            new \Zend\Http\PhpEnvironment\Request(),
            $cache
        );

        $this->cache = $cache;
    }

    private function getEmMock()
    {
        $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $repoMock = $this->getMockBuilder('\Doctrine\Common\Persistence\ObjectRepository')
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

    public function removeCallback()
    {
        $this->removeCounter++;
        return;
    }

    public function persistCallback()
    {
        $args = func_get_args();

        if (!empty($args[0]) && is_a($args[0], 'Rcm\Entity\PluginInstance')) {
            /** @var \Rcm\Entity\PluginInstance $pluginInstance */
            $pluginInstance = $args[0];

            $id = $pluginInstance->getInstanceId();

            if (empty($id)){
                $pluginInstance->setInstanceId($this->instanceCounter);
                $this->newPlugins[$this->instanceCounter] = $pluginInstance;
                $this->instanceCounter++;
            }
        }
    }

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
            if (!empty($this->newPlugins[$pluginInstanceId])){
                return $this->newPlugins[$pluginInstanceId];
            }
            return null;
        }

    }

    private function setupMockEntity($siteWide = false, $instanceId=1)
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
     * @covers \Rcm\Service\PluginManager::ensurePluginIsValid
     */
    public function testEnsureValidPlugin()
    {
        $this->assertTrue(
            $this->pluginManager->ensurePluginIsValid('RcmMockPlugin')
        );
    }

    /**
     * @covers \Rcm\Service\PluginManager::ensurePluginIsValid
     * @expectedException \Rcm\Exception\InvalidPluginException
     */
    public function testEnsurePluginNotValid()
    {
        $this->pluginManager->ensurePluginIsValid('ThisShouldAlwaysFail');
    }

    /**
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
            '{"html":"<p>This is a test<\/p>"}',
            $viewData['html']
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/style.css', $viewData['css'][0]
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/test.js', $viewData['js'][0]
        );
        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertFalse($viewData['siteWide']);
        $this->assertFalse($viewData['fromCache']);
    }

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
            '{"instanceData":"<p>This is a instance id 1<\/p>"}', $viewData['html']
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/style.css', $viewData['css'][0]
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/test.js', $viewData['js'][0]
        );
        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertFalse($viewData['siteWide']);
        $this->assertFalse($viewData['fromCache']);
    }

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
            '{"instanceData":"<p>This is a instance id 1<\/p>"}', $viewData['html']
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/style.css', $viewData['css'][0]
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/test.js', $viewData['js'][0]
        );
        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertFalse($viewData['siteWide']);
        $this->assertTrue($viewData['fromCache']);

    }

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
            '{"instanceData":"<p>This is a instance id 2<\/p>"}', $viewData['html']
        );

        $this->assertContains(
            '/modules/rcm-mock-plugin/style.css', $viewData['css'][0]
        );
        $this->assertContains(
            '/modules/rcm-mock-plugin/test.js', $viewData['js'][0]
        );
        $this->assertContains(
            'Test Site Wide Instance', $viewData['displayName'], 'Failed. value: '.print_r($viewData, true)
        );
        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertTrue($viewData['siteWide']);
        $this->assertFalse($viewData['fromCache']);
    }

    /**
     * @expectedException Rcm\Exception\PluginInstanceNotFoundException
     */
    public function testGetPluginByInstanceIdException()
    {
        $this->pluginManager->getPluginByInstanceId(10000);
    }

    public function testGetNewPluginInstanceEntity()
    {
        $instance = $this->pluginManager->getNewPluginInstanceEntity(
            'RcmMockPlugin'
        );

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $instance);

        $this->assertTrue($instance->getName() == 'RcmMockPlugin');
        $this->assertTrue(
            $instance->getDisplayName() == 'Mock Object Display Name',
            'Display Name incorrect.  Display name set as '
            . $instance->getDisplayName()
        );
        $this->assertFalse($instance->isSiteWide());
        $this->assertEmpty($instance->getMd5());
    }

    public function testSaveNewInstance()
    {
        $instanceConfig = array('html' => 'This is a test');

        $newInstance = $this->pluginManager->saveNewInstance(
            'RcmMockPlugin', $instanceConfig
        );

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $newInstance);

        $this->assertTrue($newInstance->getName() == 'RcmMockPlugin');
        $this->assertTrue(
            $newInstance->getDisplayName() == 'Mock Object Display Name',
            'Display Name incorrect.  Display name set as '
            . $newInstance->getDisplayName()
        );
        $this->assertGreaterThan(0, $newInstance->getInstanceId());
        $this->assertContains(
            md5(serialize($instanceConfig)), $newInstance->getMd5()
        );
        $this->assertFalse($newInstance->isSiteWide());
    }

    public function testSaveNewSiteWideInstance()
    {
        $instanceConfig = array('html' => 'This is a test');

        $newInstance = $this->pluginManager->saveNewInstance(
            'RcmMockPlugin', $instanceConfig, true, 'Test Display Name'
        );

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $newInstance);

        $this->assertTrue($newInstance->getName() == 'RcmMockPlugin');
        $this->assertTrue(
            $newInstance->getDisplayName() == 'Test Display Name',
            'Display Name incorrect.  Display name set as '
            . $newInstance->getDisplayName()
        );
        $this->assertGreaterThan(0, $newInstance->getInstanceId());
        $this->assertContains(
            md5(serialize($instanceConfig)), $newInstance->getMd5()
        );
        $this->assertTrue($newInstance->isSiteWide());
    }

    /**
     * @depends testSaveNewInstance
     */
    public function testSavePlugin()
    {
        $instanceConfig = array('html' => 'This is a test');

        $testInstance = $this->pluginManager->saveNewInstance(
            'RcmMockPlugin', $instanceConfig
        );

        $testInstanceId = $testInstance->getInstanceId();

        $newInstanceConfig = array('html' => 'This is a test too');

        $savedInstance = $this->pluginManager->savePlugin(
            $testInstanceId, $newInstanceConfig
        );

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $savedInstance);

        $this->assertTrue($savedInstance->getName() == 'RcmMockPlugin');
        $this->assertTrue(
            $savedInstance->getDisplayName() == 'Mock Object Display Name',
            'Display Name incorrect.  Display name set as '
            . $savedInstance->getDisplayName()
        );
        $this->assertGreaterThan(0, $savedInstance->getInstanceId());
        $this->assertContains(
            md5(serialize($newInstanceConfig)), $savedInstance->getMd5()
        );
        $this->assertFalse($savedInstance->isSiteWide());

        $this->assertNotEquals(
            $testInstanceId, $savedInstance->getInstanceId()
        );
    }

    /**
     * @depends testSaveNewInstance
     */
    public function testSavePluginWithSameData()
    {
        $instanceConfig = array('html' => 'This is a test');

        $testInstance = $this->pluginManager->saveNewInstance(
            'RcmMockPlugin', $instanceConfig
        );

        $testInstanceId = $testInstance->getInstanceId();

        $savedInstance = $this->pluginManager->savePlugin(
            $testInstanceId, $instanceConfig
        );

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $savedInstance);

        $this->assertTrue($savedInstance->getName() == 'RcmMockPlugin');
        $this->assertTrue(
            $savedInstance->getDisplayName() == 'Mock Object Display Name',
            'Display Name incorrect.  Display name set as '
            . $savedInstance->getDisplayName()
        );
        $this->assertGreaterThan(0, $savedInstance->getInstanceId());
        $this->assertContains(
            md5(serialize($instanceConfig)), $savedInstance->getMd5()
        );
        $this->assertFalse($savedInstance->isSiteWide());

        $this->assertEquals($testInstanceId, $savedInstance->getInstanceId());
    }

    public function testDeleteInstanceId()
    {
        $this->pluginManager->deletePluginInstance(1);
        $this->assertEquals(1, $this->removeCounter);
    }

    /**
     * @expectedException \Rcm\Exception\PluginInstanceNotFoundException
     */
    public function testDeleteInstanceIdNotFound()
    {
        $this->pluginManager->deletePluginInstance(40000000);
        $this->assertEquals(2, $this->removeCounter);
    }

    /**
     * @expectedException \RcmMockPlugin\Exception\RuntimeException
     */
    public function testDeleteInstanceIdPluginControllerThrowsException()
    {
        $this->pluginManager->deletePluginInstance(5000000);
        $this->assertEquals(3, $this->removeCounter);
    }

}