<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\RemoteAccessManager\Handler;

class WedosAccessHandler extends DefaultAccessHandler implements IAccessHandler
{
	public function deny()
	{
		echo <<< WEDOS
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="cs" />
	<title>Webhosting je aktivní</title>
	<meta name="robots" content="noindex,follow" />
	<link rel="stylesheet" type="text/css" href="https://hosting.wedos.com/css/default-pages.css" />
</head>
<body class="web_default">

	<div id="page">

		<div id="header">
			<img src="https://hosting.wedos.com/images/default-pages/logo.png" alt="WEDOS Internet, a.s." />
		</div>

		<div id="content">
			<img src="https://hosting.wedos.com/images/default-pages/tick.png" alt="OK" />
			<h1>Webhosting je aktivní</h1>
			<p>Webhosting pro tuto doménu je aktivní. Přes FTP nahrajte potřebné soubory a poté tento soubor <b>index.html</b> smažte.</p>
			<p>Další informace a návody hledejte ve <strong><a href="https://kb.wedos.com/">znalostní bázi WEDOS</a></strong>.</p>
		</div>

		<div id="footer">
			<p><strong><a href="https://hosting.wedos.com/">Hosting WEDOS</a></strong> - <a href="https://hosting.wedos.com/cs/domeny.html">registrace domén</a>, <a href="https://hosting.wedos.com/cs/webhosting.html">webhosting</a>, <a href="https://hosting.wedos.com/cs/dedikovane-servery.html">serverhosting</a></p>
		</div>

	</div>
</body>
</html>
WEDOS;
		exit();
	}
}
