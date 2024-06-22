<?php
	// dev note: comment out the script-src sha and uncomment the line below
	//$view['csp_header']['script-src'][]='\'unsafe-inline\'';

	$view['csp_header']['script-src'][]='\'sha256-rrCZqwWPAuf1WazFUUhoYuIPF4ks6eG86vgohUugzns=\'';
	$view['csp_header']['style-src'][]='\'unsafe-hashes\'';
	$view['csp_header']['style-src'][]='\'sha256-+k5+KrEwshiQdCJo2qBq0r6HXBL1wDofbCniyWDqWqA=\'';
	$view['csp_header']['style-src'][]='\'sha256-LJpsgcd5doJCKRHPIlVV6D8a51yxDT2EmILNFjLlwCg\'';
	$view['csp_header']['style-src'][]='\'sha256-yhyh8XnYFd+/f2BIIZsFbe6jpM1o1tCzCmnA5uI9Pnc=\'';
	$view['csp_header']['style-src'][]='\'sha256-+k5+KrEwshiQdCJo2qBq0r6HXBL1wDofbCniyWDqWqA=\'';

	$view['lang']='en';
	$view['title']='About';
	$view['meta_description']='Toolkit readme';
	$view['meta_robots']='index,follow';
?>