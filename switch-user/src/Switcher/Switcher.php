<?php

namespace Rcm\SwitchUser\Switcher;

use Rcm\SwitchUser\Result;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Switcher
{
    /**
     * getName of the switch method
     *
     * @return string
     */
    public function getName();

    /**
     * switchTo
     *
     * @param UserInterface $targetUser
     * @param array         $options
     *
     * @return Result
     */
    public function switchTo(UserInterface $targetUser, $options = []);

    /**
     * switchBack
     *
     * @param UserInterface $impersonatorUser
     * @param array         $options
     *
     * @return Result
     */
    public function switchBack(UserInterface $impersonatorUser, $options = []);
}
