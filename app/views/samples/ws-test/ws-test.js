document.getElementById('addr').value=window.location.host;

document.getElementById('test').addEventListener('click', function(){
	var proto;

	document.getElementById('output').innerHTML='WebSocket test starting';

	if(document.getElementsByName('proto')[0].checked)
		proto=document.getElementsByName('proto')[0].value;
	else if(document.getElementsByName('proto')[1].checked)
		proto=document.getElementsByName('proto')[1].value;

	document.getElementById('ws-addr').innerHTML='WS address: '+proto+'://'+document.getElementById('addr').value+document.getElementById('path').value;

	var socket=new WebSocket(proto+'://'+document.getElementById('addr').value+document.getElementById('path').value);

	socket.addEventListener('error', function(){
		document.getElementById('output').innerHTML='WebSocket test failed';
	});
	socket.addEventListener('open', function(){
		socket.send('get_time');
	});
	socket.addEventListener('message', function(event){
		var data=JSON.parse(event.data);

		if(data[0] === 'get_time')
			document.getElementById('output').innerHTML='Current timestamp: '+data[1];
		else
			document.getElementById('output').innerHTML='WebSocket test failed - bad response';
	});
}, false);