<?php

require_once('page.php');

$page = null;
$login = null;

if (isset($_GET['pageId'])){
	$page = page::fetchInstance($_GET['pageId']);
	$encryption = $page->getEncryption();}

if ($page !== null){
	$login = $page->getLogin();
	$page = page::getPage($login);
		
	$pageId=json_encode($page->getName());
	$publicKey=json_encode($page->getEncryption()->getPublicKey());}
else{
	/*throw new Exception('Login not found');*/}

$loggedin = ($login===null ? 'false' : 'true');

function loggedin_eval($pageContent,$pageUncontent){
	if ($GLOBALS['loggedin']==='true') eval($pageContent);
	else eval($pageUncontent);}
function loggedin_echo($pageContent,$pageUncontent){
	if ($GLOBALS['loggedin']==='true') echo($pageContent);
	else echo($pageUncontent);}