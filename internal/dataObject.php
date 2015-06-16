<?php

require_once('dataObjectConfig.php');

final class dataObject{

	protected static $className;
	protected static $classFields;

	private $name;

	public function getName(){
		return $this->name;}
		
	public static function getFields(){
		return static::$classFields;}
	public static function getClassName(){
		return static::$className;}
		
	private static $currentObjs=array();	
	private static $dataObjectsDatabase=false;
	
	private static function getDatabase(){
		if (!dataObject::$dataObjectsDatabase){
			dataObject::$dataObjectsDatabase = new mysqli (dataObjectsServer, dataObjectsServerUsername, dataObjectsServerPassword,dataObjectsDatabase);}
		if (dataObject::$dataObjectsDatabase->connect_error)
			throw new Exception('Database connect error');
		return dataObject::$dataObjectsDatabase;}

	private static function fetchResult($question){
		$result = dataObject::getDatabase()->query($question);
		if (!$result)
			throw new Exception('Database query error');
		return $result;}
	private static function getResult($question){
		$result = dataObject::getDatabase()->query($question);
		return $result;}	
		
	public static final function fetchObject($objName){
		$hasKey= function(&$array, $key){
			if (!is_array($array)){
				$array=array();}
				return array_key_exists($key, $array);};
		$hasExists= function($rows){
			if (!isset($rows->num_rows)) return false;
			return $rows->num_rows;};
			
		$objClass = static::$className;

		if (! $hasKey( dataObject::$currentObjs[$objClass], $objName )) {

			$result = dataObject::getResult('select 1 from INFORMATION_SCHEMA.TABLES where TABLE_NAME = '."'".$objClass."'".';');
				
			#var_dump($sqlResult);
			if (! $hasExists($result)){
				dataObject::fetchResult('create table '.$objClass.' (dataObjectName_____ CHAR(255) PRIMARY KEY);');}
				
			$result = dataObject::getResult('select 1 from '.dataObjectsDatabase.'.'.$objClass.' where dataObjectName_____ = '."'".$objName."'".';');
				
			#var_dump($sqlResult);
			if (! $hasExists($result)){
				return null;}
				
			$newObj = new dataObject();
			$newObj->name=$objName;
				
			dataObject::$currentObjs[$objClass][$objName] = $newObj;}

		return dataObject::$currentObjs[$objClass][$objName];}
	public static final function getObject($objName){

		#static $callCount = 0;$callCount++;

		$hasKey= function(&$array, $key){
			if (!is_array($array)){
				$array=array();}
			return array_key_exists($key, $array);};
		$hasExists= function($rows){
			if (!isset($rows->num_rows)) return false;
			return $rows->num_rows;};
			
		$objClass = static::$className;

		if (! $hasKey( dataObject::$currentObjs[$objClass], $objName )) {

			#echo '<br><br>making new memory object'.$callCount;			

			$result = dataObject::getResult('select 1 from INFORMATION_SCHEMA.TABLES where TABLE_NAME = '."'".$objClass."'".';');
			
			#echo '<br><br>class info:';
			var_dump($result);
			if (! $hasExists($result)){
				#echo '<br>making new sql class'.$callCount;			
				dataObject::fetchResult('create table '.$objClass.' (dataObjectName_____ CHAR(255) PRIMARY KEY);');}
			
			$result = dataObject::getResult('select 1 from '.dataObjectsDatabase.'.'.$objClass.' where dataObjectName_____ = '."'".$objName."'".';');
			
			#echo '<br><br>object info:';
			var_dump($result);
			if (! $hasExists($result)){
				#echo '<br>making new sql object'.$callCount;			
				dataObject::fetchResult('insert into '.$objClass.' (dataObjectName_____) VALUES ('."'".$objName."'".');');}
			
			$newObj = new dataObject();
			$newObj->name=$objName;
			
			dataObject::$currentObjs[$objClass][$objName] = $newObj;}
		
		return dataObject::$currentObjs[$objClass][$objName];}

	public final function getField($fieldName){
		if (!array_key_exists($fieldName, static::$classFields)) throw new Exception('Field does not exist');
		return dataObject::fetchResult(
				'select 1 from '.dataObjectsDatabase.'.'.$objClass
					.' where dataObjectName_____ = '."'".$objName."'".';')
			->fetch_array()[$fieldName];}}