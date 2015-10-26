<?php

class Dnsbls
{
    public static function validateIp($host)
    {
        $host = (filter_var($host, FILTER_VALIDATE_URL) == false) ? gethostbyname($host) : $host;
        return (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) ? true : false;
    }

    public static function reverseIp($ip)
    {
        return implode('.', array_reverse(explode('.', $ip)));
    }

    public static function getScore($ip)
    {
        $resultDNS = dns_get_record(self::reverseIp($ip) . '.score.senderscore.com');
        if (!empty($resultDNS)) {
            return str_replace('127.0.4.', '', $resultDNS[0]['ip']);
        }
        return false;
    }

    public static function checkBl($ip, $rbl)
    {
        return (checkdnsrr(self::reverseIp($ip) . "." . $rbl . ".", "A"));
    }
}
