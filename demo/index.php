<!DOCTYPE html>
<html>
<head>
	
<title>UX HTTP</title>
<style>
.component {
	display:block; 
	clear:left; 
	padding: 20px;
}

img {
	float:left; 
	padding-right: 20px;
}

</style>
<script src="UX.js"></script>
</head>
<body>

<div id="app">
<h1>UX DEMO</h1>
 <p>{{blogTitle}}</p>
  <div>
    <div :loop="blogs">
	<div class="component">
		 <p><img src="{{component_image}}" /></p>
		  <h1>{{component_title}}</h1>
		 <p>{{component_text}}</p>
	 </div>
  </div>
</div>
	
	<script>

	let app = new UX();
	
	app.http('../API.php', 'callback', mycall);
	
	function mycall(dataset) {
		let data = dataset.replaceAll('&gt;','>').replaceAll('&lt;','<');
		app.load({
			data: {
				blogTitle: 'HTTP test',
				blogs: JSON.parse(data)
			}
		});
	}
	</script>
</body>
</html>