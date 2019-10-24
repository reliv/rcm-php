<?php

namespace Rcm\SwitchUser;

/**
 * Class Result
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\SwitchUser
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Result
{
    /**
     * @var bool
     */
    protected $success = true;

    /**
     * @var string
     */
    protected $message = '';

    /**
     * setSuccess
     *
     * @param bool   $success
     * @param string $message
     *
     * @return void
     */
    public function setSuccess($success, $message = '')
    {
        $this->success = (bool)$success;
        $this->message = (string)$message;
    }

    /**
     * isAllowed
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * getMessage
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
