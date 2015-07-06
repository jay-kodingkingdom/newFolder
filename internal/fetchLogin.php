<?php

require_once('pureFetchLogin.php');

loggedin_echo(	
<<<EOT
var pageId = $pageId;
var publicKey = $publicKey;
EOT
		,'');

echo (
<<<EOT
var loggedin = $loggedin;
EOT
);