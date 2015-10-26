var dnsbl = [
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
	"spamrbl.imp.ch",
	"t3direct.dnsbl.net.au",
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
];

var goodColor = 'green darken-4';
var badColor = 'red darken-4';

$( "form" ).submit(function( event ) {
	event.preventDefault();
	if ($( "input:first" ).val() === '') {
		return false;
	};

	$('table > tbody:last-child').html('');
	validate($( "input:first" ).val());

	$( "input:first" ).val('') ;
	$('#resolvedIp').text('');
	$('#results-container').addClass('hide');
	$('#score').text('').removeClass(badColor, goodColor).addClass('disabled');

	return false;
});

function query(host, ip) {
	$.ajax({
		url: 'query/'+ host + '/' + ip,
		dataType: 'json',
		success: function(data) {
			console.log(data);
			if (data.status == 'OK') {
				var icon = '<i class="material-icons">done_all</i>';
				var color = goodColor;
			} else {
				var icon = '<i class="material-icons">warning</i>';
				var color = badColor;
			}
			$('table > tbody:last-child').append('<tr><td>'+data.host+'</td><td><div class="chip white-text '+color+'">'+icon+data.status+'</div></td></tr>');
		}
	});
}

function getScore(ip) {
	$.ajax({
		url: 'score/' + ip,
		dataType: 'json',
		success: function(data) {
			console.log( data );
			if (data.score == false) {
				$('#score').text('Insufficient Email Seen').addClass('disabled');
			} else {
				if (data.score <= 80) {
					var icon = '<i class="material-icons">warning</i>';
					$('#score').text(data.score + '%').removeClass('disabled').addClass(badColor);
				} else {
					var icon = '<i class="material-icons">done_all</i>';
					$('#score').text(data.score + '%').removeClass('disabled').addClass(goodColor);
				}
			}
		}
	});
}

function validate(ip) {
	$.ajax({
		url: 'validate/' + ip,
		dataType: 'json',
		success: function(data) {
			if (data.validate == false) {
				Materialize.toast('Input looks invalid', 4000);
				$('#host').addClass('invalid');
				return false;
			} else {
				//$("#loadingbar").show();
				$('#results-container').removeClass('hide');
				getScore(data.ip);
				$('#resolvedIp').text(data.ip);
				$.each( dnsbl, function( key, host ) {
					query(host, data.ip);
				});
				$("#loadingbar").hide();
			}
		},
		beforeSend: function(){
			$("#loadingbar").show();
		},
	});
}
