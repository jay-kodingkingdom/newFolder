<?php

require_once('User.php');

final class Admin extends User{
	
	const className='Admin';
	const classFields=array('username','password',
			'displayname');
	
	public final function setDisplayname($displayname){
		$this->setField('displayname',$displayname);}
	public final function getDisplayname(){
		return $this->getField('displayname');}

	public static final function getAdmin($username, $password, $displayname){
		$admin = Admin::fetchUser($username, $password, $displayname);
		if ($admin === null) {
			$admin = Admin::getUser($username, $password);
			$admin->setDisplayName($displayname);}
		return $admin;}
	public static final function fetchAdmin($username, $password, $displayname){
		foreach (Admin::getInstances() as $admin){
			if ($admin->getUsername() == $username &&
					$admin->getPassword() == $password &&
					$admin->getDisplayname() == $displayname)
						return $admin;}
		return null;}}