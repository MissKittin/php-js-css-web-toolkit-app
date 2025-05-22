if(
	window.matchMedia &&
	(getCookie('app_theme') === undefined)
){
	if(window.matchMedia('(prefers-color-scheme: dark)').matches)
		document.cookie='app_theme=dark';
	else if(window.matchMedia('(prefers-color-scheme: light)').matches)
		document.cookie='app_theme=light';
}