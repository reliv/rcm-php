<?php

namespace RcmUser\Authentication\Adapter;

use RcmUser\User\Entity\UserInterface;
use Zend\Authentication\Adapter\ValidatableAdapterInterface;

/**
 * Class Adapter
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface Adapter extends ValidatableAdapterInterface
{
    /**
     * withUser - Immutable setting of the user
     *
     * @param UserInterface $user
     *
     * @return Adapter
     */
    public function withUser(UserInterface $user);
}
