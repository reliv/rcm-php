<?php
/**
 * LinksTest.php
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
use RcmUser\User\Entity\Links;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class LinksTest
 *
 * LinksTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\User\Entity\Links
 */
class LinksTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $link = new Link();

        $link->setHelp('TESThelp');
        $link->setTitle('TESTtitle');
        $link->setType('TESTtype');
        $link->setUrl('TESTurl');

        $linksData = [$link];
        $links = new Links($linksData);

        $this->assertEquals(
            $link,
            $links->getLinks()[0]
        );

        $links->addLink($link);

        $this->assertEquals(
            2,
            count($links->getLinks())
        );

        $this->assertInstanceOf(
            '\ArrayIterator',
            $links->getIterator()
        );

        $this->assertJson(
            json_encode($links)
        );
    }
}
