<?php
namespace Rcm\Model;

class IpInfo
{
    /**
     * Correctly parses remote IP from load-balancer-added header. Will fall
     * back if no load balancer.
     * @return string
     */
    function getRemoteIp() {
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //Sometimes many IPs are in this string. We want the last one.
            $ipString=getenv("HTTP_X_FORWARDED_FOR");
            $ips = explode(",",$ipString);
            //Sometimes the IP ends with a space so we trim.
            return trim($ips[count($ips)-1]);
        }else{
            //If no load balancer then fall back to normal remote IP.
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}