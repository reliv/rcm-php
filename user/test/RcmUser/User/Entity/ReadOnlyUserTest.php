<?php

namespace RcmUser\Test\User\Entity;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\ReadOnlyUser;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ReadOnlyUserTest extends Zf2TestCase
{

    /**
     * buildReadOnlyUser
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::
     *
     * @return ReadOnlyUser
     */
    public function buildReadOnlyUser()
    {
        $user = new User();
        $user->setId('id');
        $user->setUsername('username');
        $user->setPassword('password');
        $user->setProperties(['A' => 'something']);

        return new ReadOnlyUser($user);
    }

    /**
     * testConstruct
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::__construct
     * @covers \RcmUser\User\Entity\ReadOnlyUser::populate
     *
     * @return void
     */
    public function testConstruct()
    {
        $user = new User();
        $user->setId('id');
        $user->setUsername('username');
        $user->setPassword('password');
        $user->setState('disabled');
        $user->setName('name');
        $user->setEmail('test@example.com');
        $user->setProperties(['A' => 'something']);

        return new ReadOnlyUser($user);
    }

    /**
     * testSetId
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::setID
     * @covers \RcmUser\Exception\RcmUserReadOnlyException
     * @expectedException \RcmUser\Exception\RcmUserReadOnlyException
     *
     * @return void
     */
    public function testSetId()
    {
        $roUser = $this->buildReadOnlyUser();
        $roUser->setId('234');
    }

    /**
     * testSetUsername
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::setUsername
     * @covers \RcmUser\Exception\RcmUserReadOnlyException
     * @expectedException \RcmUser\Exception\RcmUserReadOnlyException
     *
     * @return void
     */
    public function testSetUsername()
    {
        $roUser = $this->buildReadOnlyUser();
        $roUser->setUsername('234');
    }

    /**
     * testSetPassword
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::setPassword
     * @covers \RcmUser\Exception\RcmUserReadOnlyException
     * @expectedException \RcmUser\Exception\RcmUserReadOnlyException
     *
     * @return void
     */
    public function testSetPassword()
    {
        $roUser = $this->buildReadOnlyUser();
        $roUser->setPassword('234');
    }

    /**
     * testSetState
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::setState
     * @covers \RcmUser\Exception\RcmUserReadOnlyException
     * @expectedException \RcmUser\Exception\RcmUserReadOnlyException
     *
     * @return void
     */
    public function testSetState()
    {
        $roUser = $this->buildReadOnlyUser();
        $roUser->setState('234');
    }

    /**
     * testSetEmail
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::setEmail
     * @covers \RcmUser\Exception\RcmUserReadOnlyException
     * @expectedException \RcmUser\Exception\RcmUserReadOnlyException
     *
     * @return void
     */
    public function testSetEmail()
    {
        $roUser = $this->buildReadOnlyUser();
        $roUser->setEmail('234@example.com');
    }

    /**
     * testSetName
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::setName
     * @covers \RcmUser\Exception\RcmUserReadOnlyException
     * @expectedException \RcmUser\Exception\RcmUserReadOnlyException
     *
     * @return void
     */
    public function testSetName()
    {
        $roUser = $this->buildReadOnlyUser();
        $roUser->setName('noope');
    }

    /**
     * testSetProperties
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::setProperties
     * @covers \RcmUser\Exception\RcmUserReadOnlyException
     * @expectedException \RcmUser\Exception\RcmUserReadOnlyException
     *
     * @return void
     */
    public function testSetProperties()
    {
        $roUser = $this->buildReadOnlyUser();
        $roUser->setProperties(['B' => '234']);
    }

    /**
     * testSetProperty
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::setProperty
     * @covers \RcmUser\Exception\RcmUserReadOnlyException
     * @expectedException \RcmUser\Exception\RcmUserReadOnlyException
     *
     * @return void
     */
    public function testSetProperty()
    {
        $roUser = $this->buildReadOnlyUser();
        $roUser->setProperty('B', '234');
    }

    /**
     * testPopulate
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::populate
     * @covers \RcmUser\Exception\RcmUserReadOnlyException
     * @expectedException \RcmUser\Exception\RcmUserReadOnlyException
     *
     * @return void
     */
    public function testPopulate()
    {
        $user = new User('4343');
        $roUser = $this->buildReadOnlyUser();
        $roUser->populate($user);
    }

    /**
     * testPopulate
     *
     * @covers \RcmUser\User\Entity\ReadOnlyUser::merge
     * @covers \RcmUser\Exception\RcmUserReadOnlyException
     * @expectedException \RcmUser\Exception\RcmUserReadOnlyException
     *
     * @return void
     */
    public function testMerge()
    {
        $user = new User('4343');
        $roUser = $this->buildReadOnlyUser();
        $roUser->merge($user);
    }
}
