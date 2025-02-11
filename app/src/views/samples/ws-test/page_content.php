<form>
	Server address: <input id="addr" type="text"><br>
	Server path: <input id="path" type="text"><br>
	ws:// <input type="radio" name="proto" value="ws" checked><input type="radio" name="proto" value="wss"> wss://<br>
	<input id="test" type="button" value="Test">
</form>
<hr>

<h1 id="ws-addr">WS address:</h1>
<h1 id="output">WebSocket test not started</h1>
<hr>

<h3>Websockets functions:</h3>
<pre style="tab-size: 4;">
&lt;?php
	function websockets_main($client)
	{
		while(true)
			switch($client->read())
			{
				case "get_time":
					$client->write(json_encode(
						["get_time", time()]
					));
				break;
				case "exit":
					$client->exit();
			}
	}
	function websockets_debug($message)
	{
		echo '[D] '.$message.PHP_EOL;
	}
?&gt;
</pre>

<script>
	<?php readfile(__DIR__.'/ws-test.js'); ?>
</script>

<?php php_debugbar::get_page_content(); ?>