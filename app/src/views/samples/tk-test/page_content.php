<?php
	foreach($view['tests'] as $type=>$tests)
	{
		echo '<h1>'.$type.'</h1>';

		foreach($tests as $test_name)
			echo '<a href="?type='.$type.'&name='.$test_name.'" target="_blank">'.$test_name.'</a><br>';
	}

	echo '<h1>Components</h1>';
	foreach($view['com_tests'] as $com_name=>$tests)
	{
		echo '<h3>'.$com_name.'</h3>';

		foreach($tests as $test_name)
			echo '<a href="?type=com&com='.$com_name.'&name='.$test_name.'" target="_blank">'.$test_name.'</a><br>';
	}

	php_debugbar::get_page_content();
?>