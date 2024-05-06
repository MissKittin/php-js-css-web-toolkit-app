<?php
	/*
	 * An overlay for the default template
	 * that saves typing
	 */

	require APP_TEMPL.'/samples/default/default_template.php';

	class app_template extends default_template
	{
		public static function quick_view($view_path, $page_content='page_content.php')
		{
			parent::{__FUNCTION__}(
				APP_VIEW.'/samples/'.$view_path,
				$page_content
			);
		}

		public function view($view_path, $page_content='page_content.php')
		{
			parent::{__FUNCTION__}(
				APP_VIEW.'/samples/'.$view_path,
				$page_content
			);
		}
	}
?>