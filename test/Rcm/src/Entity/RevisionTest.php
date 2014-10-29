<?php
/**
 * Unit Test for the Revision Entity
 *
 * This file contains the unit test for the Revision Entity
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
        $this->revision = new Revision();
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
     * Test Get and Set Created Date
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     */
    public function testGetAndSetCreatedDate()
    {
        $createdDate = new \DateTime('2014-Apr-20');

        $this->revision->setCreatedDate($createdDate);

        $actual = $this->revision->getCreatedDate();

        $this->assertEquals($createdDate, $actual);
    }

    /**
     * Test Created Date Only Accepts a DateTime object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetCreatedDateOnlyAcceptsDateTime()
    {
        $this->revision->setCreatedDate(time());
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
        $createdDate = new \DateTime('2014-Apr-20');

        $this->revision->setPublishedDate($createdDate);

        $actual = $this->revision->getPublishedDate();

        $this->assertEquals($createdDate, $actual);
    }

    /**
     * Test Created Date Only Accepts a DateTime object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetPublishDateOnlyAcceptsDateTime()
    {
        $this->revision->setCreatedDate(time());
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
        $wrapperOne = new PluginWrapper();
        $wrapperOne->setPluginWrapperId(44);

        $wrapperTwo = new PluginWrapper();
        $wrapperTwo->setPluginWrapperId(45);

        $expected = array(
            $wrapperOne,
            $wrapperTwo
        );

        $this->revision->addPluginWrapper($wrapperOne);
        $this->revision->addPluginWrapper($wrapperTwo);

        $actual = $this->revision->getPluginWrappers();

        $this->assertTrue(is_array($actual));
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test Add Plugin Wrapper Only Accepts a PluginWrapper object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testAddPluginWrapperOnlyAcceptsPluginWrapper()
    {
        $this->revision->addPluginWrapper(time());
    }


    /**
     * Test Remove Plugin Wrapper
     *
     * @return void
     *
     * @covers \Rcm\Entity\Revision
     */
    public function testRemovePluginWrapper()
    {
        $wrapperOne = new PluginWrapper();
        $wrapperOne->setPluginWrapperId(44);

        $wrapperTwo = new PluginWrapper();
        $wrapperTwo->setPluginWrapperId(45);

        $wrapperThree = new PluginWrapper();
        $wrapperThree->setPluginWrapperId(46);

        $expected = array(
            $wrapperTwo,
            $wrapperThree
        );

        $this->revision->addPluginWrapper($wrapperOne);
        $this->revision->addPluginWrapper($wrapperTwo);
        $this->revision->addPluginWrapper($wrapperThree);

        $this->revision->removeInstance($wrapperOne);

        $actual = $this->revision->getPluginWrappers();

        $this->assertTrue(is_array($actual));

        $reIndexedArray = array_values($actual);

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
        $revision = array(
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
            )
        );

        $this->revision->setRevisionId($revision['revisionId']);
        $this->revision->setAuthor($revision['author']);
        $this->revision->setCreatedDate($revision['createdDate']);
        $this->revision->publishRevision();
        $this->revision->setPublishedDate($revision['publishedDate']);
        $this->revision->setMd5($revision['md5']);

        foreach ($revision['instances'] as $instance) {
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

            $this->revision->addPluginWrapper($wrapper);
        }

        $clonedRevision = clone $this->revision;

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

        $this->assertNotEquals(
            $this->revision->getCreatedDate(),
            $clonedRevision->getCreatedDate()
        );

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
            if(!$clonedWrapper->getInstance()->isSiteWide()) {
                $this->assertNull($clonedWrapper->getInstance()->getInstanceId());
            } else {
                $this->assertNotNull($clonedWrapper->getInstance()->getInstanceId());
            }
        }
    }
}
 