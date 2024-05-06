<?php
	function robots()
	{
		header('Content-type: text/plain');

		$proto='http';
		if(isset($_SERVER['HTTPS']))
			$proto='https';

		echo 'Sitemap: '.$proto.'://'.$_SERVER['HTTP_HOST'].'/sitemap.xml'."\n";
	}
	function sitemap($array)
	{
		header('Content-type: text/xml');

		if(file_exists(VAR_LIB.'/robots-sitemap/sitemap.xml'))
		{
			readfile(VAR_LIB.'/robots-sitemap/sitemap.xml');
			exit();
		}

		$proto='http';
		if(isset($_SERVER['HTTPS']))
			$proto='https';

		require TK_LIB.'/sitemap_generator.php';
		$sitemap=new sitemap_generator([
			'url'=>$proto.'://'.$_SERVER['HTTP_HOST'],
			'default_tags'=>[
				'lastmod'=>date('Y-m-d'),
				'changefreq'=>'monthly',
				'priority'=>'0.5'
			]
		]);

		foreach($array as $item)
			$sitemap->add($item);

		$xml=$sitemap->get();

		if(!file_exists(VAR_LIB.'/robots-sitemap'))
			mkdir(VAR_LIB.'/robots-sitemap');

		file_put_contents(VAR_LIB.'/robots-sitemap/sitemap.xml', $xml);

		echo $xml;
	}
?>