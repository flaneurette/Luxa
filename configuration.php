<?php

// This must be defined.
define('SITE','https://localhost/cms/'); 
define('UPLOAD_DIR', '../resources/content/');
define('CHARSET', 'utf8');
define('HOST', 'localhost');
define('DATABASE', 'Luxa');
define('USERNAME', 'root');
define('PASSWORD', '');
define('MAX_LOGIN_ATTEMPTS', 10);

/*

Security notice:
It is somewhat safer to store this file below the /www/ folder in case PHP shuts down and files become readable.
To do this, edit the paths to this file all PHP pages, and move this file below /www/

*/
?>