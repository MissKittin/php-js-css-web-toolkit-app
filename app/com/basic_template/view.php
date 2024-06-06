<!DOCTYPE html>
<html<?php if(isset($view['lang'])) echo ' lang="'.$view['lang'].'"'; ?>>
	<head>
		<title><?php if(isset($view['title'])) echo $view['title']; ?></title>
		<meta charset="utf-8">
		<?php self::parse_headers($view); ?>
		<link rel="stylesheet" href="/assets/default_bright.css">
		<noscript><link rel="stylesheet" href="/assets/default_bright_noscript.css"></noscript>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<?php if(is_file(__DIR__.'/favicon.html')) readfile(__DIR__.'/favicon.html'); ?>
	</head>
	<body>
		<?php
			if(str_ends_with($page_content, '.php'))
				require $view_path.'/'.$page_content;
			else
				readfile($view_path.'/'.$page_content);
		?>
		<script src="/assets/default.js"></script>
		<?php
			if(isset($view['scripts']))
				foreach($view['scripts'] as $script)
					{ ?><script src="<?php echo $script; ?>"></script><?php }
		?>
	</body>
</html>