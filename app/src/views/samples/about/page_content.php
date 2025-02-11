<div id="loading_page_content">
	<h1>Formatting the document...</h1>
</div>

<div id="page_content">
	<?php if(file_exists(APP_ROOT.'/README.md')) { ?>
		<div id="readme-app" class="markdown">
			<h1>About app</h1>
			<pre><?php echo htmlspecialchars(file_get_contents(APP_ROOT.'/README.md')); ?></pre>
		</div>
	<?php } ?>

	<?php if(file_exists(APP_ROOT.'/tk/README.md')) { ?>
		<div id="readme-tk" class="markdown">
			<h1>About toolkit</h1>
			<pre><?php echo htmlspecialchars(file_get_contents(APP_ROOT.'/tk/README.md')); ?></pre>
		</div>
	<?php } ?>

	<hr>

	<?php if(file_exists(APP_ROOT.'/HOWTO.md')) { ?>
		<div id="howto" class="markdown">
			<h1>How to</h1>
			<pre><?php echo htmlspecialchars(file_get_contents(APP_ROOT.'/HOWTO.md')); ?></pre>
		</div>
	<?php } ?>

	<?php if(file_exists(APP_ROOT.'/LICENSE.MIT')) { ?>
		<div class="license">
			<h1>License</h1>
			<pre><?php echo htmlspecialchars(file_get_contents(APP_ROOT.'/LICENSE.MIT')); ?></pre>
		</div>
	<?php } ?>
	<?php if(file_exists(APP_ROOT.'/LICENSE.GPL-2.0-only')) { ?>
		<div class="license">
			<h1>GPL License</h1>
			<pre><?php echo htmlspecialchars(file_get_contents(APP_ROOT.'/LICENSE.GPL-2.0-only')); ?></pre>
		</div>
	<?php } ?>

	<?php if(file_exists(TK_LIB.'/../LICENSE')) { ?>
		<div class="license">
			<h1>Toolkit License</h1>
			<pre><?php echo htmlspecialchars(file_get_contents(TK_LIB.'/../LICENSE')); ?></pre>
		</div>
	<?php } ?>
</div>

<style><?php readfile(__DIR__.'/markdown.css'); ?></style>
<script><?php readfile(__DIR__.'/markdown.js'); ?></script>
<?php php_debugbar::get_page_content(); ?>