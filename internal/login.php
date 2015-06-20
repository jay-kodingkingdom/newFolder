<?php

require_once('dataObject.php');
require_once('User.php');
require_once('misc.php');
require_once('allRoundConfig.php');

final class login extends dataObject{

	const className='login';
	const classFields=array('user','userClass');
		
	public final function getLogin($username, $password){
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