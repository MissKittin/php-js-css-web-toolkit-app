<?php
	if(file_exists(APP_ROOT.'/README.md'))
		echo '<div id="readme-app"><h1>About app</h1><pre>'.htmlspecialchars(file_get_contents(APP_ROOT.'/README.md')).'</pre></div>';
	if(file_exists(APP_ROOT.'/tk/README.md'))
		echo '<div id="readme-tk"><h1>About toolkit</h1><pre>'.htmlspecialchars(file_get_contents(APP_ROOT.'/tk/README.md')).'</pre></div>';
	if(file_exists(APP_ROOT.'/HOWTO.md'))
		echo '<div id="howto"><h1>How to</h1><pre>'.htmlspecialchars(file_get_contents(APP_ROOT.'/HOWTO.md')).'</pre></div>';
	if(file_exists(APP_ROOT.'/LICENSE'))
		echo '<div id="license"><h1>License</h1><pre>'.htmlspecialchars(file_get_contents(APP_ROOT.'/LICENSE')).'</pre></div>';
?>
<script>
	// this script is created for toolkit readme only
	window.addEventListener('DOMContentLoaded', function(){
		<?php readfile(__DIR__.'/markdown.js'); ?>
		format_readme(document.getElementById('readme-app').children[1], document.getElementById('readme-app'));
		format_readme(document.getElementById('readme-tk').children[1], document.getElementById('readme-tk'));
		format_readme(document.getElementById('howto').children[1], document.getElementById('howto'));
		format_license(document.getElementById('license'));
	});
</script>