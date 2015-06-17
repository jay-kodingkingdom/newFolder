<?php

require_once('dataObjectConfig.php');

class dataObject{

	protected static $className='dataObject';
	protected static $classFields=array('love','peace');

	public static function getFields(){
		return static::$classFields;}
		public static function getClassName(){
			return static::$className;}
	
	
	
	
	private $name;

	public function getName(){
		return $this->name;}

		
		
		
		
	private static $memoryObjs=array();	
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
		
		
		
		
	private static function hasKey(&$array, $key){
			if (!is_array($array)){
				$array=array();}
			return array_key_exists($key, $array);}
	private static function hasExists($rows){
			if (!isset($rows->num_rows)) return false;
			return $rows->num_rows;}		
		

			
			
			
			
	private static function classExists($className){
		$objClass = static::$className;
		return dataObject::
			hasExists(
					dataObject::getResult(
							'select 1 from INFORMATION_SCHEMA.TABLES
							 where TABLE_NAME = '.
							"'".$objClass."'"
							.';'));}
		
	private static function makeClass($className){
		$objClass = static::$className;
		$dataFieldsFormat = "";
		foreach (static::$classFields as $field){
			$dataFieldsFormat .= ", ".
				$field." varchar(255)";}
		#echo '<br>making new sql class'.$callCount;
		dataObject::fetchResult(
			'create table '.$objClass.
				' (dataObjectName char(255) PRIMARY KEY'
				.$dataFieldsFormat.
				');');}
				
	private static function instanceExists($instanceName){
		$objClass = static::$className;
		return dataObject::
			hasExists(
				dataObject::getResult(
						'select 1 from '.dataObjectsDatabase.'.'.$objClass.
						' where dataObjectName = '.
						"'".$instanceName."'".
						';'));}
		
	private static function makeInstance($instanceName){		
		$objClass = static::$className;
		dataObject::fetchResult(
			'insert into '.dataObjectsDatabase.'.'.$objClass.
				' (dataObjectName)
				 VALUES ('.
				"'".$instanceName."'".
				');');}
		
		
	private static function rememberInstance($instanceName){		
		$objClass = static::$className;
		return
			dataObject::hasKey(
				dataObject::$memoryObjs[$objClass]
					, $instanceName);}
		
	private static function fieldExists($fieldName){
		return 
			in_array($fieldName
					, static::$classFields);}
		
	private function fetchField($fieldName){
		$objClass = static::$className;
		$objName = $this->name;
		
		return dataObject::fetchResult(
				'select '.$fieldName.
				' from '.dataObjectsDatabase.'.'.$objClass
				.' where dataObjectName = '."'".$objName."'".';')
				->fetch_assoc()[$fieldName];}
		
	private function putField($fieldName, $fieldValue){
		$objClass = static::$className;
		$objName = $this->name;
		
		dataObject::fetchResult(
				'update '.dataObjectsDatabase.'.'.$objClass.
					' set '.$fieldName.'='.
					"'".$fieldValue."'".
					' where dataObjectName='.
					"'".$objName."'".';');}	
		
		
		
		
		
		
		
	public static final function fetchInstance($objName){
		#static $callCount=0; $callCount++;		
		$objClass = static::$className;

		if (! dataObject::rememberInstance($objName)) {

			#echo '<br><br>making new memory object'.$callCount;			

			if (! dataObject::classExists($objClass)){
				#echo '<br>making new sql class'.$callCount;			
				dataObject ::makeClass($objClass);}
			
			if (!dataObject::instanceExists($objName)){
				#echo '<br>cannot fetch object'.$callCount;			
				return null;}
			
			$newObj = new dataObject();
			$newObj->name=$objName;
			
			dataObject::$memoryObjs[$objClass][$objName] = $newObj;}
		
		return dataObject::$memoryObjs[$objClass][$objName];}
	public static final function getInstance($objName){
		#static $callCount=0; $callCount++;		
		$objClass = static::$className;

		if (! dataObject::rememberInstance($objName)) {

			#echo '<br><br>making new memory object'.$callCount;			

			if (! dataObject::classExists($objClass)){
				#echo '<br>making new sql class'.$callCount;			
				dataObject ::makeClass($objClass);}
			
			if (!dataObject::instanceExists($objName)){
				#echo '<br>making new sql object'.$callCount;			
				dataObject::makeInstance($objName);}
			
			$newObj = new dataObject();
			$newObj->name=$objName;
			
			dataObject::$memoryObjs[$objClass][$objName] = $newObj;}
		
		return dataObject::$memoryObjs[$objClass][$objName];}

	public final function getField($fieldName){		
		if (! static::fieldExists($fieldName))
			throw new Exception('Field does not exist');
	
		return $this->fetchField($fieldName);}

	public final function setField($fieldName, $fieldValue){
		if (! static::fieldExists($fieldName))
			throw new Exception('Field does not exist');
		
		$this->putField($fieldName, $fieldValue);}}