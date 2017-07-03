<?php

namespace RcmTest\Entity;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Revision;

/**
 * Unit Test for the Revision Entity
 *
 * Unit Test for the Revision Entity
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class RevisionTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Entity\Revision */
    protected $revision;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->revision = new Revision('user123');
    }

    /**
     * Test Get and Set Redirect Id
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     */
    public function testGetAndSetRevisionId()
    {
        $id = 99;

        $this->revision->setRevisionId($id);

        $actual = $this->revision->getRevisionId();

        $this->assertEquals($id, $actual);
    }

    /**
     * Test Get and Set Author
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     */
    public function testGetAndSetAuthor()
    {
        $author = 'Westin Shafer';

        $this->revision->setAuthor($author);

        $actual = $this->revision->getAuthor();

        $this->assertEquals($author, $actual);
    }

    /**
     * Test Get Created Date
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     */
    public function testGetCreatedDate()
    {
        $actual = $this->revision->getCreatedDate();

        $this->assertInstanceOf(\DateTime::class, $actual);
    }

    /**
     * Test Get and Set Published Date
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     */
    public function testGetAndSetPublishedDate()
    {
        $date = new \DateTime('2014-Apr-20');

        $this->revision->setPublishedDate($date);

        $actual = $this->revision->getPublishedDate();

        $this->assertEquals($date, $actual);
    }

    /**
     * Test Get and Add Plugin Wrappers
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     */
    public function testGetAndAddPluginWrappers()
    {
        $wrapperOne = new PluginWrapper('user123');
        $wrapperOne->setPluginWrapperId(44);

        $wrapperTwo = new PluginWrapper('user123');
        $wrapperTwo->setPluginWrapperId(45);

        $expected = [
            $wrapperOne,
            $wrapperTwo
        ];

        $this->revision->addPluginWrapper($wrapperOne);
        $this->revision->addPluginWrapper($wrapperTwo);

        $actual = $this->revision->getPluginWrappers();

        $this->assertEquals($expected, $actual->toArray());
    }

    /**
     * Test Add Plugin Wrapper Only Accepts a PluginWrapper object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     * @expectedException \TypeError
     */
//    public function testAddPluginWrapperOnlyAcceptsPluginWrapper()
//    {
//        $this->revision->addPluginWrapper(time());
//    }

    /**
     * Test Remove Plugin Wrapper
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     */
    public function testRemovePluginWrapper()
    {
        $wrapperOne = new PluginWrapper('user123');
        $wrapperOne->setPluginWrapperId(44);

        $wrapperTwo = new PluginWrapper('user123');
        $wrapperTwo->setPluginWrapperId(45);

        $wrapperThree = new PluginWrapper('user123');
        $wrapperThree->setPluginWrapperId(46);

        $expected = [
            $wrapperTwo,
            $wrapperThree
        ];

        $this->revision->addPluginWrapper($wrapperOne);
        $this->revision->addPluginWrapper($wrapperTwo);
        $this->revision->addPluginWrapper($wrapperThree);

        $this->revision->removeInstance($wrapperOne);

        $actual = $this->revision->getPluginWrappers();

        $reIndexedArray = array_values($actual->toArray());

        $this->assertEquals($expected, $reIndexedArray);
    }

    /**
     * Test Publish Revision
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     */
    public function testPublishRevision()
    {
        $this->revision->publishRevision();

        $publishDate = $this->revision->getPublishedDate();
        $isPublished = $this->revision->wasPublished();

        $this->assertTrue($publishDate instanceof \DateTime);

        //Expected Published Date
        $expectedPublishDate = date("M-d-Y G");

        $this->assertEquals(
            $expectedPublishDate,
            $publishDate->format("M-d-Y G")
        );

        $this->assertTrue($isPublished);
    }

    /**
     * Test Get and Set Md5
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     */
    public function testGetAndSetMd5()
    {

        $md5 = md5('This is my MD5 string to test');

        $this->revision->setMd5($md5);

        $actual = $this->revision->getMd5();

        $this->assertEquals($md5, $actual);
    }

    /**
     * Test Clone
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     */
    public function testClone()
    {
        $revision = [
            'revisionId' => 100,
            'author' => 'Westin Shafer',
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
                        'siteWide' => false, // @deprecated <deprecated-site-wide-plugin>
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
                        'siteWide' => true, // @deprecated <deprecated-site-wide-plugin>
                        'displayName' => 'TestSiteWide',
                        'instanceConfig' => [
                            'var3' => 3,
                            'var4' => 4
                        ],
                        'md5' => 'secondMd5'
                    ],
                ],
            ]
        ];

        $this->revision->setRevisionId($revision['revisionId']);
        $this->revision->setAuthor($revision['author']);
        $this->revision->publishRevision();
        $this->revision->setPublishedDate($revision['publishedDate']);
        $this->revision->setMd5($revision['md5']);

        foreach ($revision['instances'] as $instance) {
            $plugin = new PluginInstance('user123');
            $plugin->setInstanceId($instance['instance']['pluginInstanceId']);
            $plugin->setPlugin($instance['instance']['plugin']);

            // @deprecated <deprecated-site-wide-plugin>
            if ($instance['instance']['siteWide']) {
                $plugin->setSiteWide();
            }

            $plugin->setDisplayName($instance['instance']['displayName']);
            $plugin->setInstanceConfig($instance['instance']['instanceConfig']);
            $plugin->setMd5($instance['instance']['md5']);

            $wrapper = new PluginWrapper('user123');
            $wrapper->setPluginWrapperId($instance['pluginWrapperId']);
            $wrapper->setLayoutContainer($instance['layoutContainer']);
            $wrapper->setRenderOrderNumber($instance['renderOrder']);
            $wrapper->setHeight($instance['height']);
            $wrapper->setWidth($instance['width']);
            $wrapper->setDivFloat($instance['divFloat']);
            $wrapper->setInstance($plugin);

            $this->revision->addPluginWrapper($wrapper);
        }

        $clonedRevision = $this->revision->newInstance('user123');

        /* Test Revision */
        $this->assertNotEquals(
            $this->revision->getRevisionId(),
            $clonedRevision->getRevisionId()
        );

        $this->assertNull($clonedRevision->getRevisionId());

        $this->assertEquals(
            $this->revision->getAuthor(),
            $clonedRevision->getAuthor()
        );

        // @todo Is this a valid test? should cloning a container create revision clones?
        //$this->assertNotEquals(
        //    $currentRevision->getCreatedDate(),
        //    $clonedCurrentRev->getCreatedDate()
        //);

        $this->assertNotEquals(
            $this->revision->getPublishedDate(),
            $clonedRevision->getPublishedDate()
        );

        $this->assertTrue($this->revision->wasPublished());

        $this->assertEquals(
            $this->revision->getMd5(),
            $clonedRevision->getMd5()
        );

        $clonedWrappers = $clonedRevision->getPluginWrappers();
        $revisionWrappers = $this->revision->getPluginWrappers();

        $this->assertNotEquals($revisionWrappers, $clonedWrappers);

        /** @var \Rcm\Entity\PluginWrapper $clonedWrapper */
        foreach ($clonedWrappers as $clonedWrapper) {
            // @deprecated <deprecated-site-wide-plugin>
            //if (!$clonedWrapper->getInstance()->isSiteWide()) {
                $this->assertNull($clonedWrapper->getInstance()->getInstanceId());
            //} else {
            //    $this->assertNotNull($clonedWrapper->getInstance()->getInstanceId());
            //}
        }
    }
}
