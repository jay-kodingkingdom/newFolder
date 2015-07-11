<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

define('rootPath', __DIR__);

function printdebug($obj){
	$approxVariableName=null;
	foreach($GLOBALS as $var_name => $value) {
		if ($value === $obj) {
			$approxVariableName=$var_name;
			break;}}
	echo '<br><br><br><br><br>';
	if ($approxVariableName===null) echo 'unknown expression';
	else echo 'approxVariableName '.$approxVariableName;

	if (is_object($obj)){
		$reflection=new ReflectionClass(get_class($obj));
		foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC)
				as $method){
			if ($method->getNumberOfRequiredParameters()==0){
				$methodName=$method->getName();
				if (substr($methodName, 0, strlen('get')) == 'get'){
					$result=$obj->$methodName();
					echo '<br><br><br><br><br>';
					echo $methodName;
					echo '<br><br>';
					var_dump($result);}}}}
	else {
		echo '<br><br><br><br><br>';
		var_dump($obj);}}