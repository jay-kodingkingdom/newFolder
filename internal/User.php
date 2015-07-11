<?php

require_once('dataObject.php');
require_once('misc.php');

class User extends dataObject{
	
	const className='User';
	const classFields=array('username','password');


	public final function setUsername($username){
		if ($this->getUsername()===null)
			$this->setField('username',$username);}
	public final function setPassword($password){
		if ($this->getPassword()===null)
			$this->setField('password',$password);}
	
	public final function getUsername(){
		return $this->getField('username');}
	public final function getPassword(){
		return $this->getField('password');}

	public static final function getUser($username, $password){
		$user = static::fetchUser($username, $password);
		if ($user === null) {
			while (static::fetchInstance($name=getRandomString())!==null){}
			$user = static::getInstance($name);
			$user->setUsername($username);
			$user->setPassword($password);}
		return $user;}
	public static final function fetchUser($username, $password){
		$objClass = static::className;

		foreach ($objClass::getInstances() as $user){
			if ($user->getUsername() == $username &&
					$user->getPassword() == $password)
						return $user;}
		return null;}}
		
trait contactInfo {

	public final function setRealname($realname){
		$this->setField('realname',$realname);}
	public final function getRealname(){
		return $this->getField('realname');}
	public final function setEmail($email){
		$this->setField('email',$email);}
	public final function getEmail(){
		return $this->getField('email');}
	public final function setMobileNumber($mobileNumber){
		$this->setField('mobileNumber',$mobileNumber);}
	public final function getMobileNumber(){
		return $this->getField('mobileNumber');}}