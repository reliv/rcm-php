<?php
/**
 * LinkTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\User\Entity;

use RcmUser\User\Entity\Link;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class LinkTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\User\Entity\Link
 */
class LinkTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $link = new Link();

        $link->setHelp('TESThelp');
        $link->setTitle('TESTtitle');
        $link->setType('TESTtype');
        $link->setUrl('TESTurl');

        $linkData = [
            'help' => 'TEST2help',
            'title' => 'TEST2title',
            'type' => 'TEST2type',
            'url' => 'TEST2url'
        ];

        $this->assertEquals(
            'TESThelp',
            $link->getHelp()
        );
        $this->assertEquals(
            'TESTtitle',
            $link->getTitle()
        );

        $this->assertEquals(
            'TESTtype',
            $link->getType()
        );

        $this->assertEquals(
            'TESTurl',
            $link->getUrl()
        );

        $this->assertJson(
            json_encode($link)
        );

        $link2 = new Link();

        $link2->populate($linkData);

        $this->assertEquals(
            $linkData['url'],
            $link2->getUrl()
        );

        $link2->populate($link);
        $this->assertEquals(
            $link->getUrl(),
            $link2->getUrl()
        );

        try {
            $link2->populate('nope');
        } catch (\RcmUser\Exception\RcmUserException $e) {
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }
}
