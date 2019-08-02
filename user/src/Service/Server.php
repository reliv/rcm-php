<?php

namespace RcmUser\Service;

/**
 * Class Server
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class Server
{
    /**
     * getRemoteIpAddress
     *
     * @return string
     */
    public static function getRemoteIpAddress()
    {
        if (!isset($_SERVER)) {
            return 'UNKNOWN';
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //Sometimes many IPs are in this string. We want the last one.
            $ipString = getenv("HTTP_X_FORWARDED_FOR");
            $ips = explode(",", $ipString);

            //Sometimes the IP ends with a space so we trim.
            return trim($ips[count($ips) - 1]);
        }

        //If no load balancer then fall back to normal remote IP.
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * getSessionId
     *
     * @return string
     */
    public static function getSessionId()
    {
        return session_id();
    }
}
