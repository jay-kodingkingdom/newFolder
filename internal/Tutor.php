<?php

require_once('User.php');

final class Tutor extends User{
	
	const className='Tutor';
	const classFields=array('username','password',
			'displayname','timeSlotSchedulesPointers');
	
	public final function setDisplayname($displayname){
		$this->setField('displayname',$displayname);}
	public final function getDisplayname(){
		return $this->getField('displayname');}

	public static final function getTutor($username, $password, $displayname){
		$admin = Admin::fetchUser($username, $password, $displayname);
		if ($admin === null) {
			$admin = Tutor::getUser($username, $password);
			$admin->setDisplayName($displayname);}
		return $admin;}
	public static final function fetchTutor($username, $password, $displayname){
		foreach (Tutor::getInstances() as $admin){
			if ($admin->getUsername() == $username &&
					$admin->getPassword() == $password &&
					$admin->getDisplayname() == $displayname)
						return $admin;}
		return null;}}