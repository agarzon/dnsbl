<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>DNSBLS - DNS Blacklist and Sender Score</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="DNSBLS - DNS Blacklist and Sender Score">
	<link rel="shortcut icon" href="favicon.ico">
	<!-- Twitter Bootstrap -->
	<link href="bootstrap.min.css" rel="stylesheet">

	<style type="text/css">
		body { padding-top: 40px; }
	</style>
</head>
<body>
	<div class="container">
		<div class="well well-large">
			<h1>DNS Blacklist and Sender Score</h1>
			<form action="" method="POST" class="form-inline" autocomplete="off">
				<label>IP or domain address</label>
				<input type="text" name="host" placeholder="Enter IP or Domain...">
				<button type="submit" class="btn">Check Now!</button>
			</form>
			<p>* Score is taken from: <a href="http://www.senderscore.org/" target="_blank">senderscore.org</a></p>
		</div>
	<?php
	require_once("bls.php");
	require_once("class.php");
	$host = !empty($_REQUEST['host']) ? $_REQUEST['host'] : null;

	if (isset($host)) {
		if (Dnsbls::validateIp($host)) {
			echo "<h4>IP: " . gethostbyname($host) . "</h4>";
			if (Dnsbls::getScore($host)) {
				echo Dnsbls::drawBar(Dnsbls::getScore($host)); ?>
			<?php } else {
				echo 
				'<div class="alert">
					<strong>No Score</strong> - Insufficient Email Seen
				</div>';
			} ?>

			<table class="table table-striped table-bordered table-condensed">
				<?php
				ini_set('zlib.output_compression', 0);
				ini_set('output_buffering', 0);

				ob_implicit_flush(true);
				while (@ob_end_flush());//Clean buffer
				foreach ($bls as $rbl) {
					$checkBl = Dnsbls::checkBl($host, $rbl);
					if ($checkBl) {
						echo "<tr class=\"error\"><td>$rbl</td><td><span class=\"label label-error\">Listed</span></td></tr>";
					} else {
						echo "<tr><td>$rbl</td><td><span class=\"label label-success\">OK</span></td></tr>";
					}
					echo str_repeat(" ", 1024), "\n";
					usleep(250000);
				} ?>
			</table>
			
		<?php } else {
			echo "<div class=\"alert alert-error\">Error: Invalid, private or reserved IP or domain address</div>\n";
		}

	} ?>
		<footer>
			<p>&copy; VeneHosting.com <?=date('Y')?></p>
		</footer>

	</div>
</body>
</html>