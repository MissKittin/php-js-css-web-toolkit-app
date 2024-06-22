<!DOCTYPE html>
<html<?php if(isset($view['lang'])) echo ' lang="'.$view['lang'].'"'; ?>>
	<head>
		<?php if(isset($view['title'])) echo '<title>'.$view['title'].'</title>'; ?>
		<meta charset="utf-8">
		<?php self::parse_headers($view); ?>
		<link rel="stylesheet" href="<?php echo static::$assets_path; ?>/<?php echo static::$assets_filename; ?>.css">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<?php if(static::$favicon !== null) readfile(static::$favicon); ?>
	</head>
	<body>
		<?php
			if(substr($page_content, -4) === '.php')
				require $view_path.'/'.$page_content;
			else
				readfile($view_path.'/'.$page_content);
		?>
		<script src="<?php echo static::$assets_path; ?>/<?php echo static::$assets_filename; ?>.js"></script>
		<?php
			if(isset($view['scripts']))
				foreach($view['scripts'] as $script)
					{ ?><script src="<?php echo $script; ?>"></script><?php }
		?>
	</body>
</html>