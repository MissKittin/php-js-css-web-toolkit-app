<?php
	$view['csp_header']['style-src'][]='\'unsafe-inline\'';
	$view['csp_header']['script-src'][]='\'sha256-/X5PLF1RFAQnQE1exIRAG0cmaQJ4A4miMM25uzsWk6o=\'';

	$view['lang']='en_US';
	$view['title']='Websockets';
	$view['meta_description']='Websockets test';
	$view['meta_robots']='noindex,nofollow';

	$view['opengraph_headers'][]=['url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']];
	$view['opengraph_headers'][]=['type', 'website'];
	$view['opengraph_headers'][]=['site_name', 'PHP JS CSS Web Toolkit App'];
?>