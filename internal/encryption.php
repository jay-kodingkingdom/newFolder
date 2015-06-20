<?php

require_once('dataObject.php');
require_once('misc.php');

final class encryption extends dataObject{
	const config = array(
				"private_key_bits"=>512);
	
	const className='encryption';
	const classFields=array('publicKey','privateKey');

	public final function getEncryption(){
		while (encryption::fetchInstance($name=getRandomString())!==null){}

		$encryption = encryption::getInstance($name);

		$password = null;
				
		$newKeyPair=openssl_pkey_new(encryption::config);
		openssl_pkey_export($newKeyPair, $privateKey, $password);
		$publicKey=openssl_pkey_get_details($newKeyPair)["key"];
		
		$encryption->setField('publicKey',$publicKey);
		$encryption->setField('privateKey',$privateKey);
		return $encryption;}

	public final function encrypt($plainText){
		$publicEncrypter=openssl_pkey_get_public($this->getPublicKey());		
		$cipherText = "";
		openssl_public_encrypt($plainText,$cipherText,$publicEncrypter);
		return $cipherText;}
		
	public final function decrypt($cipherText){
		$privateDecrypter = openssl_pkey_get_private($this->getPrivateKey(), "");
		$plainText = "";
		openssl_private_decrypt($cipherText, $plainText, $privateDecrypter);
		return $plainText;}
		
	public final function getPublicKey(){
		return $this->getField('publicKey');}
	public final function getPrivateKey(){
		return $this->getField('privateKey');}}