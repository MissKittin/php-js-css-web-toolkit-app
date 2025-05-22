<?php
	/*
	 * Memcached polyfill - memcached.php proxy
	 *
	 * Warning:
	 *  this library is deprecated
	 *  use the clickalicious_memcached.php toolkit library instead
	 */

	if(class_exists('Memcached'))
		return false;

	require TK_LIB.'/clickalicious_memcached.php';
?>