<!DOCTYPE html>
<html lang="en">
	<head>
		<title>HTTP 410 Gone</title>
		<meta charset="utf-8">
		<meta name="robots" content="noindex,nofollow">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<style>
			body {
				color: #000000;
				background-color: #ffffff;
				font: 8pt/11pt verdana;
			}
			a:link {
				color: #ff0000;
			}
			a:visited {
				color: #4e4e4e;
			}
			table {
				width: 400px;
				padding-left: 5px;
				border-spacing: 5px;
			}
			td {
				padding-bottom: 5px;
			}
			#td_img_info {
				vertical-align: bottom;
				text-align: left;
			}
			#td_header {
				vertical-align: middle;
				text-align: left;
				width: 360px;
			}
			#td_header h1 {
				padding-left: 5px;
			}
			h1 {
				font: 13pt/15pt verdana;
			}
			hr {
				background-color: #c0c0c0;
				border-width: 0;
				height: 2px;
				margin-bottom: 25px;
			}
			#try_steps {
				margin-bottom: 20px;
			}
			#img_refresh {
				vertical-align: middle;
			}
			#img_back {
				vertical-align: bottom;
			}
			h2 {
				font: 8pt/11pt verdana;
			}
		</style>
	</head>
	<body>
		<table>
			<tr>
				<td id="td_img_info">
					<img alt="i" src="data:image/bmp;base64,Qk3yAwAAAAAAAFYAAAAoAAAAGQAAACEAAAABAAgAAAAAAJwDAAAAAAAAAAAAAAgAAAAIAAAA////AMzMzACAgIAA/wAAAAAAAAAAAAAAAAAAAAAAAAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEAAYAAgEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBBABDAAIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQQA3QACAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEEAOIAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAABBAFaAQIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQQABAACAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEEAAQAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAABBAFaAQIAAAAAAAAAAwMDAwMDAwMDAAAAAAAAAQQABAACAAAAAAAAAAABAwMDAwMBAAAAAAAAAAEEAVoBAgAAAAAAAAAAAAMDAwMDAAAAAAAAAAABBIAuggIAAAAAAAAAAAADAwMDAwAAAAAAAAAAAQSALoICAAAAAAAAAAAAAwMDAwMAAAAAAAAAAAEE1N0AAgAAAAAAAAAAAAMDAwMDAAAAAAAAAAABBHdxdwIAAAAAAAAAAAADAwMDAwAAAAAAAAAAAQR3d3cCAAAAAAAAAAABAwMDAwMAAAAAAAAAAAEE3d3QAgAAAAAAAAADAwMDAwMDAAAAAAAAAAABBN3d3QIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQQHtwcCAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEE3d3dAgAAAAAAAAAAAQIDAwIBAAAAAAAAAAABBAAN0AIAAAAAAAAAAAIDAwMDAgAAAAAAAAAAAQTd3dQCAAAAAAAAAAACAwMDAwIAAAAAAAAAAAEEd3d3AgAAAAAAAAAAAQIDAwIBAAAAAAAAAAABBITQDQIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQR3cXcCAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEEcNDXAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAABBETd0AIAAAAAAAAAAAAAAAAAAAAAAAQEBAQEBAR3d3cCAAAAAAAAAAAAAAAAAAAAAAACAAABAgQAR3F3AgAAAAAAAAAAAAAAAAAAAAAAAgABAgQAALe3twIAAAAAAAAAAAAAAAAAAAAAAAIBAgQAAABN3dACAAAAAAAAAAAAAAAAAAAAAAACAgQAAAAARN1NAgAAAAAAAAAAAAAAAAAAAAAAAgQAAAAAAAAAAAICAgICAgICAgICAgICAgICAgIAAAAAAADYTQA=">
				</td>
				<td id="td_header">
					<h1>The page does not exist</h1>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					The page you are looking for has been removed.
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<hr>
					<p id="try_steps">You might find the information you need by opening the <a href="/"><?php echo $_SERVER['HTTP_HOST']; ?></a> home page and then linking to a similiar page.</p>
					<p><br></p>
					<h2>HTTP Error 410 - Permanently not available</h2>
				</td>
			</tr>
		</table>
	</body>
</html>