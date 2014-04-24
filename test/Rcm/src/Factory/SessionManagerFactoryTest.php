<?php
/**
 * Test for Factory SessionManagerFactory
 *
 * This file contains the test for the SessionManagerFactory.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmTest\Factory;

require_once __DIR__ . '/../../../Base/BaseTestCase.php';

use Rcm\Factory\SessionManagerFactory;
use RcmTest\Base\BaseTestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SaveHandler\SaveHandlerInterface;
use Zend\Session\SessionManager;
use Zend\Session\Storage\StorageInterface;

/**
 * Test for Factory SessionManagerFactory
 *
 * Test for Factory SessionManagerFactory
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class SessionManagerFactoryTest extends BaseTestCase
{
    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        $this->addModule('Rcm');
        parent::setUp();
    }

    /**
     * Generic test for the constructor
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testCreateService()
    {
        $sm = new ServiceManager();
        $sm->setService('config', array());

        $factory = new SessionManagerFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof SessionManager);
    }

    /**
     * Test Create Service With Config
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testCreateServiceWithConfig()
    {

        $config = array(
            'session' => array(
                'config' => array(
                    'class' => '\Zend\Session\Config\SessionConfig'
                )
            )
        );

        $sm = new ServiceManager();
        $sm->setService('config', $config);

        $factory = new SessionManagerFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof SessionManager);
    }

    /**
     * Test getSessionConfig with empty config
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testGetSessionConfigWithNoConfigArray()
    {

        $config = array(
            'SomeVar'
        );

        $sm = new ServiceManager();

        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionConfig');
        $method->setAccessible(true);

        $object = $method->invoke($factory, $sm, $config);

        $this->assertTrue($object instanceof SessionConfig);
    }

    /**
     * Test getSessionConfig with Class Config But No Options
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testGetSessionConfigWithClassConfigButNotOptions()
    {

        $config = array(
            'config' => array(
                'class' => '\Zend\Session\Config\SessionConfig'
            )
        );

        $sm = new ServiceManager();

        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionConfig');
        $method->setAccessible(true);

        $object = $method->invoke($factory, $sm, $config);

        $this->assertTrue($object instanceof SessionConfig);
    }

    /**
     * Test getSessionConfig with Service Locator Object But No Options
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testGetSessionConfigWithServiceLocatorObjectButNotOptions()
    {

        $config = array(
            'config' => array(
                'class' => 'mockSessionConfigObject'
            )
        );

        $mockSessionConfigObject = $this
            ->getMockBuilder('\Zend\Session\Config\SessionConfig')
            ->disableOriginalConstructor()
            ->getMock();

        $sm = new ServiceManager();
        $sm->setService('mockSessionConfigObject', $mockSessionConfigObject);

        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionConfig');
        $method->setAccessible(true);

        $object = $method->invoke($factory, $sm, $config);

        $this->assertTrue($object instanceof SessionConfig);
    }

    /**
     * Test getSessionConfig with Class Config And Options
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testGetSessionConfigWithClassConfigAndOptions()
    {

        $config = array(
            'config' => array(
                'class' => '\Zend\Session\Config\SessionConfig',
                'options' => array(
                    'name' => 'myTest'
                ),
            ),
        );

        $sm = new ServiceManager();

        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionConfig');
        $method->setAccessible(true);

        /** @var \Zend\Session\Config\SessionConfig $object */
        $object = $method->invoke($factory, $sm, $config);

        $this->assertTrue($object instanceof SessionConfig);

        $this->assertEquals('myTest', $object->getName());
    }

    /**
     * Test getSessionConfig with No Class Config but has Options
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testGetSessionConfigWithNoClassConfigButHasOptions()
    {

        $config = array(
            'config' => array(
                'options' => array(
                    'name' => 'myTest'
                ),
            ),
        );

        $sm = new ServiceManager();
        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionConfig');
        $method->setAccessible(true);

        /** @var \Zend\Session\Config\SessionConfig $object */
        $object = $method->invoke($factory, $sm, $config);

        $this->assertTrue($object instanceof SessionConfig);

        $this->assertEquals('myTest', $object->getName());
    }

    /**
     * Test getSessionConfig with invalid class type
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testGetSessionConfigWithInvalidClassType()
    {

        $config = array(
            'config' => array(
                'class' => '\Rcm\Factory\SessionManagerFactory'
            )
        );

        $sm = new ServiceManager();
        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionConfig');
        $method->setAccessible(true);

        /** @var \Zend\Session\Config\SessionConfig $object */
        $method->invoke($factory, $sm, $config);
    }

    /**
     * Test getSessionStorage with empty config
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testGetSessionStorageWithNoConfigArray()
    {

        $config = array(
            'SomeVar'
        );

        $sm = new ServiceManager();
        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionStorage');
        $method->setAccessible(true);

        $object = $method->invoke($factory, $sm, $config);

        $this->assertEmpty($object);
    }

    /**
     * Test getSessionStorage with Storage Class set in config
     *
     * @return null
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testGetSessionStorageWithStorageClassSetInConfig()
    {

        $config = array(
            'storage' => '\Zend\Session\Storage\SessionArrayStorage'
        );

        $sm = new ServiceManager();
        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionStorage');
        $method->setAccessible(true);

        $object = $method->invoke($factory, $sm, $config);

        $this->assertTrue($object instanceof StorageInterface);
    }

    /**
     * Test getSessionStorage with Storage Class set from Service Locator
     *
     * @return null
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testGetSessionStorageWithStorageClassInServiceLocator()
    {

        $config = array(
            'storage' => 'MockSessionArrayStorage'
        );

        $mockStorageHandler = $this
            ->getMockBuilder('\Zend\Session\Storage\SessionArrayStorage')
            ->disableOriginalConstructor()
            ->getMock();

        $sm = new ServiceManager();
        $sm->setService('MockSessionArrayStorage', $mockStorageHandler);

        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionStorage');
        $method->setAccessible(true);

        $object = $method->invoke($factory, $sm, $config);

        $this->assertTrue($object instanceof StorageInterface);
    }

    /**
     * Test getSessionStorage with Invalid Storage Class set in config
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testGetSessionStorageWithInvalidStorageClassSetInConfig()
    {

        $config = array(
            'storage' => '\Rcm\Factory\SessionManagerFactory'
        );

        $sm = new ServiceManager();

        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionStorage');
        $method->setAccessible(true);

        $method->invoke($factory, $sm, $config);
    }

    /**
     * Test getSessionSaveHandler with empty config
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testGetSessionSaveHandlerWithNoConfigArray()
    {

        $config = array(
            'SomeVar'
        );

        $sm = new ServiceManager();
        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionSaveHandler');
        $method->setAccessible(true);

        $object = $method->invoke($factory, $sm, $config);

        $this->assertEmpty($object);
    }

    /**
     * Test getSessionSaveHandler with class set in config
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testGetSessionSaveHandlerWithClassSetInServiceManager()
    {

        $config = array(
            'save_handler' => 'MockSaveHandler'
        );

        $mockSaveHandler = $this
            ->getMockBuilder('\Zend\Session\SaveHandler\MongoDB')
            ->disableOriginalConstructor()
            ->getMock();

        $sm = new ServiceManager();
        $sm->setService('MockSaveHandler', $mockSaveHandler);

        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionSaveHandler');
        $method->setAccessible(true);

        $object = $method->invoke($factory, $sm, $config);

        $this->assertTrue($object instanceof SaveHandlerInterface);
    }

    /**
     * Test getSessionSaveHandler with invalid class set in config
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testGetSessionSaveHandlerWithInvalidClassSetInServiceManager()
    {

        $config = array(
            'save_handler' => 'MockSaveHandler'
        );

        $mockSaveHandler = $this
            ->getMockBuilder('\Rcm\Factory\SessionManagerFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $sm = new ServiceManager();
        $sm->setService('MockSaveHandler', $mockSaveHandler);

        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('getSessionSaveHandler');
        $method->setAccessible(true);

        $method->invoke($factory, $sm, $config);
    }

    /**
     * Test setValidatorChain with empty config
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testSetValidatorChainWithNoConfigArray()
    {

        $config = array(
            'SomeVar'
        );

        $sessionManager = new SessionManager();

        $sm = new ServiceManager();
        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('setValidatorChain');
        $method->setAccessible(true);

        $method->invoke($factory, $sessionManager, $sm, $config);

        $validators = $sessionManager
            ->getValidatorChain()
            ->getListeners('session.validate');

        $this->assertEmpty($validators);
    }

    /**
     * Test setValidatorChain with class in config
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testSetValidatorChainWithClassInConfigArray()
    {

        $config = array(
            'validators' => array(
                'Zend\Session\Validator\HttpUserAgent',
                'Zend\Session\Validator\RemoteAddr'
            )
        );

        $sessionManager = new SessionManager();

        $sm = new ServiceManager();
        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('setValidatorChain');
        $method->setAccessible(true);

        $method->invoke($factory, $sessionManager, $sm, $config);

        /** @var \Zend\Stdlib\PriorityQueue $validators */
        $validators = $sessionManager
            ->getValidatorChain()
            ->getListeners('session.validate');

        $list = $validators->getIterator()->toArray();

        /** @var \Zend\Stdlib\CallbackHandler $item */
        $hasItems = array();

        foreach ($list as $item) {
            $validator = $item->getCallback();
            $hasItems[] = get_class($validator[0]);
        }

        $this->assertContains($config['validators'][0], $hasItems);
        $this->assertContains($config['validators'][1], $hasItems);
    }

    /**
     * Test setValidatorChain with service in config
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     */
    public function testSetValidatorChainWithServiceInConfigArray()
    {

        $config = array(
            'validators' => array(
                'HttpUserAgent',
                'RemoteAddr'
            )
        );

        $mockHttpUserAgent = $this
            ->getMockBuilder('Zend\Session\Validator\HttpUserAgent')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRemoteAddr = $this
            ->getMockBuilder('Zend\Session\Validator\RemoteAddr')
            ->disableOriginalConstructor()
            ->getMock();

        $expectedUserAgentClass = get_class($mockHttpUserAgent);
        $expectedRemoteClass = get_class($mockRemoteAddr);

        $sessionManager = new SessionManager();

        $sm = new ServiceManager();
        $sm->setService('HttpUserAgent', $mockHttpUserAgent);
        $sm->setService('RemoteAddr', $mockRemoteAddr);

        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('setValidatorChain');
        $method->setAccessible(true);

        $method->invoke($factory, $sessionManager, $sm, $config);

        /** @var \Zend\Stdlib\PriorityQueue $validators */
        $validators = $sessionManager
            ->getValidatorChain()
            ->getListeners('session.validate');

        $list = $validators->getIterator()->toArray();

        /** @var \Zend\Stdlib\CallbackHandler $item */
        $hasItems = array();

        foreach ($list as $item) {
            $validator = $item->getCallback();
            $hasItems[] = get_class($validator[0]);
        }

        $this->assertContains($expectedUserAgentClass, $hasItems);
        $this->assertContains($expectedRemoteClass, $hasItems);
    }

    /**
     * Test setValidatorChain with invalid class in config
     *
     * @return null
     *
     * @covers \Rcm\Factory\SessionManagerFactory
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testSetValidatorChainWithInvalidClassInConfigArray()
    {

        $config = array(
            'validators' => array(
                '\Rcm\Factory\SessionManagerFactory',
            )
        );

        $sessionManager = new SessionManager();

        $sm = new ServiceManager();
        $factory = new SessionManagerFactory();

        $class = new \ReflectionClass('\Rcm\Factory\SessionManagerFactory');
        $method = $class->getMethod('setValidatorChain');
        $method->setAccessible(true);

        $method->invoke($factory, $sessionManager, $sm, $config);
    }
}