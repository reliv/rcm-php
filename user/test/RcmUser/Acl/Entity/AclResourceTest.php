<?php
/**
 * AclResourceTest.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Acl\Entity;

use RcmUser\Acl\Entity\AclResource;
use RcmUser\Exception\RcmUserException;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class AclResourceTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Acl\Entity\AclResource
 */
class AclResourceTest extends Zf2TestCase
{
    /**
     * getAclResource
     *
     * @param       $resourceId
     * @param null  $parentResourceId
     * @param array $privileges
     *
     * @return AclResource
     */
    public function getAclResource(
        $resourceId,
        $parentResourceId = null,
        $privileges = []
    ) {

        return new AclResource(
            $resourceId,
            $parentResourceId = null,
            $privileges = []
        );
    }

    /**
     * getAclResourceArray
     *
     * @param $resourceId
     *
     * @return array
     */
    public function getAclResourceArray(
        $resourceId
    ) {

        return [
            'resourceId' => $resourceId,
            'providerId' => 'providerId',
            'parentResourceId' => 'parentresourceid',
            'privileges' => 'privileges',
            'name' => 'name',
            'description' => 'description',
        ];
    }

    /**
     * testSetGet
     *
     * @return void
     */
    public function testSetGet()
    {
        $resourceIds = [
            'testid0',
            'testid1',
        ];
        $aclResourceArray = $this->getAclResourceArray($resourceIds[0]);

        $aclResource = $this->getAclResource($resourceIds[0]);
        $aclParentResource = $this->getAclResource('parentresourceid2');

        $this->assertEquals(
            $resourceIds[0],
            $aclResource->getResourceId(),
            'Set or get failed.'
        );

        $aclResource->setResourceId($resourceIds[1]);
        $this->assertEquals(
            $resourceIds[1],
            $aclResource->getResourceId(),
            'Set or get failed.'
        );

        $aclResource->setProviderId($aclResourceArray['providerId']);
        $this->assertEquals(
            $aclResourceArray['providerId'],
            $aclResource->getProviderId(),
            'Set or get failed.'
        );

        $aclResource->setParentResourceId(
            $aclResourceArray['parentResourceId']
        );
        $this->assertEquals(
            $aclResourceArray['parentResourceId'],
            $aclResource->getParentResourceId(),
            'Set or get failed.'
        );

        $aclResource->setParentResource($aclParentResource);
        $this->assertEquals(
            $aclParentResource,
            $aclResource->getParentResource(),
            'Set or get failed.'
        );

        $aclResource->setPrivileges($aclResourceArray['privileges']);
        $this->assertEquals(
            $aclResourceArray['privileges'],
            $aclResource->getPrivileges(),
            'Set or get failed.'
        );

        $aclResource->setName($aclResourceArray['name']);
        $this->assertEquals(
            $aclResourceArray['name'],
            $aclResource->getName(),
            'Set or get failed.'
        );

        $aclResource->setDescription($aclResourceArray['description']);
        $this->assertEquals(
            $aclResourceArray['description'],
            $aclResource->getDescription(),
            'Set or get failed.'
        );
    }

    /**
     * testIsValidResourceId
     *
     * @return void
     */
    public function testIsValidResourceId()
    {
        $aclResource = $this->getAclResource('123');

        $badResourceId = "!inv@lid$";

        $isvalid = $aclResource->isValidResourceId($badResourceId);

        $this->assertFalse($isvalid, 'Resource not valid, but said it was.');

        try {
            $aclResource->setResourceId($badResourceId);
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }

    /**
     * testSetParentResourceId
     *
     * @return void
     */
    public function testSetParentResourceId()
    {
        $aclResource = $this->getAclResource('123');
        $aclResource2 = $this->getAclResource('432');

        $aclResource->setParentResource($aclResource2);

        $aclResource->setParentResourceId('555');

        $this->assertEquals(
            '555',
            $aclResource->getParentResource(),
            'Setting a parent resourceId should clear parent resource object.'
        );

        $badResourceId = "!inv@lid$";

        try {
            $aclResource->setParentResourceId($badResourceId);
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }

    /**
     * testPopulate
     *
     * @return void
     */
    public function testPopulate()
    {
        $aclResourceArray = $this->getAclResourceArray('321');
        $aclResource = $this->getAclResource('123');
        $aclResource2 = $this->getAclResource('222');
        $aclResource3 = $this->getAclResource('333');

        $aclResource->populate($aclResourceArray);

        $this->assertEquals(
            $aclResourceArray['resourceId'],
            $aclResource->getResourceId(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResourceArray['providerId'],
            $aclResource->getProviderId(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResourceArray['providerId'],
            $aclResource->getProviderId(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResourceArray['parentResourceId'],
            $aclResource->getParentResourceId(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResourceArray['privileges'],
            $aclResource->getPrivileges(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResourceArray['name'],
            $aclResource->getName(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResourceArray['description'],
            $aclResource->getDescription(),
            'Populate failed.'
        );

        //
        $aclResource2->populate($aclResource);

        $this->assertEquals(
            $aclResource->getResourceId(),
            $aclResource2->getResourceId(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResource->getProviderId(),
            $aclResource2->getProviderId(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResource->getProviderId(),
            $aclResource2->getProviderId(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResource->getParentResourceId(),
            $aclResource2->getParentResourceId(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResource->getPrivileges(),
            $aclResource2->getPrivileges(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResource->getName(),
            $aclResource2->getName(),
            'Populate failed.'
        );
        $this->assertEquals(
            $aclResource->getDescription(),
            $aclResource2->getDescription(),
            'Populate failed.'
        );

        // exception
        try {
            $aclResource3->populate('NOPE');
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }

    /**
     * testJsonSerialize
     *
     * @return void
     */
    public function testJsonSerialize()
    {
        $aclResource = $this->getAclResource('123');

        $stdObj = $aclResource->jsonSerialize();

        $this->assertInstanceOf(
            '\stdClass',
            $stdObj,
            'jsonSerialize did not return std class.'
        );

        $json = json_encode($aclResource);

        $this->assertJson($json, 'Could not encode as JSON.');
    }
}
