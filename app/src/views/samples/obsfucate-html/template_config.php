<?php
	$view['lang']='en_US';
	$view['title']='HTML obsfucator';
	$view['meta_description']='Output buffer library test';
	$view['meta_robots']='index,follow';

	$view['opengraph_headers'][]=['url', (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']];
	$view['opengraph_headers'][]=['type', 'website'];
	$view['opengraph_headers'][]=['site_name', 'PHP JS CSS Web Toolkit App'];

	// inline script in page_content.php (patch)
	$view['csp_header']['script-src'][]='\'sha256-X6J7hQYXqnu888xEQ+pczWCZ840+hDTUD88HRubhjuI=\'';
?>