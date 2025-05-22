document.addEventListener('DOMContentLoaded', function(){
	document.getElementById('menu').style.display='block';

	var apiCall=function()
	{
		getJson(window.location.origin+window.location.pathname+'/api', 'get', function(error, response){
			if(error != null)
			{
				console.error(error);
				return;
			}

			document.getElementById('api').innerHTML=''
			+	'<h1 class="page_title">API test</h1>'
			+	response.text+'<br>'
			+	'The number drawn: '+response.number;
		});

		apiCall=function()
		{
			console.log(
				'Data from API has already been loaded'
			);
		};
	};

	if(window.location.hash === '#!api')
		apiCall();

	document.getElementById('menu_api').addEventListener('click', function(){
		apiCall();
	}, true);

	multipage('app', 'home', 'not_found');
}, false);