<?php

namespace RcmUser\Authentication\Adapter;

use RcmUser\User\Entity\UserInterface;

/**
 * Class AbstractAdapter
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
abstract class AbstractAdapter extends \Zend\Authentication\Adapter\AbstractAdapter implements Adapter
{
    /**
     * @var
     */
    protected $user;

    /**
     * withUser - Immutable setting of the user
     *
     * @param UserInterface $user
     *
     * @return UserAdapter|Adapter
     */
    public function withUser(UserInterface $user)
    {
        $new = clone($this);
        $new->user = $user;

        return $new;
    }
}
