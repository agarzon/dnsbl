<?php 
class Dnsbls {

	public static function validateIp($host) {
		$host = (filter_var($host, FILTER_VALIDATE_URL) == false) ? gethostbyname($host) : $host;
		return (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) ? true : false;
	}
	
	public static function reverseIp($ip) {
		return implode('.', array_reverse(explode ('.', $ip)));
	}

	public static function getScore($ip) {
		$reversedIp = self::reverseIp($ip);
		$resultDNS = dns_get_record($reversedIp . '.score.senderscore.com', DNS_A);
		if (empty($resultDNS)) {
			return false;
		} else {
			$pieces = explode('.', $resultDNS[0]['ip']);
			return $pieces[3];
		}		
	}

	public static function checkBl($ip, $rbl) {
		$reversedIp = self::reverseIp($ip);
		return  $resultBl = dns_get_record($reversedIp . '.' . $rbl, DNS_TXT);
		return !empty($resultBl);
	}

	public static function drawBar($score = 0) {
		switch ($score) {
			case $score <= 50:
				$class = "progress-danger"; break;
			case $score <= 75:
				$class = "progress-warning"; break;
			case $score <= 100:
				$class = "progress-success"; break;
		}

		return "
		<div class=\"progress $class\">
		<div class=\"bar\" style=\"width: $score%\">Score: $score</div>
		</div>";
	}
}
