<?php
	function get_tests($view)
	{
		$tests=[];
		$com_tests=[];

		foreach(['bin', 'lib'] as $type)
			foreach(
				array_diff(scandir(APP_ROOT.'/tk/'.$type.'/tests'), ['.', '..'])
				as $test_name
			)
				if(is_file(APP_ROOT.'/tk/'.$type.'/tests/'.$test_name))
					$tests[$type][]=$test_name;

		foreach(
			array_diff(scandir(TK_COM), ['.', '..'])
			as $com_name
		)
			if(is_dir(TK_COM.'/'.$com_name.'/tests'))
				foreach(
					array_diff(scandir(TK_COM.'/'.$com_name.'/tests'), ['.', '..'])
					as $test_name
				)
					if(is_file(TK_COM.'/'.$com_name.'/tests/'.$test_name))
						$com_tests[$com_name][]=$test_name;

		$view['tests']=$tests;
		$view['com_tests']=$com_tests;

		return $view;
	}
	function run_test($type, $test_name)
	{
		if(
			str_contains($type, '/') ||
			str_contains($type, '\\') ||
			str_contains($test_name, '/') ||
			str_contains($test_name, '\\')
		)
			return false;

		switch($type)
		{
			case 'bin':
			case 'lib':
			break;
			case 'com':
				$com=check_get_escaped('com');

				if($com !== null)
				{
					if(
						str_contains($com, '/') ||
						str_contains($com, '\\')
					)
						return false;

					$type='com/'.$com;
					break;
				}

				return false;
			default:
				return false;
		}

		if(!is_file(APP_ROOT.'/tk/'.$type.'/tests/'.$test_name))
			return false;

		if(str_ends_with($test_name, '.phtml'))
		{
			include APP_ROOT.'/tk/'.$type.'/tests/'.$test_name;
			return true;
		}

		echo '<pre>';
		include APP_ROOT.'/tk/'.$type.'/tests/'.$test_name;
		echo '</pre>';

		return true;
	}
?>