<!DOCTYPE html>
<html>
<head>	
<title>UX HTTP</title>
<script src="UX.js"></script>
</head>
<body>

<div id="app">
<h1>UX DEMO</h1>
 <p>{{blogTitle}}</p>
  <div>
    <div :loop="blogs">
	<div>
	    <h1>{{component_title}}</h1>
	    <p>{{component_text}}</p>
	 </div>
  </div>
</div>
	
	<script>

	let app = new UX();
	
	app.http('../API.php', 'callback', mycall);
	
	function mycall(dataset) {
		app.load({
			data: {
				blogTitle: 'HTTP test',
				blogs: JSON.parse(dataset)
			}
		});
	}
	</script>
</body>
</html>
