<?php

set_include_path('./phpseclib');
require_once('dataObject.php');
require_once('User.php');
require_once('misc.php');
require_once('allRoundConfig.php');
require_once('Crypt/RSA.php');

final class login extends dataObject{

	const className='login';
	const classFields=array('user','userClass');
		
	public final function fetchLogin($username, $password){
		$loginUser = null;
		foreach(userClasses as $userClass){
			foreach ($userClass::getInstances() as $user){
				if ($user->getUsername() == $username &&
						$user->getPassword() == $password){
							$loginUser = $user;
							$loginUserClass = $userClass;
							break 2;}}}
		
		if ($loginUser === null) return null;
		while (login::fetchInstance($name=getRandomString())!==null){}
		
		$login = login::getInstance($name);
		
		$login->setField('user',$loginUser);
		$login->setField('userClass',$loginUserClass);
		return $login;}	

	public final function getUser(){
		return $this->getField('user');}
	public final function getUserClass(){
		return $this->getField('userClass');}}

function rsa_encrypt($string, $public_key){
	$cipher = new Crypt_RSA();
	$cipher->loadKey($public_key);
	$cipher->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
	return base64_encode($cipher->encrypt($string));}

function rsa_decrypt($string, $private_key){
	$cipher = new Crypt_RSA();
	$cipher->loadKey($private_key);
	$cipher->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
	return $cipher->decrypt($string);}