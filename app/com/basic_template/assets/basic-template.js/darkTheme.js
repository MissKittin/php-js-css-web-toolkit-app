if(
	window.matchMedia &&
	window.matchMedia('(prefers-color-scheme: dark)').matches &&
	(getCookie('app_dark_theme') !== 'true')
)
	document.cookie='app_dark_theme=true';