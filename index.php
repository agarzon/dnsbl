<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="DNSBLS - DNS Blacklist and Sender Score">
		<title>DNSBLS - DNS Blacklist and Sender Score</title>

		<link href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/lumen/bootstrap.min.css" rel="stylesheet">

		<style type="text/css" media="screen">
			body {padding-top: 10px;}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="well">
				<h2>DNS Blacklist and Sender Score</h2>
				<form method="post" action="index.php" class="form-inline" autocomplete="off" role="form">
					<label>IP or domain address: </label>
					<input id="host" name="host" placeholder="Enter IP or Domain..." class="form-control" required="" >
					<button id="submit" name="submit" class="btn btn-primary">Check Now!</button>
				</form>
				<p>* Sender Score is taken from: <a href="http://www.senderscore.org/" target="_blank">senderscore.org</a></p>
			</div>
			<?php
			require_once("class.php");
			$host = !empty($_REQUEST['host']) ? $_REQUEST['host'] : null;

			if (isset($host)) {
				if (Dnsbls::validateIp($host)) {
					echo "<p>IP: " . gethostbyname($host) . "</p>";
					if (Dnsbls::getScore($host)) {
						echo Dnsbls::drawBar(Dnsbls::getScore($host)); ?>
					<?php } else {
						echo
						'<div class="alert alert-warning">
							<strong>No Score</strong> - Insufficient Email Seen
						</div>';
					} ?>

					<table class="table table-striped table-bordered table-condensed table-hover">
						<?php
						ini_set('zlib.output_compression', 0);
						ini_set('output_buffering', 0);
						ob_implicit_flush(true);
						while (@ob_end_flush());//Clean buffer
						foreach (Dnsbls::$list as $rbl) {
							$checkBl = Dnsbls::checkBl($host, $rbl);
							if ($checkBl) {
								echo "<tr class=\"danger\"><td>$rbl</td><td><span class=\"label label-danger\">Listed</span></td></tr>";
							} else {
								echo "<tr><td>$rbl</td><td><span class=\"label label-success\">OK</span></td></tr>";
							}
							echo str_repeat(" ", 1024), "\n";
							usleep(250000);
						} ?>
					</table>

				<?php } else {
					echo "<div class=\"alert alert-danger\">Error: Invalid, private or reserved IP or domain address</div>\n";
				}

			} ?>
			<hr />
			<footer>
				<p>Created by <a href="http://www.venehosting.com/" title="VeneHosting.com" target="_blank">VeneHosting.com</a></p>
			</footer>
		</div>
	</body>
</html>