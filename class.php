<?php
class Dnsbls {

	public static $list = array(
		"bl.score.senderscore.com",
		"bl.mailspike.net",
		"bl.spameatingmonkey.net",
		"b.barracudacentral.org",
		"bl.deadbeef.com",
		"bl.emailbasura.org",
		"bl.spamcannibal.org",
		"bl.spamcop.net",
		"blackholes.five-ten-sg.com",
		"blacklist.woody.ch",
		"bogons.cymru.com",
		"cbl.abuseat.org",
		"cdl.anti-spam.org.cn",
		"combined.abuse.ch",
		"combined.rbl.msrbl.net",
		"db.wpbl.info",
		"dnsbl-1.uceprotect.net",
		"dnsbl-2.uceprotect.net",
		"dnsbl-3.uceprotect.net",
		"dnsbl.ahbl.org",
		"dnsbl.inps.de",
		"dnsbl.sorbs.net",
		"drone.abuse.ch",
		"drone.abuse.ch",
		"duinv.aupads.org",
		"dul.dnsbl.sorbs.net",
		"dul.ru",
		"dyna.spamrats.com",
		"dynip.rothen.com",
		"http.dnsbl.sorbs.net",
		"images.rbl.msrbl.net",
		"ips.backscatterer.org",
		"ix.dnsbl.manitu.net",
		"korea.services.net",
		"misc.dnsbl.sorbs.net",
		"noptr.spamrats.com",
		"ohps.dnsbl.net.au",
		"omrs.dnsbl.net.au",
		"orvedb.aupads.org",
		"osps.dnsbl.net.au",
		"osrs.dnsbl.net.au",
		"owfs.dnsbl.net.au",
		"owps.dnsbl.net.au",
		"pbl.spamhaus.org",
		"phishing.rbl.msrbl.net",
		"probes.dnsbl.net.au",
		"proxy.bl.gweep.ca",
		"proxy.block.transip.nl",
		"psbl.surriel.com",
		"rbl.interserver.net",
		"rdts.dnsbl.net.au",
		"relays.bl.gweep.ca",
		"relays.bl.kundenserver.de",
		"relays.nether.net",
		"residential.block.transip.nl",
		"ricn.dnsbl.net.au",
		"rmst.dnsbl.net.au",
		"sbl.spamhaus.org",
		"short.rbl.jp",
		"smtp.dnsbl.sorbs.net",
		"socks.dnsbl.sorbs.net",
		"spam.abuse.ch",
		"spam.dnsbl.sorbs.net",
		"spam.rbl.msrbl.net",
		"spam.spamrats.com",
		"spamlist.or.kr",
		"spamrbl.imp.ch",
		"t3direct.dnsbl.net.au",
		"tor.ahbl.org",
		"tor.dnsbl.sectoor.de",
		"torserver.tor.dnsbl.sectoor.de",
		"ubl.lashback.com",
		"ubl.unsubscore.com",
		"virbl.bit.nl",
		"virus.rbl.jp",
		"virus.rbl.msrbl.net",
		"web.dnsbl.sorbs.net",
		"wormrbl.imp.ch",
		"xbl.spamhaus.org",
		"zen.spamhaus.org",
		"zombie.dnsbl.sorbs.net",
	);

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
				$class = "progress-bar progress-bar-danger"; break;
			case $score <= 75:
				$class = "progress-bar-warning"; break;
			case $score <= 100:
				$class = "progress-bar-success"; break;
		}

		return '
		<div class="progress">
			<div class="progress-bar ' . $class . '" role="progressbar" aria-valuenow="' . $score . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $score . '%;">
			' . $score . '
			</div>
		</div>';
	}
}
