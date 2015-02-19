<?php
/**
 * Unit Test for the Container Abstract
 *
 * This file contains the unit test for the Container Abstract
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

use Doctrine\Common\Collections\ArrayCollection;
use Rcm\Entity\Container;
use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;

/**
 * Unit Test for Container Abstract Entity
 *
 * Unit Test for Container Abstract Entity
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class ContainerAbstractTest extends \PHPUnit_Framework_TestCase
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
     * Test Get and Set Name Property
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testGetAndSetName()
    {
        $name = 'some-name';

        $this->container->setName($name);

        $actual = $this->container->getName();

        $this->assertEquals($name, $actual);
    }

    /**
     * Test Exception thrown if name contains spaces
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testSetNameWithSpacesThrowsException()
    {
        $this->container->setName('My Name Has Spaces');
    }

    /**
     * Test Get and Set Author
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testGetAndSetAuthor()
    {
        $author = 'Westin Shafer';

        $this->container->setAuthor($author);

        $actual = $this->container->getAuthor();

        $this->assertEquals($author, $actual);
    }

    /**
     * Test Get and Set Created Date
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testGetAndSetCreatedDate()
    {
        $createdDate = new \DateTime('2014-Apr-20');

        $this->container->setCreatedDate($createdDate);

        $actual = $this->container->getCreatedDate();

        $this->assertEquals($createdDate, $actual);
    }

    /**
     * Test Created Date Only Accepts a DateTime object
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetCreatedDateOnlyAcceptsDateTime()
    {
        $this->container->setCreatedDate(time());
    }

    /**
     * Test Get and Set Last Published Date
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testGetAndSetLastPublished()
    {
        $lastPublished = new \DateTime('2014-Apr-20');

        $this->container->setLastPublished($lastPublished);

        $actual = $this->container->getLastPublished();

        $this->assertEquals($lastPublished, $actual);
    }

    /**
     * Test Set Last Published Only Accepts a DateTime object
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetLastPublishedOnlyAcceptsDateTime()
    {
        $this->container->setLastPublished(time());
    }

    /**
     * Test Get and Set Published Revision
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testGetAndSetPublishedRevision()
    {
        $publishedRevision = new Revision();
        $publishedRevision->setRevisionId(6);

        $this->container->setPublishedRevision($publishedRevision);

        $actual = $this->container->getPublishedRevision();

        $this->assertEquals($publishedRevision, $actual);
    }

    /**
     * Test Alias Set Published Revision Only Accepts a Revision object
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetPublishedRevisionOnlyAcceptsDateTime()
    {
        $this->container->setLastPublished(time());
    }

    /**
     * Test Get and Set Current Revision
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testAliasGetAndSetCurrentRevisionRevision()
    {
        $publishedRevision = new Revision();
        $publishedRevision->setRevisionId(6);

        $this->container->setPublishedRevision($publishedRevision);

        $actual = $this->container->getPublishedRevision();

        $this->assertEquals($publishedRevision, $actual);
    }

    /**
     * Test Set Current Revision Only Accepts a Revision object
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testAliasSetCurrentRevisionOnlyAcceptsDateTime()
    {
        $this->container->setPublishedRevision(time());
    }

    /**
     * Test Remove Current Revision
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testRemoveCurrentRevisionRevision()
    {
        $publishedRevision = new Revision();
        $publishedRevision->setRevisionId(6);

        $this->container->setPublishedRevision($publishedRevision);

        $actual = $this->container->getPublishedRevision();

        $this->assertEquals($publishedRevision, $actual);

        $this->container->removePublishedRevision();

        $shouldBeEmpty = $this->container->getPublishedRevision();

        $this->assertEmpty($shouldBeEmpty);
    }

    /**
     * Test Get and Set Staged Revision
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testGetAndSetStagedRevisionRevision()
    {
        $publishedRevision = new Revision();
        $publishedRevision->setRevisionId(6);

        $this->container->setStagedRevision($publishedRevision);

        $actual = $this->container->getStagedRevision();

        $this->assertEquals($publishedRevision, $actual);
    }

    /**
     * Test Set Staged Revision Only Accepts a Revision object
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetStagedRevisionOnlyAcceptsDateTime()
    {
        $this->container->setStagedRevision(time());
    }

    /**
     * Test Remove Current Revision
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testRemoveStagedRevisionRevision()
    {
        $publishedRevision = new Revision();
        $publishedRevision->setRevisionId(6);

        $this->container->setStagedRevision($publishedRevision);

        $actual = $this->container->getStagedRevision();

        $this->assertEquals($publishedRevision, $actual);

        $this->container->removeStagedRevision();

        $shouldBeEmpty = $this->container->getStagedRevision();

        $this->assertEmpty($shouldBeEmpty);
    }

    /**
     * Test Get and Set Site
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testGetAndSetSite()
    {
        $site = new Site();
        $site->setSiteId(3);

        $this->container->setSite($site);

        $actual = $this->container->getSite();

        $this->assertEquals($site, $actual);
    }

    /**
     * Test Set Site Only Accepts a Site object
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetSiteOnlyAcceptsSiteEntity()
    {
        $this->container->setSite(time());
    }

    /**
     * Test Add Revision
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testGetAndAddRevisions()
    {
        $revisionFive = new Revision();
        $revisionFive->setRevisionId(5);

        $revisionSix = new Revision();
        $revisionSix->setRevisionId(6);

        $expected = [
            5 => $revisionFive,
            6 => $revisionSix
        ];

        $this->container->addRevision($revisionFive);
        $this->container->addRevision($revisionSix);

        $actual = $this->container->getRevisions();

        $this->assertTrue($actual instanceof ArrayCollection);
        $this->assertEquals($expected, $actual->toArray());
    }

    /**
     * Test Add Revision
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testGetRevisionByRevisionId()
    {
        $revisionFive = new Revision();
        $revisionFive->setRevisionId(5);

        $revisionSix = new Revision();
        $revisionSix->setRevisionId(6);

        $this->container->addRevision($revisionFive);
        $this->container->addRevision($revisionSix);

        $actualRevisionFive = $this->container->getRevisionById(5);
        $this->assertEquals($revisionFive, $actualRevisionFive);

        $actualRevisionSix = $this->container->getRevisionById(6);
        $this->assertEquals($revisionSix, $actualRevisionSix);
    }

    /**
     * Test Get Last Saved Revision
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testGetLastSavedRevision()
    {
        $revisionFour = new Revision();
        $revisionFour->setRevisionId(4);
        $revisionFour->publishRevision();

        $revisionFive = new Revision();
        $revisionFive->setRevisionId(5);
        $revisionFour->publishRevision();

        $revisionSix = new Revision();
        $revisionSix->setRevisionId(6);

        $revisionSeven = new Revision();
        $revisionSeven->setRevisionId(7);

        $revisionEight = new Revision();
        $revisionEight->setRevisionId(8);

        $this->container->addRevision($revisionFour);
        $this->container->addRevision($revisionFive);
        $this->container->addRevision($revisionSix);
        $this->container->addRevision($revisionSeven);
        $this->container->addRevision($revisionEight);
        $this->container->setPublishedRevision($revisionFive);
        $this->container->setStagedRevision($revisionSix);

        $actual = $this->container->getLastSavedDraftRevision();

        $this->assertEquals($revisionEight, $actual);
    }

    /**
     * Test Get Last Saved Revision No Unpublished Pages
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testGetLastSavedRevisionNoUnPublishedRevisions()
    {
        $revisionFour = new Revision();
        $revisionFour->setRevisionId(4);
        $revisionFour->publishRevision();

        $revisionFive = new Revision();
        $revisionFive->setRevisionId(5);
        $revisionFour->publishRevision();

        $revisionSix = new Revision();
        $revisionSix->setRevisionId(6);

        $revisionSeven = new Revision();
        $revisionSeven->setRevisionId(7);
        $revisionSeven->publishRevision();

        $revisionEight = new Revision();
        $revisionEight->setRevisionId(8);
        $revisionEight->publishRevision();

        $this->container->addRevision($revisionFour);
        $this->container->addRevision($revisionFive);
        $this->container->addRevision($revisionSix);
        $this->container->addRevision($revisionSeven);
        $this->container->addRevision($revisionEight);
        $this->container->setPublishedRevision($revisionFive);
        $this->container->setStagedRevision($revisionSix);

        $actual = $this->container->getLastSavedDraftRevision();

        $this->assertEmpty($actual);
    }

    /**
     * Test Clone
     *
     * @return void
     *
     * @covers \Rcm\Entity\ContainerAbstract
     */
    public function testClone()
    {
        $site = new Site();
        $site->setSiteId(55);

        $container = [
            'containerId' => '200',
            'name' => 'containerOne',
            'author' => 'Westin Shafer',
            'createdDate' => new \DateTime('yesterday'),
            'lastPublished' => new \DateTime('yesterday'),
            'revisions' => [
                0 => [
                    'revisionId' => 100,
                    'author' => 'Westin Shafer',
                    'createdDate' => new \DateTime('yesterday'),
                    'publishedDate' => new \DateTime('yesterday'),
                    'published' => true,
                    'md5' => 'revisionMD5',
                    'instances' => [
                        0 => [
                            'pluginWrapperId' => 43,
                            'layoutContainer' => 'layoutOne',
                            'renderOrder' => 0,
                            'height' => 32,
                            'width' => 100,
                            'divFloat' => 'right',
                            'instance' => [
                                'pluginInstanceId' => 44,
                                'plugin' => 'MockPlugin',
                                'siteWide' => false,
                                'displayName' => null,
                                'instanceConfig' => [
                                    'var1' => 1,
                                    'var2' => 2
                                ],
                                'md5' => 'firstMd5'
                            ],
                        ],

                        1 => [
                            'pluginWrapperId' => 45,
                            'layoutContainer' => 'layoutTwo',
                            'renderOrder' => 1,
                            'height' => 33,
                            'width' => 101,
                            'divFloat' => 'none',
                            'instance' => [
                                'pluginInstanceId' => 46,
                                'plugin' => 'MockPlugin2',
                                'siteWide' => true,
                                'displayName' => 'TestSiteWide',
                                'instanceConfig' => [
                                    'var3' => 3,
                                    'var4' => 4
                                ],
                                'md5' => 'secondMd5'
                            ],
                        ],
                    ],
                ],

                1 => [
                    'revisionId' => 101,
                    'author' => 'Westin Shafer',
                    'createdDate' => new \DateTime('-1 month'),
                    'publishedDate' => new \DateTime('-1 month'),
                    'published' => false,
                    'md5' => 'revision2MD5',
                    'instances' => [
                        0 => [
                            'pluginWrapperId' => 47,
                            'layoutContainer' => 'layoutThree',
                            'renderOrder' => 2,
                            'height' => 33,
                            'width' => 102,
                            'divFloat' => 'right',
                            'instance' => [
                                'pluginInstanceId' => 48,
                                'plugin' => 'MockPlugin3',
                                'siteWide' => false,
                                'displayName' => null,
                                'instanceConfig' => [
                                    'var1' => 1,
                                    'var2' => 2
                                ],
                                'md5' => 'firstMd5'
                            ],
                        ],

                        1 => [
                            'pluginWrapperId' => 49,
                            'layoutContainer' => 'layoutFour',
                            'renderOrder' => 3,
                            'height' => 34,
                            'width' => 103,
                            'divFloat' => 'left',
                            'instance' => [
                                'pluginInstanceId' => 50,
                                'plugin' => 'MockPlugin4',
                                'siteWide' => true,
                                'displayName' => 'TestSiteWide2',
                                'instanceConfig' => [
                                    'var3' => 3,
                                    'var4' => 4
                                ],
                                'md5' => 'secondMd5'
                            ],
                        ],
                    ]
                ]
            ],
        ];

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
//                $wrapper->setHeight($instance['height']);
//                $wrapper->setWidth($instance['width']);
//                $wrapper->setDivFloat($instance['divFloat']);
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
        $this->assertNull($clonedContainer->getPublishedRevision());
        $this->assertCount(1, $clonedContainer->getRevisions());

        $currentRevision = $this->container->getPublishedRevision();
        $clonedCurrentRev = $clonedContainer->getStagedRevision();

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

        $this->assertFalse($clonedCurrentRev->wasPublished());

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
    }
}
