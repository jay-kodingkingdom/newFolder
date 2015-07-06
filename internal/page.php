<?php

require_once('dataObject.php');
require_once('login.php');
require_once('encryption.php');

final class page extends dataObject{

	const className='page';
	const classFields=array('loginPointer','encryptionPointer');

	private final function scheduleDestroy(){}
	
	public static final function getPage(login $login){
		while (page::fetchInstance($name=getRandomString())!==null){}

		$page = page::getInstance($name);

		$page->setField('loginPointer',$login->getName());
		$page->setField('encryptionPointer',encryption::getEncryption()->getName());

		$page->scheduleDestroy();
		return $page;}


	public final function getEncryption(){
		return encryption::fetchInstance($this->getField('encryptionPointer'));}
		
	public final function getLogin(){
		return Login::fetchInstance($this->getField('loginPointer'));}}