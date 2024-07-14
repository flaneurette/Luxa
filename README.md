# Plain UI
Plain UI is a simple yet modern headless CMS, which outputs JSON data through an API.

<img src="https://github.com/flaneurette/Plain-UI/blob/main/assets/demo.png" />

# Installation

1. Edit /resources/PHP/Class.DB.php and define the database and paths.
2. Import the PlainUI SQL file, goto the index page and login with: username: admin, password: admin. 

(Be sure to use a different Bcrypt password on a production setting.)

# Uses
It could be used with a JavaScript framework like VUE.js, React or UX.js, which also accepts JSON data objects:
https://github.com/flaneurette/UX.js

# DEMO with UX.js
With UX.js, the following code will fetch all JSON data from the API:

	<script>
	let app = new UX();
	app.http('API.php', 'callback', mycall);
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
		<h1>{{snippet_title}}</h1>
		<p>{{snippet_text}}</p>
	</div>
    </ul>
  </div>
</div>
```
