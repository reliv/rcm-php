<?php

namespace RcmUser\Test\User\Service;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;
use RcmUser\User\Service\UserPropertyService;
use Zend\EventManager\EventManagerInterface;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserPropertyServiceTest
 *
 * TEST
 *
 * PHP version 5
 *
 * @covers \RcmUser\User\Service\UserPropertyService
 */
class UserPropertyServiceTest extends Zf2TestCase
{

    public $userPropertyService;


    public function getUserPropertyService()
    {
        if (!isset($this->userPropertyService)) {
            $this->buildUserPropertyService();
        }

        return $this->userPropertyService;
    }

    public function buildUserPropertyService()
    {
        $user = new User();
        $user->setId('123');

        /** @var EventManagerInterface $this->eventManager */
        $this->eventManager = $this->getMockBuilder(
            '\Zend\EventManager\EventManagerInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->userPropertyService = new UserPropertyService($this->eventManager);
    }

    public function testGetUserProperty()
    {
        $key = 'propertyX';
        $value = 'XXXXX';
        $user = new User();
        $user->setId('123');
        $user->setProperty($key, 'XXXXX');

        $newValue = $this->getUserPropertyService()->getUserProperty(
            $user,
            $key
        );

        $this->assertEquals(
            $value,
            $newValue,
            'Property value did not come back.'
        );
    }
}
