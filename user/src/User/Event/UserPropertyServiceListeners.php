<?php

namespace RcmUser\User\Event;

use RcmUser\Result;
use RcmUser\User\Entity\UserRoleProperty;
use RcmUser\User\Service\UserPropertyService;
use Zend\EventManager\Event;

/**
 * Class UserPropertyServiceListeners
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class UserPropertyServiceListeners extends AbstractUserDataServiceListeners
{
    /**
     * @var int $priority
     */
    protected $priority = 1;

    /**
     * @var string
     */
    protected $id = UserPropertyService::EVENT_IDENTIFIER;

    /**
     * @var array $listenerMethods
     */
    protected $listenerMethods
        = [
            //'onGetUserPropertyLinks' => 'getUserPropertyLinks',
            'onPopulateUserProperty' => UserPropertyService::EVENT_POPULATE_USER_PROPERTY, //'populateUserProperty',
            //'onGetUserPropertyIsAllowed' => UserPropertyService::EVENT_GET_USER_PROPERTY_IS_ALLOWED,
        ];

    /**
     * getUserPropertyKey
     *
     * @return string
     */
    public function getUserPropertyKey()
    {
        return UserRoleProperty::PROPERTY_KEY;
    }

    /**
     * onPopulateUserProperty
     *
     * @param Event $e e
     *
     * @return bool|Result
     */
    public function onPopulateUserProperty($e)
    {
        $propertyNameSpace = $e->getParam('propertyNameSpace');
        $data = $e->getParam('data');
        $thisPropertyNameSpace = $this->getUserPropertyKey();

        if ($propertyNameSpace !== $thisPropertyNameSpace) {
            return false;
        }

        $property = new UserRoleProperty();

        try {
            $property->populate($data);
        } catch (\Exception $e) {
            return new \RcmUser\Result(
                $property,
                Result::CODE_FAIL,
                'Property failed to populate with error: ' . $e->getMessage()
            );
        }

        return new Result($property);
    }

    /**
     * onGetUserPropertyLinks @todo
     *
     * @param Event $e e
     *
     * @return \RcmUser\Result
     *
     * public function onGetUserPropertyLinks($e)
     * {
     * $user = $e->getParam('user');
     * $propertyNameSpace = $e->getParam('propertyNameSpace');
     * $thisPropertyNameSpace = $this->getUserPropertyKey();
     *
     * if ($propertyNameSpace !== $thisPropertyNameSpace) {
     *
     * return false;
     * }
     *
     * $links = new Links();
     * $link = new Link();
     * $link->setTitle('Edit User Roles');
     * $link->setType('edit');
     * $link->setHelp('Edit page for adding removing user roles.');
     * $link->setUrl('/admin/' . $user->getId());
     * $links->addLink($link);
     *
     * return new Result($links);
     * }
     */

    /**
     * onGetUserPropertyIsAllowed @todo
     *
     * @param Event $e e
     *
     * @return bool
     *
     * public function onGetUserPropertyIsAllowed($e)
     * {
     * $user = $e->getParam('user');
     * $propertyNameSpace = $e->getParam('propertyNameSpace');
     * $thisPropertyNameSpace = $this->getUserPropertyKey();
     *
     * if ($propertyNameSpace !== $thisPropertyNameSpace) {
     *
     * return false;
     * }
     *
     * return false;
     * }
     */
}
