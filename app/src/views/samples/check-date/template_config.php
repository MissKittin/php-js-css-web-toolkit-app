<?php
	$view['csp_header']['style-src'][]='\'unsafe-hashes\'';
	$view['csp_header']['style-src'][]='\'sha256-Ls1729j3r2TF/b4LA4PWZuspbwvcg6xE5uxyBKBeXrI=\'';
	$view['csp_header']['style-src'][]='\'sha256-LpG2EGpPl462t81FieLddfap21+YrF49ronG6DL+7A8=\'';

	$view['lang']='en_US';
	$view['title']='Check date test';
	$view['meta_description']='PHP library test';
	$view['meta_robots']='index,follow';

	$view['opengraph_headers'][]=['url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']];
	$view['opengraph_headers'][]=['type', 'website'];
	$view['opengraph_headers'][]=['site_name', 'PHP JS CSS Web Toolkit App'];
?>