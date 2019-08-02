<?php

namespace RcmUser\Test;

use RcmUser\Config\Config;

/**
 * Class Zf2TestCase
 *
 * Zf2TestCase
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Zf2TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface $mockServiceLocator
     */
    public $mockServiceLocator;

    /**
     * @var array $mockServices
     */
    public $mockServices;

    /**
     * @var \Zend\Mvc\Controller\ControllerManager $mockControllerManager
     */
    public $mockControllerManager;

    /**
     * @var \Zend\View\HelperPluginManager $mockHelperPluginManager
     */
    public $mockHelperPluginManager;

    /**
     * @var array $valueMap
     */
    public $valueMap;

    /**
     * @var array $mockRcmUserConfig
     */
    public $mockRcmUserConfig
        = [
            'htmlAssets' => [
                'js' => [
                    '/test.js',
                ],

                'css' => [
                    '/test.css',
                ],
            ],
            'User\Config' => [

                'ValidUserStates' => [
                    'disabled', // **REQUIRED for User entity**
                    'enabled',
                ],
                'DefaultUserState' => 'enabled',
                'Encryptor.passwordCost' => 14,
                'InputFilter' => [

                    'username' => [
                        'name' => 'username',
                        'required' => true,
                        'filters' => [
                            ['name' => 'StringTrim'],
                        ],
                        'validators' => [
                            [
                                'name' => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min' => 3,
                                    'max' => 100,
                                ],
                            ],
                        ],
                    ],
                    'password' => [
                        'name' => 'password',
                        'required' => true,
                        'filters' => [],
                        'validators' => [
                            [
                                'name' => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min' => 6,
                                    'max' => 100,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'Auth\Config' => [
                'ObfuscatePasswordOnAuth' => true,
            ],
            'Acl\Config' => [

                'DefaultGuestRoleIds' => ['guest'],
                'DefaultUserRoleIds' => ['user'],
                'SuperAdminRoleId' => 'admin',
                'GuestRoleId' => 'guest',
                'ResourceProviders' => [

                    'RcmUser' => \RcmUser\Provider\RcmUserAclResourceProvider::class,
                    'RcmUser.TEST' => [
                        'TESTONE' => [
                            'resourceId' => 'TESTONE',
                            'parentResourceId' => null,
                            'privileges' => [
                                'read',
                                'update',
                                'create',
                                'delete',
                                'execute',
                            ],
                            'name' => 'Test resource one.',
                            'description' => 'test resource one desc.',
                        ],
                        'TESTTWO' => [
                            'resourceId' => 'TESTTWO',
                            'parentResourceId' => 'TESTONE',
                            'privileges' => [
                                'read',
                                'update',
                                'create',
                                'delete',
                                'execute',
                            ],
                            'name' => 'Test resource two.',
                            'description' => 'test resource two desc.',
                        ]
                    ],
                ],
            ],
        ];

    /**
     * getMockServices
     *
     * @return array
     */
    public function getMockServices()
    {
        if (isset($this->mockServices)) {
            return $this->mockServices;
        }

        $this->mockServices = [

            \RcmUser\Config\Config::class =>
                new \RcmUser\Config\Config($this->mockRcmUserConfig),
            \RcmUser\User\Config::class =>
                new \RcmUser\User\Config($this->mockRcmUserConfig['User\Config']),
            \RcmUser\Authentication\Config::class =>
                new \RcmUser\Authentication\Config($this->mockRcmUserConfig['Auth\Config']),
            \RcmUser\Acl\Config::class =>
                new \RcmUser\Acl\Config($this->mockRcmUserConfig['Acl\Config']),
            \RcmUser\User\Service\UserDataService::class =>
                $this->getMockBuilder(
                    \RcmUser\User\Service\UserDataService::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\User\Service\UserPropertyService::class =>
                $this->getMockBuilder(
                    \RcmUser\User\Service\UserPropertyService::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\User\Service\UserRoleService::class =>
                $this->getMockBuilder(
                    \RcmUser\User\Service\UserRoleService::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\User\Db\UserDataMapper::class =>
                $this->getMockBuilder(
                    \RcmUser\User\Db\UserDataMapperInterface::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\User\Db\UserRolesDataMapper::class =>
                $this->getMockBuilder(
                    \RcmUser\User\Db\UserRolesDataMapperInterface::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\User\Data\UserValidator::class =>
                $this->getMockBuilder(
                    \RcmUser\User\Data\UserValidatorInterface::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\User\Password\Password::class =>
                $this->getMockBuilder(
                    \RcmUser\User\Password\Password::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\User\Data\UserDataPreparer::class =>
                $this->getMockBuilder(
                    \RcmUser\User\Data\UserDataPreparerInterface::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\User\Event\UserDataServiceListeners::class =>
                $this->getMockBuilder(
                    \RcmUser\User\Event\AbstractUserDataServiceListeners::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\User\Event\UserRoleDataServiceListeners::class =>
                $this->getMockBuilder(
                    \RcmUser\User\Event\AbstractUserDataServiceListeners::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Authentication\Service\UserAuthenticationService::class =>
                $this->getMockBuilder(
                    \RcmUser\Authentication\Service\UserAuthenticationService::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Authentication\Adapter\Adapter::class =>
                $this->getMockBuilder(
                    \RcmUser\Authentication\Adapter\UserAdapter::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Authentication\Storage\Session::class =>
                $this->getMockBuilder(
                    \RcmUser\Authentication\Storage\UserSession::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Authentication\Service\AuthenticationService::class =>
                $this->getMockBuilder(
                    \RcmUser\Authentication\Service\AuthenticationService::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Authentication\Event\UserAuthenticationServiceListeners::class =>
                $this->getMockBuilder(
                    \RcmUser\Authentication\Event\UserAuthenticationServiceListeners::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Acl\Service\AclResourceService::class =>
                $this->getMockBuilder(
                    \RcmUser\Acl\Service\AclResourceService::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Acl\Service\AuthorizeService::class =>
                $this->getMockBuilder(
                    \RcmUser\Acl\Service\AuthorizeService::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Acl\Db\AclRoleDataMapper::class =>
                $this->getMockBuilder(
                    \RcmUser\Acl\Db\AclRoleDataMapperInterface::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Acl\Db\AclRuleDataMapper::class =>
                $this->getMockBuilder(
                    \RcmUser\Acl\Db\AclRuleDataMapperInterface::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Acl\Service\AclDataService::class =>
                $this->getMockBuilder(
                    \RcmUser\Acl\Service\AclDataService::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Service\RcmUserService::class =>
                $this->getMockBuilder(
                    \RcmUser\Service\RcmUserService::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Provider\RcmUserAclResourceProvider::class =>
                $this->getMockBuilder(
                    \RcmUser\Provider\RcmUserAclResourceProvider::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Event\ListenerCollection::class =>
                [],
            \RcmUser\Event\UserEventManager::class =>
                $this->getMockBuilder(
                    \RcmUser\Event\UserEventManager::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            \RcmUser\Log\Logger::class =>
                $this->getMockBuilder(
                    \Zend\Log\LoggerInterface::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
            /////////////////////////////////////////////////////////
            \Doctrine\ORM\EntityManager::class =>
                $this->getMockBuilder(
                    \Doctrine\ORM\EntityManager::class
                )
                    ->disableOriginalConstructor()
                    ->getMock(),
        ];

        return $this->mockServices;
    }

    /**
     * getValueMap
     *
     * @return array
     */
    public function getValueMap()
    {
        if (isset($this->valueMap)) {
            return $this->valueMap;
        }
        $mockServices = $this->getMockServices();
        $this->valueMap = [];
        foreach ($mockServices as $key => $value) {
            $this->valueMap[] = [$key, $value];
        }

        return $this->valueMap;
    }

    /**
     * getMockServiceLocator
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getMockServiceLocator()
    {
        if (isset($this->mockServiceLocator)) {
            return $this->mockServiceLocator;
        }

        $this->mockServiceLocator = $this->getMockBuilder(
            \Zend\ServiceManager\ServiceLocatorInterface::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockServiceLocator->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($this->getValueMap()));

        return $this->mockServiceLocator;
    }

    /**
     * getMockControllerManager
     *
     * @return \Zend\Mvc\Controller\ControllerManager
     */
    public function getMockControllerManager()
    {
        if (isset($this->mockControllerManager)) {
            return $this->mockControllerManager;
        }

        $this->mockControllerManager = $this->getMockBuilder(
            \Zend\Mvc\Controller\ControllerManager::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockControllerManager->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($this->getMockServiceLocator()));

        return $this->mockControllerManager;
    }

    /**
     * getMockViewManager
     *
     * @return \Zend\View\HelperPluginManager
     */
    public function getMockHelperPluginManager()
    {
        if (isset($this->mockHelperPluginManager)) {
            return $this->mockHelperPluginManager;
        }

        $this->mockHelperPluginManager = $this->getMockBuilder(
            \Zend\View\HelperPluginManager::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockHelperPluginManager->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($this->getMockServiceLocator()));

        return $this->mockHelperPluginManager;
    }

    /**
     * test - this is just here to avoid php unit error
     *
     * @return void
     */
    public function test()
    {
        $this->assertTrue(true);
    }
}
