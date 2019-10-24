<?php

namespace Rcm\SwitchUser\Restriction;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Result
{
    /**
     * setAllowed
     *
     * @param bool   $allowed
     * @param string $message
     *
     * @return void
     */
    public function setAllowed($allowed, $message = '');

    /**
     * isAllowed
     *
     * @return bool
     */
    public function isAllowed();

    /**
     * getMessage
     *
     * @return string
     */
    public function getMessage();
}
