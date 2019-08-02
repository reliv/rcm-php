<?php
/**
 * DoctrineMapperTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Db;

require_once __DIR__ . '/../../Zf2TestCase.php';

use RcmUser\Db\DoctrineMapper;

/**
 * Class DoctrineMapperTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Db\DoctrineMapper
 */
class DoctrineMapperTest extends \PHPUnit_Framework_TestCase
{

    public function testSetGet()
    {

        $em = $this->getMockBuilder(
            '\Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $docMapr = new DoctrineMapper();

        $entCls = '\some\class';
        $docMapr->setEntityClass($entCls);

        $this->assertEquals(
            $entCls,
            $docMapr->getEntityClass(),
            'Set or Get failed.'
        );

        $docMapr->setEntityManager($em);

        $this->assertEquals(
            $em,
            $docMapr->getEntityManager(),
            'Set or Get failed.'
        );
    }
}
