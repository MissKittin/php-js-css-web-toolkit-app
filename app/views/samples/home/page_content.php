<h1>PHP-JS-CSS web toolkit</h1>
<script>
	document.addEventListener('DOMContentLoaded', function(){
		sendNotification('sendNotification() works', 'Your nice web app', '');
	});
</script>
<?php require __DIR__.'/home_print_menu.php'; home_print_menu($view['home_links']); ?>