document.addEventListener('DOMContentLoaded', function(){
	<?php
		if(!defined('APP_STDLIB'))
		{
			if(file_exists(__DIR__.'/../../../../lib/stdlib.php'))
				require __DIR__.'/../../../../lib/stdlib.php';
			else
				require __DIR__.'/../../lib/stdlib.php';
		}
	?>
	<?php include TK_LIB.'/getCookie.js'; ?>
	<?php include __DIR__.'/darkTheme.js'; ?>
});