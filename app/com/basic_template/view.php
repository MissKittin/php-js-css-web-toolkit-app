<!DOCTYPE html>
<html<?php if(isset($view['lang'])) echo ' lang="'.$view['lang'].'"'; ?>>
	<head>
		<?php if(isset($view['title'])) echo '<title>'.$view['title'].'</title>'; ?>
		<meta charset="utf-8">
		<?php
			static::parse_headers($view);

			if(static::$inline_assets)
			{
				?><style nonce="mainstyle"><?php
					readfile(__DIR__.'/assets/basic-template.css');
				?></style><?php
			}
			else
				{ ?><link rel="stylesheet" href="<?php echo static::$assets_path; ?>/<?php echo static::$assets_filename; ?>.css"><?php }
		?>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<?php if(static::$favicon !== null) readfile(static::$favicon); ?>
	</head>
	<body>
		<?php
			if(substr($page_content, -4) === '.php')
				require $view_path.'/'.$page_content;
			else
				readfile($view_path.'/'.$page_content);

			if(static::$inline_assets)
			{
				?><script nonce="mainscript"><?php
					require __DIR__.'/assets/basic-template.js/main.php';
				?></script><?php
			}
			else
				{ ?><script src="<?php echo static::$assets_path; ?>/<?php echo static::$assets_filename; ?>.js"></script><?php }

			if(isset($view['scripts']))
				foreach($view['scripts'] as $script)
					{ ?><script src="<?php echo $script; ?>"></script><?php }
		?>
	</body>
</html>