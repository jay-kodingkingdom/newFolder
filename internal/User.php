<?php

require_once 'dataObject.php';

class User extends dataObject{
	
	const className='User';
	const classFields=array('username','password');


	public final function setUsername($username){
		if (getUsername()===null)
			$this->setField('username',$username);}
	public final function setPassword($password){
		if (getPassword()===null)
			$this->setField('password',$password);}
	
	public final function getUsername(){
		return $this->getField('username');}
	public final function getPassword(){
		return $this->getField('password');}
}