<?php

namespace RcmUser\Test\Provider;

use RcmUser\Acl\Entity\AclResource;
use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../Zf2TestCase.php';

/**
 * Class RcmUserAclResourceProviderTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Provider
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Provider\RcmUserAclResourceProvider
 */
class RcmUserAclResourceProviderTest extends Zf2TestCase
{

    /**
     * @var $rcmUserAclResourceProvider \RcmUser\Provider\RcmUserAclResourceProvider
     */
    public $rcmUserAclResourceProvider;

    public function setUp()
    {
        $this->rcmUserAclResourceProvider = new RcmUserAclResourceProvider();
    }

    /**
     * testBuildRcmUserAclResourceProvider
     *
     * @return RcmUserAclResourceProvider
     */
    public function testBuildRcmUserAclResourceProvider()
    {
        $this->rcmUserAclResourceProvider = new RcmUserAclResourceProvider();

        $resources = $this->rcmUserAclResourceProvider->getResources();

        $this->assertTrue(is_array($resources));
    }

    /**
     * testGetSet
     *
     * @return void
     */
    public function testGetSet()
    {

        $providerId = 'test';

        $this->rcmUserAclResourceProvider->setProviderId($providerId);

        $getProviderId = $this->rcmUserAclResourceProvider->getProviderId();

        $this->assertEquals($providerId, $getProviderId, 'Set or Get failed.');
    }

    /**
     * testGetResources
     *
     * @covers \RcmUser\Provider\RcmUserAclResourceProvider::getResources
     *
     * @return void
     */
    public function testGetResources()
    {

        $resources = $this->rcmUserAclResourceProvider->getResources();

        $this->assertTrue(
            is_array($resources),
            'Array of resources not returned.'
        );
    }

    /**
     * testGetResource
     *
     * @covers \RcmUser\Provider\RcmUserAclResourceProvider::getResource
     *
     * @return void
     */
    public function testGetResource()
    {
        $resource = $this->rcmUserAclResourceProvider->getResource(
            RcmUserAclResourceProvider::RESOURCE_ID_ROOT
        );

        $this->assertTrue(
            ($resource instanceof AclResource),
            'AclResource not returned.'
        );
    }
}
