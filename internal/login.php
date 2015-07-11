<?php
require_once('dataObject.php');
require_once('User.php');
require_once('Student.php');
require_once('Tutor.php');
require_once('Admin.php');
require_once('misc.php');
require_once('allRoundConfig.php');

final class login extends dataObject{

	const className='login';
	const classFields=array('userPointer','userClass');

	private final function scheduleDestroy(){}
	
	public static final function getLogin($username, $password){
		$loginUser = null;
		foreach(json_decode(userClasses) as $userClass){
			foreach ($userClass::getInstances() as $user){
				if ($user->getUsername() == $username &&
						$user->getPassword() == $password){
							$loginUser = $user->getName();
							$loginUserClass = $userClass;
							break 2;}}}
		
		if ($loginUser === null) return null;
		while (login::fetchInstance($name=getRandomString())!==null){}
		
		$login = login::getInstance($name);
		
		$login->setField('userPointer',$loginUser);
		$login->setField('userClass',$loginUserClass);
		
		$login->scheduleDestroy();
		return $login;}	

	public final function getUser(){
		$userPointer = $this->getField('userPointer');
		$userClass = $this->getField('userClass');
		return $userClass::fetchInstance($userPointer);}}