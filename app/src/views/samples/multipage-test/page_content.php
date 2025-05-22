<style><?php readfile(__DIR__.'/main.css'); ?></style>
<noscript>
	<h1>Enable JavaScript</h1>
</noscript>
<div id="menu">
	<a href="#!home">Home</a> |
	<a href="#!about">About</a> |
	<a href="#!products">Products</a> |
	<a id="menu_api" href="#!api">API</a> |
	<a href="#!nonexistent">Error</a><br>
</div>
<div id="app">
	<div id="home">
		<h1 class="page_title">Home</h1>
		<?php
			echo generate_lorem_ipsum_wp(
				20, 5,
				true, false,
				'<p>', '</p>'
			);
		?>
	</div>
	<div id="about">
		<h1 class="page_title">About us</h1>
		<?php
			echo generate_lorem_ipsum_wp(
				20, 5,
				true, false,
				'<p>', '</p>'
			);
		?>
	</div>
	<div id="products">
		<h1 class="page_title">Our products</h1>
		<?php
			echo generate_lorem_ipsum_wp(
				20, 5,
				true, false,
				'<p>', '</p>'
			);
		?>
	</div>
	<div id="api"></div>
	<div id="not_found">
		<h1>Page not found!</h1>
	</div>
</div>
<script><?php readfile(__DIR__.'/main.js'); ?></script>
<script><?php readfile(TK_LIB.'/getJson.js'); ?></script>
<?php php_debugbar::get_page_content(); ?>