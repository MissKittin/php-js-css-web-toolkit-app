<?php
	function home_print_menu($links, $use_line_break=true)
	{
		static $link_prefix=null;

		if($link_prefix === null)
		{
			$link_prefix=strtok($_SERVER['REQUEST_URI'], '?');

			if($link_prefix === '/')
				$link_prefix='';
			else if(substr($link_prefix, -1) === '/')
				$link_prefix=substr($link_prefix, 0, -1);
		}

		$link_class=' class="link_no_arrow"';
		$line_break=' ';

		if($use_line_break)
		{
			$link_class='';
			$line_break='<br>';
		}

		foreach($links as $link_label=>$link_url)
			if(is_array($link_url))
			{
				echo $link_label.' ';
				(__FUNCTION__)($link_url, false);
				echo $line_break;
			}
			else
				echo '<a href="'.$link_prefix.$link_url.'"'.$link_class.'>'.$link_label.'</a>'.$line_break;
	}
?>