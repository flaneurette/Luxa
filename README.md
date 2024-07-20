# Plain UI
Plain UI is a simple yet modern headless CMS, which outputs JSON, CSV or Array data through an API.

<img src="https://github.com/flaneurette/Plain-UI/blob/main/assets/adminscreen.png" />

# Installation

1. Edit /resources/PHP/Class.DB.php and define the database and paths.
2. Import the PlainUI SQL file, goto the index page and login with: username: admin, password: admin.

# Requirements
PHP, MYSQL, mod_rewrite

# API
By default, the API writes JSON data. Possible outputs:

1. filetype=csv will output CSV
2. filetype=array will output an array
3. filetype=json (or blank) will output JSON

   Example: https://localhost/CMS/API.php?filetype=csv&id=1
   will fetch the index.html in CSV format. (Note that all HTMLentities are encoded, and can be decoded by JavaScript as HTML is allowed in PlainUI.)
   
# Uses
It could be used with a JavaScript framework like VUE.js, React or UX.js, which also accepts JSON data objects:
https://github.com/flaneurette/UX.js

# Demo with UX.js
With UX.js, the following code will fetch all JSON data from the API:

	<script>
	let app = new UX();
	app.http('API.php?filetype=json', 'callback', mycall);
	function mycall(dataset) {
		app.load({
			data: {
				blogTitle: 'HTTP test',
				blogs: JSON.parse(dataset)
			}
		});
	}
	</script>

Which can then be accessed:
```
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
```
