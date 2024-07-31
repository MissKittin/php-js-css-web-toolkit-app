<?php
	// Run PHP tests in batch mode

	foreach(array_slice(scandir(__DIR__.'/../tests'), 2) as $test_section)
		if(is_dir(__DIR__.'/../tests/'.$test_section))
			foreach(new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(
					__DIR__.'/../tests/'.$test_section,
					RecursiveDirectoryIterator::SKIP_DOTS
				)
			) as $test){
				echo '-> Running '.substr($test->getPathname(), strlen(__DIR__.'/../tests/')).PHP_EOL;

				system('"'.PHP_BINARY.'" "'.$test->getPathname().'"', $test_result);

				if($test_result !== 0)
					$failed_tests[]=$test;

				echo PHP_EOL;
			}

	if(!empty($failed_tests))
	{
		echo PHP_EOL;

		foreach($failed_tests as $test)
			echo 'Test '.$test.' failed'.PHP_EOL;

		exit(1);
	}
?>