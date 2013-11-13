<?php

use \RcmTest\Base\DoctrineTestCase;
use \Rcm\Model\PluginManager2;

class PluginManagerTest extends DoctrineTestCase
{

    /** @var  \Rcm\Model\PluginManager2 */
    protected $pluginManager;

    public function setUp()
    {
        parent::setUp();

        /** @var \Zend\ServiceManager\ServiceManager $sm */
        $sm = Bootstrap::getServiceManager();

        /** @var \Zend\ModuleManager\ModuleManager $moduleManager */
        $moduleManager = $sm->get('ModuleManager');

        $resolver = $sm->get('ViewResolver');

        $render = new \Zend\View\Renderer\PhpRenderer();
        $render->setResolver($resolver);

        $basePath = $render->plugin('basepath');
        $basePath->setBasePath('/');

        /** @var Zend\Cache\Storage\StorageInterface $cache */
        $cache = $sm->get('rcmCache');

        $cache->clearByNamespace('RcmCache');

        $this->pluginManager = new PluginManager2(
            $this->entityManager,
            $sm->get('config'),
            $sm,
            $moduleManager,
            $render,
            new \Zend\Http\Request(),
            $cache
        );
    }

    private function setupRssFeedEntity($siteWide=false)
    {

        $pluginConfig = array (
            'headline' => 'Testing Feed',
            'rssFeedUrl' => 'http://www.planet-php.net/rdf/',
            'rssFeedLimit' => '6',
        );

        $pluginInstance = new \Rcm\Entity\PluginInstance();
        $pluginInstance->setPlugin('RcmRssFeed');
        $pluginInstance->setInstanceId(1);
        $pluginInstance->setMd5('91f65ba866e687ed8f482192cce57bd1');

        if ($siteWide){
            $pluginInstance->setSiteWide();
            $pluginInstance->setDisplayName('Test Site Wide Instance');
        }

        $this->entityManager->persist($pluginInstance);

        $simpleConfig = new \RcmDjPluginStorage\Entity\InstanceConfig();
        $simpleConfig->setInstanceId(1);
        $simpleConfig->setConfig($pluginConfig);

        $this->entityManager->persist($simpleConfig);
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    /**
     * @covers \Rcm\Model\PluginManager2::ensurePluginIsValid
     */
    public function testEnsureValidPlugin()
    {
        $this->assertTrue($this->pluginManager->ensurePluginIsValid('RcmHtmlArea'));
    }

    /**
     * @covers \Rcm\Model\PluginManager2::ensurePluginIsValid
     * @expectedException \Rcm\Exception\InvalidPluginException
     */
    public function testEnsurePluginNotValid()
    {
        $this->pluginManager->ensurePluginIsValid('ThisShouldAlwaysFail');
    }

    /**
     * @covers \Rcm\Model\PluginManager2::getPluginController
     */
    public function testGetPluginController()
    {
        $pluginController = $this->pluginManager->getPluginController('RcmHtmlArea');

        $this->assertTrue(is_a($pluginController, '\Rcm\Plugin\PluginInterface'));
    }

    public function testGetNewEntity()
    {
        $viewData = $this->pluginManager->getNewEntity('RcmRssFeed');

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


        $this->assertContains('<h2 data-textEdit="headline">Planet PHP Feed</h2>', $viewData['html']);
        $this->assertContains('/modules/rcm-rss-feed/style.css', $viewData['css'][0]);
        $this->assertContains('/modules/rcm-rss-feed/RssReader.js', $viewData['js'][0]);
        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertFalse($viewData['siteWide']);
        $this->assertFalse($viewData['fromCache']);
    }

    public function testGetPluginByInstanceId()
    {
        $this->setupRssFeedEntity();

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

        $this->assertContains('<h2 data-textEdit="headline">Testing Feed</h2>', $viewData['html']);
        $this->assertContains('/modules/rcm-rss-feed/style.css', $viewData['css'][0]);
        $this->assertContains('/modules/rcm-rss-feed/RssReader.js', $viewData['js'][0]);
        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertFalse($viewData['siteWide']);
        $this->assertFalse($viewData['fromCache']);
    }

    public function testCacheForGetPluginByInstanceId()
    {
        $this->setupRssFeedEntity();

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

        $this->assertContains('<h2 data-textEdit="headline">Testing Feed</h2>', $viewData['html']);
        $this->assertContains('/modules/rcm-rss-feed/style.css', $viewData['css'][0]);
        $this->assertContains('/modules/rcm-rss-feed/RssReader.js', $viewData['js'][0]);
        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertFalse($viewData['siteWide']);
        $this->assertTrue($viewData['fromCache']);

    }

    public function testGetSiteWide()
    {
        $this->setupRssFeedEntity(true);

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

        $this->assertContains('<h2 data-textEdit="headline">Testing Feed</h2>', $viewData['html']);
        $this->assertContains('/modules/rcm-rss-feed/style.css', $viewData['css'][0]);
        $this->assertContains('/modules/rcm-rss-feed/RssReader.js', $viewData['js'][0]);
        $this->assertContains('Test Site Wide Instance', $viewData['displayName']);
        $this->assertContains('91f65ba866e687ed8f482192cce57bd1', $viewData);
        $this->assertTrue($viewData['siteWide']);
        $this->assertFalse($viewData['fromCache']);
    }

    /**
     * @expectedException Rcm\Exception\PluginInstanceNotFoundException
     */
    public function testGetPluginByInstanceIdException()
    {
        $this->pluginManager->getPluginByInstanceId(1);
    }

    public function testGetNewPluginInstanceEntity()
    {
        $instance = $this->pluginManager->getNewPluginInstanceEntity('RcmHtmlArea');

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $instance);

        $this->assertTrue($instance->getName() == 'RcmHtmlArea');
        $this->assertTrue($instance->getDisplayName() == 'Rich Content Area', 'Display Name incorrect.  Display name set as '.$instance->getDisplayName());
        $this->assertFalse($instance->isSiteWide());
        $this->assertEmpty($instance->getMd5());
    }

