<!DOCTYPE html>
<html<?php if(isset($view['lang'])) echo ' lang="'.strtok($view['lang'], '_').'"'; ?>>
	<head<?php if(isset($view['head_prefix'])) echo ' prefix="'.$view['head_prefix'].'"'; ?>>
		<?php if(isset($view['title'])) echo '<title>'.$view['title'].'</title>'; ?>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<?php static::parse_headers_top($view); ?>
	</head>
	<body>
		<?php
			if(static::$templating_engine !== null)
				static::$templating_engine[0](
					$view_path.'/'.$page_content,
					$view
				);
			else if(substr($page_content, -4) === '.php')
				require $view_path.'/'.$page_content;
			else
				readfile($view_path.'/'.$page_content);

			static::parse_headers_bottom($view);
		?>
	</body>
</html>