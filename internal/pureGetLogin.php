<?php

require_once('page.php');
$page = null;
$login = null;

if (isset($_GET['pageId']) &&
		isset($_GET['username']) &&
		isset($_GET['password'])){
	$page = page::fetchInstance($_GET['pageId']);}

if ($page !== null) {
	$username = $page->getEncryption()->decrypt($_GET['username']);
	$password = $page->getEncryption()->decrypt($_GET['password']);
	
	$login = login::getLogin($username, $password);}

if ($login !== null){
	$page = page::getPage($login);}
else {
	$login = login::getInstance('');
	$page = page::getPage($login);
	$login->destroy();
	$login=null;}

$pageId=json_encode($page->getName());
$publicKey=json_encode($page->getEncryption()->getPublicKey());

$loggedin = ($login===null ? 'false' : 'true');

function loggedin_eval($pageContent,$pageUncontent){
	if ($GLOBALS['loggedin']==='true') eval($pageContent);
	else eval($pageUncontent);}
function loggedin_echo($pageContent,$pageUncontent){
	if ($GLOBALS['loggedin']==='true') echo($pageContent);
	else echo($pageUncontent);}