    public function testSaveNewInstance()
    {
        $instanceConfig =  array('html' => 'This is a test');

        $newInstance = $this->pluginManager->saveNewInstance('RcmHtmlArea',$instanceConfig);

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $newInstance);

        $this->assertTrue($newInstance->getName() == 'RcmHtmlArea');
        $this->assertTrue($newInstance->getDisplayName() == 'Rich Content Area', 'Display Name incorrect.  Display name set as '.$newInstance->getDisplayName());
        $this->assertGreaterThan(0, $newInstance->getInstanceId());
        $this->assertContains(md5(serialize($instanceConfig)), $newInstance->getMd5());
        $this->assertFalse($newInstance->isSiteWide());
    }

    public function testSaveNewSiteWideInstance()
    {
        $instanceConfig =  array('html' => 'This is a test');

        $newInstance = $this->pluginManager->saveNewInstance('RcmHtmlArea',$instanceConfig, true, 'Test Display Name');

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $newInstance);

        $this->assertTrue($newInstance->getName() == 'RcmHtmlArea');
        $this->assertTrue($newInstance->getDisplayName() == 'Test Display Name', 'Display Name incorrect.  Display name set as '.$newInstance->getDisplayName());
        $this->assertGreaterThan(0, $newInstance->getInstanceId());
        $this->assertContains(md5(serialize($instanceConfig)), $newInstance->getMd5());
        $this->assertTrue($newInstance->isSiteWide());
    }

    /**
     * @depends testSaveNewInstance
     */
    public function testSavePlugin()
    {
        $instanceConfig =  array('html' => 'This is a test');

        $testInstance = $this->pluginManager->saveNewInstance('RcmHtmlArea',$instanceConfig);

        $testInstanceId = $testInstance->getInstanceId();

        $newInstanceConfig = array('html' => 'This is a test too');

        $savedInstance = $this->pluginManager->savePlugin($testInstanceId, $newInstanceConfig);

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $savedInstance);

        $this->assertTrue($savedInstance->getName() == 'RcmHtmlArea');
        $this->assertTrue($savedInstance->getDisplayName() == 'Rich Content Area', 'Display Name incorrect.  Display name set as '.$savedInstance->getDisplayName());
        $this->assertGreaterThan(0, $savedInstance->getInstanceId());
        $this->assertContains(md5(serialize($newInstanceConfig)), $savedInstance->getMd5());
        $this->assertFalse($savedInstance->isSiteWide());

        $this->assertNotEquals($testInstanceId, $savedInstance->getInstanceId());
    }

    /**
     * @depends testSaveNewInstance
     */
    public function testSavePluginWithSameData()
    {
        $instanceConfig =  array('html' => 'This is a test');

        $testInstance = $this->pluginManager->saveNewInstance('RcmHtmlArea',$instanceConfig);

        $testInstanceId = $testInstance->getInstanceId();

        $savedInstance = $this->pluginManager->savePlugin($testInstanceId, $instanceConfig);

        $this->assertInstanceOf('\Rcm\Entity\PluginInstance', $savedInstance);

        $this->assertTrue($savedInstance->getName() == 'RcmHtmlArea');
        $this->assertTrue($savedInstance->getDisplayName() == 'Rich Content Area', 'Display Name incorrect.  Display name set as '.$savedInstance->getDisplayName());
        $this->assertGreaterThan(0, $savedInstance->getInstanceId());
        $this->assertContains(md5(serialize($instanceConfig)), $savedInstance->getMd5());
        $this->assertFalse($savedInstance->isSiteWide());

        $this->assertEquals($testInstanceId, $savedInstance->getInstanceId());
    }

    public function testDeleteInstanceId()
    {
        $this->setupRssFeedEntity();

        $this->assertTrue($this->instanceExistsInDb(1));

        $this->pluginManager->deletePluginInstance(1);

        $this->assertFalse($this->instanceExistsInDb(1));
    }

    private function instanceExistsInDb($instanceId)
    {
        $checkQuery = $this->entityManager->createQuery('
            SELECT COUNT(pi.instanceId) FROM \Rcm\Entity\PluginInstance pi
            WHERE pi.instanceId = :instanceId
        ');

        $checkQuery->setParameter('instanceId', $instanceId);

        $exists = $checkQuery->getSingleScalarResult();

        if ($exists > 0) {
            return true;
        }

        return false;
    }

}