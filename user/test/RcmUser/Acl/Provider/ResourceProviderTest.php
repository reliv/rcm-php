<?php
/**
 * ResourceProviderTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Provider
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Acl\Provider;

use RcmUser\Acl\Provider\ResourceProvider;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class ResourceProviderTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Provider
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Acl\Provider\ResourceProvider
 */
class ResourceProviderTest extends Zf2TestCase
{
    /**
     * testSetGet
     *
     * @return void
     */
    public function testSetGet()
    {
        $resources = [
            '1' => 'some',
            '2' => 'res'
        ];
        $providerId = 'someprovider';
        $resourceProvider = new ResourceProvider($resources);

        $resourceProvider->setProviderId($providerId);

        $this->assertEquals(
            $providerId,
            $resourceProvider->getProviderId(),
            'Setter or getter failed.'
        );

        $this->assertEquals(
            $resources,
            $resourceProvider->getResources(),
            'Setter or getter failed.'
        );

        $this->assertEquals(
            $resources['1'],
            $resourceProvider->getResource('1'),
            'Setter or getter failed.'
        );

        $this->assertNull(
            $resourceProvider->getResource('NOPE'),
            'Unset reaource should return null'
        );
    }
}
