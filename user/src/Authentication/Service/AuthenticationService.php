<?php

namespace RcmUser\Authentication\Service;

use RcmUser\User\Entity\UserInterface;

/**
 * Class AuthenticationService
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AuthenticationService extends \Zend\Authentication\AuthenticationService
{
    /**
     * setIdentity - User to refresh existing session
     *
     * @param UserInterface $identity identity
     *
     * @return void
     */
    public function setIdentity(UserInterface $identity)
    {
        $storage = $this->getStorage();

        $storage->write($identity);
    }
}
