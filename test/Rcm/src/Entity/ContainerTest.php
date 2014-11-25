<?php
/**
 * Unit Test for the Controller Entity
 *
 * This file contains the unit test for the Controller Entity
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

namespace RcmTest\Entity;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Entity\Container;
use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;

/**
 * Unit Test for Controller Entity
 *
 * Unit Test for Controller Entity
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Entity\Container */
    protected $container;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->container = new Container();
    }

    /**
     * Test Get And Set Container ID
     *
     * @return void
     *
     * @covers \Rcm\Entity\Container
     */
    public function testGetAndSetContainerId()
    {
        $id = 4;

        $this->container->setContainerId($id);

        $actual = $this->container->getContainerId();

        $this->assertEquals($id, $actual);
    }

    /**
     * Test Clone
     *
     * @return void
     *
     * @covers \Rcm\Entity\Container
     */
    public function testClone()
    {
        $site = new Site();
        $site->setSiteId(55);

        $container = array(
            'containerId' => '200',
            'name' => 'containerOne',
            'author' => 'Westin Shafer',
            'createdDate' => new \DateTime('yesterday'),
            'lastPublished' => new \DateTime('yesterday'),
            'revisions' => array(
                0 => array(
                    'revisionId' => 100,
                    'author' => 'Westin Shafer',
                    'createdDate' => new \DateTime('yesterday'),
                    'publishedDate' => new \DateTime('yesterday'),
                    'published' => true,
                    'md5' => 'revisionMD5',
                    'instances' => array(
                        0 => array(
                            'pluginWrapperId' => 43,
                            'layoutContainer' => 'layoutOne',
                            'renderOrder' => 0,
                            'height' => 32,
                            'width' => 100,
                            'divFloat' => 'right',
                            'instance' => array(
                                'pluginInstanceId' => 44,
                                'plugin' => 'MockPlugin',
                                'siteWide' => false,
                                'displayName' => null,
                                'instanceConfig' => array(
                                    'var1' => 1,
                                    'var2' => 2
                                ),
                                'md5' => 'firstMd5'
                            ),
                        ),

                        1 => array(
                            'pluginWrapperId' => 45,
                            'layoutContainer' => 'layoutTwo',
                            'renderOrder' => 1,
                            'height' => 33,
                            'width' => 101,
                            'divFloat' => 'none',
                            'instance' => array(
                                'pluginInstanceId' => 46,
                                'plugin' => 'MockPlugin2',
                                'siteWide' => true,
                                'displayName' => 'TestSiteWide',
                                'instanceConfig' => array(
                                    'var3' => 3,
                                    'var4' => 4
                                ),
                                'md5' => 'secondMd5'
                            ),
                        ),
                    ),
                ),

                1 => array(
                    'revisionId' => 101,
                    'author' => 'Westin Shafer',
                    'createdDate' => new \DateTime('-1 month'),
                    'publishedDate' => new \DateTime('-1 month'),
                    'published' => false,
                    'md5' => 'revision2MD5',
                    'instances' => array(
                        0 => array(
                            'pluginWrapperId' => 47,
                            'layoutContainer' => 'layoutThree',
                            'renderOrder' => 2,
                            'height' => 33,
                            'width' => 102,
                            'divFloat' => 'right',
                            'instance' => array(
                                'pluginInstanceId' => 48,
                                'plugin' => 'MockPlugin3',
                                'siteWide' => false,
                                'displayName' => null,
                                'instanceConfig' => array(
                                    'var1' => 1,
                                    'var2' => 2
                                ),
                                'md5' => 'firstMd5'
                            ),
                        ),

                        1 => array(
                            'pluginWrapperId' => 49,
                            'layoutContainer' => 'layoutFour',
                            'renderOrder' => 3,
                            'height' => 34,
                            'width' => 103,
                            'divFloat' => 'left',
                            'instance' => array(
                                'pluginInstanceId' => 50,
                                'plugin' => 'MockPlugin4',
                                'siteWide' => true,
                                'displayName' => 'TestSiteWide2',
                                'instanceConfig' => array(
                                    'var3' => 3,
                                    'var4' => 4
                                ),
                                'md5' => 'secondMd5'
                            ),
                        ),
                    )
                )
            ),
        );

        $this->container->setContainerId($container['containerId']);
        $this->container->setName($container['name']);
        $this->container->setAuthor($container['author']);
        $this->container->setCreatedDate($container['createdDate']);
        $this->container->setLastPublished($container['lastPublished']);
        $this->container->setSite($site);

        foreach ($container['revisions'] as $index => $revisionData) {
            $revision = new Revision();
            $revision->setRevisionId($revisionData['revisionId']);
            $revision->setAuthor($revisionData['author']);
            $revision->setCreatedDate($revisionData['createdDate']);
            $revision->publishRevision();
            $revision->setPublishedDate($revisionData['publishedDate']);
            $revision->setMd5($revisionData['md5']);

            foreach ($revisionData['instances'] as $instance) {
                $plugin = new PluginInstance();
                $plugin->setInstanceId($instance['instance']['pluginInstanceId']);
                $plugin->setPlugin($instance['instance']['plugin']);

                if ($instance['instance']['siteWide']) {
                    $plugin->setSiteWide();
                }

                $plugin->setDisplayName($instance['instance']['displayName']);
                $plugin->setInstanceConfig($instance['instance']['instanceConfig']);
                $plugin->setMd5($instance['instance']['md5']);

                $wrapper = new PluginWrapper();
                $wrapper->setPluginWrapperId($instance['pluginWrapperId']);
                $wrapper->setLayoutContainer($instance['layoutContainer']);
                $wrapper->setRenderOrderNumber($instance['renderOrder']);
                $wrapper->setHeight($instance['height']);
                $wrapper->setWidth($instance['width']);
                $wrapper->setDivFloat($instance['divFloat']);
                $wrapper->setInstance($plugin);

                $revision->addPluginWrapper($wrapper);
            }

            if ($index === 0) {
                $this->container->setPublishedRevision($revision);
            } elseif ($index === 1) {
                $this->container->setStagedRevision($revision);
            }

            $this->container->addRevision($revision);
        }

        $this->assertCount(2, $this->container->getRevisions());

        $clonedContainer = clone $this->container;

        /* Test Container */
        $this->assertNotEquals(
            $this->container->getContainerId(),
            $clonedContainer->getContainerId()
        );

        $this->assertNull($clonedContainer->getContainerId());
        $this->assertNull($clonedContainer->getStagedRevision());
        $this->assertCount(1, $clonedContainer->getRevisions());

        $currentRevision = $this->container->getPublishedRevision();
        $clonedCurrentRev = $clonedContainer->getPublishedRevision();

        /* Test Revision */
        $this->assertNotEquals(
            $currentRevision->getRevisionId(),
            $clonedCurrentRev->getRevisionId()
        );

        $this->assertNull($clonedCurrentRev->getRevisionId());

        $this->assertEquals(
            $currentRevision->getAuthor(),
            $clonedCurrentRev->getAuthor()
        );

        $this->assertNotEquals(
            $currentRevision->getCreatedDate(),
            $clonedCurrentRev->getCreatedDate()
        );

        $this->assertTrue($clonedCurrentRev->wasPublished());

        $this->assertEquals(
            $currentRevision->getMd5(),
            $clonedCurrentRev->getMd5()
        );

        $revisionWrappers = $currentRevision->getPluginWrappers();
        $clonedWrappers = $clonedCurrentRev->getPluginWrappers();


        $this->assertNotEquals($revisionWrappers, $clonedWrappers);

        /** @var \Rcm\Entity\PluginWrapper $clonedWrapper */
        foreach ($clonedWrappers as $clonedWrapper) {
            if(!$clonedWrapper->getInstance()->isSiteWide()) {
                $this->assertNull($clonedWrapper->getInstance()->getInstanceId());
            } else {
                $this->assertNotNull($clonedWrapper->getInstance()->getInstanceId());
            }
        }

        $this->container->setContainerId(null);

        $noContainerClone = clone($this->container);
    }
}
 