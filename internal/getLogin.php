<?php

require_once('pureGetLogin.php');

echo (
<<<EOT
var pageId = $pageId;
var publicKey = $publicKey;
var loggedin = $loggedin;
EOT
);