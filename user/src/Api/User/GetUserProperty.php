<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetUserProperty
{
    /**
     * OnDemand loading of a user property.
     * Is a way of populating User::property using events.
     * Some user properties are not loaded with the user to increase speed.
     * Use this method to load these properties.
     *
     * @param UserInterface $user
     * @param string        $propertyNameSpace
     * @param null          $default
     * @param bool          $refresh
     *
     * @return mixed
     */
    public function __invoke(
        UserInterface $user,
        $propertyNameSpace,
        $default = null,
        $refresh = false
    );
}
