<?php
	require TK_LIB.'/check_date.php';

	$view['first-question']=false;
	$view['second-question']=false;

	if(check_date(23,6, 12,8))
		$view['first-question']=true;

	if(check_date(14,9, 23,4))
		$view['second-question']=true;
?>