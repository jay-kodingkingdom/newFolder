<?php
	require_once('../debug.php');
	require_once('../internal/pureFetchLogin.php');
	if ($loggedin==='true'
			&& $login->getUser()->getClassName()==='Admin')
		header('Content-Type: text/html; charset=utf-8;');
	else {
		header('Location: '.'log_in.php');
		exit();} ?>
		
<!DOCTYPE html>
<html>

	<head>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		
		<script src="../debug.js"></script>
		<script type="text/javascript">
			<?php
				require_once('../internal/fetchLogin.php');?> </script>
		<script type="text/javascript">
		function sendCode() {

			var request = new XMLHttpRequest();
		    request.onreadystatechange = function() {
		        if (request.readyState == 4
			        	&& request.status == 200) {
		        	
		        	document.getElementById('output').innerHTML += request.responseText+'<br>';}}; 
		    request.open("GET", "api/evalPhp.php?pageId=<?php echo json_decode($pageId);
		    											?>&code="+encodeURIComponent(document.getElementById("code").value));
		    request.send();
		 	return false;}
				</script> </head>

<body><pre>

<p style="font-family:'Courier New'; margin-left: 10%">Console In:</p>
<center><textarea id="code" rows="8" cols="100" wrap="soft" style="font-family:'Courier New';border: 1px solid #999999; width: 80%;"></textarea></center>

<form name="input" onSubmit='return sendCode();' style="margin-left: 10%">
		    <input name='submit' type='Submit' value='Eval' style="font-family:'Courier New'"></form>
<br>
<p style="font-family:'Courier New'; margin-left: 10%">Console Out:</p>
<div id="output" style="font-family:'Courier New'; margin: 0 auto;border: 1px solid #999999; height: 200px; width:80%; word-wrap: break-word; overflow: scroll;">&nbsp;</div>
		    
		    </pre></body></html>