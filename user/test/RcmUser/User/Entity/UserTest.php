<?php
/**
 * UserTest.php
 *
 * TEST
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUserTest\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\User\Entity;

use RcmUser\Exception\RcmUserException;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserTest
 *
 * TEST
 *
 * PHP version 5
 *
 * @covers    \RcmUser\User\Entity\UserInterface
 */
class UserTest extends \RcmUser\Test\Zf2TestCase //\PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * getNewUser
     *
     * @param string $prefix
     *
     * @return UserInterface
     */
    protected function getNewUser($prefix = 'A')
    {
        $user = new User();
        $user->setId($prefix . '_id');
        $user->setUsername($prefix . '_username');
        $user->setPassword($prefix . '_password');
        $user->setState($prefix . '_state');
        $user->setEmail($prefix . '@example.com');
        $user->setName($prefix . '_name');
        $user->setProperties(['property1', $prefix . '_property1']);
        $user->setProperty('property2', $prefix . '_property2');

        return $user;
    }

    /**
     * testSetGet
     *
     * @covers \RcmUser\User\Entity\UserInterface
     *
     * @return void
     */
    public function testSetGet()
    {
        $user = new User();

        $value = 'id123';
        $user->setId($value);
        $this->assertEquals($value, $user->getId(), 'Setter or getter failed.');

        $value = 'usernamexxx';
        $user->setUsername($value);
        $this->assertEquals(
            $value,
            $user->getUsername(),
            'Setter or getter failed.'
        );

        $value = '';
        $user->setUsername($value);
        $this->assertNull($user->getUsername(), 'Setter or getter failed.');

        $value = 'passwordxxx';
        $user->setPassword($value);
        $this->assertEquals(
            $value,
            $user->getPassword(),
            'Setter or getter failed.'
        );

        $value = '';
        $user->setPassword($value);
        $this->assertNull($user->getPassword(), 'Setter or getter failed.');

        $value = 'statexxx';
        $user->setState($value);
        $this->assertEquals(
            $value,
            $user->getState(),
            'Setter or getter failed.'
        );

        $value = '';
        $user->setState($value);
        $this->assertNull($user->getState(), 'Setter or getter failed.');

        $value = 'xxx@example.com';
        $user->setEmail($value);
        $this->assertEquals(
            $value,
            $user->getEmail(),
            'Setter or getter failed.'
        );

        $value = '';
        $user->setEmail($value);
        $this->assertNull($user->getEmail(), 'Setter or getter failed.');

        $value = 'namesxxx';
        $user->setName($value);
        $this->assertEquals(
            $value,
            $user->getName(),
            'Setter or getter failed.'
        );

        $this->assertEquals(
            $value,
            $user->get('name', null),
            'Getter failed.'
        );

        $value = '';
        $user->setName($value);
        $this->assertNull($user->getName(), 'Setter or getter failed.');

        // cannot set or get iterator
        $hasSet = $user->set('iterator', 'something');
        $this->assertFalse($hasSet, 'Failed to stop iterator property set.');

        $this->assertNull(
            $user->get('iterator', null),
            'Getter failed to exclude.'
        );

        $value = null;
        $user->setProperties($value);
        $this->assertTrue(
            is_array($user->getProperties()),
            'Setter or getter failed.'
        );

        $pvalue = ['Y' => 'propertyYYY'];
        $value = 'propertyXXX';
        $user->setProperties($pvalue);
        $this->assertArrayHasKey(
            'Y',
            $user->getProperties(),
            'Setter or getter failed.'
        );
        $user->setProperty('X', $value);
        $this->assertEquals(
            $value,
            $user->getProperty('X'),
            'Setter or getter failed.'
        );
        $this->assertArrayHasKey(
            'Y',
            $user->getProperties(),
            'Setter or getter failed.'
        );
        $this->assertTrue(
            $user->getProperty('nope', 'not_found') === 'not_found',
            'Setter or getter failed.'
        );

        $this->assertEquals(
            'propertyYYY',
            $user->get('Y', null),
            'Getter failed.'
        );

        $badPropertyName = 'N*P#_^^^^';

        $hasSet = $user->set($badPropertyName, 'something');

        $this->assertFalse($hasSet, 'Failed to stop bad property set.');

        $hasException = false;

        try {
            $user->setProperty($badPropertyName, 'something');
        } catch (RcmUserException $e) {
            $hasException = true;
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
        }

        if (!$hasException) {
            $this->fail("Expected exception not thrown");
        }
    }

    /**
     * testInvalidUserState
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testInvalidUserState()
    {
        $user = new User();
        $user->setState("<invalid>alert('user')</invalid>");
    }

    /**
     * testIsEnabled
     *
     * @covers \RcmUser\User\Entity\UserInterface::isEnabled
     *
     * @return void
     */
    public function testIsEnabled()
    {

        $user = new User();
        $user->setState(UserInterface::STATE_DISABLED);

        $this->assertFalse($user->isEnabled(), 'State check failed.');
    }

    /**
     * testArrayIterator
     *
     * @covers \RcmUser\User\Entity\UserInterface::getIterator
     *
     * @return void
     */
    public function testArrayIterator()
    {
        $userA = $this->getNewUser('A');
        $iter = $userA->getIterator();
        $userArr = iterator_to_array($userA);
        $userArr2 = iterator_to_array($iter);

        $this->assertTrue($userArr == $userArr2, 'Iterator failed work.');

        $this->assertTrue(is_array($userArr), 'Iterator failed work.');

        $this->assertArrayHasKey(
            'id',
            $userArr,
            'Iterator did not populate correctly.'
        );
    }

    /**
     * testPopulate
     *
     * @covers \RcmUser\User\Entity\UserInterface::populate
     * @covers \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testPopulate()
    {
        $userA = $this->getNewUser('A');
        $userB = $this->getNewUser('B');

        $userC = $this->getNewUser('C');
        $userArrC = iterator_to_array($userC);
        $userD = 'Some wrong user format';

        $userA->populate($userB);

        $this->assertEquals(
            $userA,
            $userB,
            'Populate from object not successful'
        );

        $userA->populate($userArrC);

        $this->assertEquals(
            $userA,
            $userC,
            'Populate from array not successful'
        );


        try {
            $userA->populate($userD);
        } catch (\RcmUser\Exception\RcmUserException $e) {
            //$this->assertEquals("Exception Code",$e->getMessage());
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }

    /**
     * testMerge
     *
     * @covers \RcmUser\User\Entity\UserInterface::merge
     *
     * @return void
     */
    public function testMerge()
    {
        $userA = new User();
        $userB = $this->getNewUser('B');
        $userC = $this->getNewUser('C');

        $userA->merge($userB);

        $this->assertEquals(
            $userA,
            $userB,
            'Merge to empty object not successful'
        );

        $userA->merge($userC);

        $this->assertNotEquals(
            $userA,
            $userC,
            'Merge to populated object not successful'
        );

        $userA->setId(null);

        $userA->merge($userC);

        $this->assertNotEquals(
            $userA,
            $userC,
            'Merge to populated single property not successful'
        );
        $this->assertEquals(
            $userA->getId(),
            $userC->getId(),
            'Merge to single property not successful'
        );
    }

    /**
     * testJsonSerialize
     *
     * @covers \RcmUser\User\Entity\UserInterface::jsonSerialize
     *
     * @return void
     */
    public function testJsonSerialize()
    {
        $userA = $this->getNewUser('A');

        $userAjson = json_encode($userA);

        $this->assertJson($userAjson, 'User not converted to JSON.');
    }
}
