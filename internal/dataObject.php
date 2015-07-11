<?php

require_once('dataObjectConfig.php');
require_once('logger.php');

class dataObject{

	const className='dataObject';
	const classFields=array();

	public static function getFields(){
		return static::classFields;}
	public static function getClassName(){
		return static::className;}
	
	
	
	
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
			throw new Exception('Database query error, query:'.$question);
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
		

			
			
			
			
	private static function classExists(){
		$objClass = static::className;
		return dataObject::
			hasExists(
					dataObject::getResult(
							'select 1 from INFORMATION_SCHEMA.TABLES
							 where TABLE_NAME = '.
							"'".$objClass."'"
							.';'));}
		
	private static function makeClass(){
		$objClass = static::className;
		$dataFieldsFormat = "";
		foreach (static::classFields as $field){
			$dataFieldsFormat .= ', '.
				$field.' mediumblob ';}
		#echo '<br>making new sql class'.$callCount;
		dataObject::fetchResult(
			'create table '.$objClass.
				' ('.dataObjectsName.' char(255) PRIMARY KEY'.
				', '.dataObjectsFreezed.' boolean default false'.
				$dataFieldsFormat.
				');');}
				
	private static function instanceExists($instanceName){
		$objClass = static::className;
		return dataObject::
			hasExists(
				dataObject::getResult(
						'select 1 from '.dataObjectsDatabase.'.'.$objClass.
						' where '.dataObjectsName.' = '.
						"'".$instanceName."'".
						';'));}
		
	private static function makeInstance($instanceName){		
		$objClass = static::className;
		dataObject::fetchResult(
			'insert into '.dataObjectsDatabase.'.'.$objClass.
				' ('.dataObjectsName.')
				 VALUES ('.
				"'".$instanceName."'".
				');');}

	private static function unmakeInstance($instanceName){
		$objClass = static::className;
		dataObject::fetchResult(
				'delete from '.dataObjectsDatabase.'.'.$objClass.
						' where '.dataObjectsName.' = '.
						"'".$instanceName."'".
						';');}
		
	private static function rememberInstance($instanceName){		
		$objClass = static::className;
		return
			(dataObject::hasKey(
				dataObject::$memoryObjs[$objClass]
					, $instanceName) ?
					dataObject::$memoryObjs[$objClass][$instanceName] :
					null);}

	private static function memorizeInstance($instanceName){
		$objClass = static::className;
			
		if (! dataObject::hasKey(
				dataObject::$memoryObjs[$objClass]
				, $instanceName)){
			$instance = new $objClass;
			$instance->name=$instanceName;
				
			dataObject::$memoryObjs[$objClass][$instanceName] = $instance;}}

	private static function forgetInstance($instanceName){
		$objClass = static::className;
					
		if (dataObject::hasKey(
				dataObject::$memoryObjs[$objClass]
					, $instanceName)){			
			unset(dataObject::$memoryObjs[$objClass][$instanceName]);}}
		
	private static function fieldExists($fieldName){
		return 
			in_array($fieldName
					, static::classFields);}
		
	private function fetchField($fieldName){
		$objClass = static::className;
		$objName = $this->name;
		
		return json_decode(
				dataObject::fetchResult(
						'select '.$fieldName.
						' from '.dataObjectsDatabase.'.'.$objClass
						.' where '.dataObjectsName.' = '."'".$objName."'".';')
				->fetch_assoc()[$fieldName]
				, true);}
		
	private function putField($fieldName, $fieldValue){
		$objClass = static::className;
		$objName = $this->name;
		
		dataObject::fetchResult(
				'update '.dataObjectsDatabase.'.'.$objClass.
					' set '.$fieldName.'='.
					"'".dataObject::getDatabase()->escape_string(
							json_encode($fieldValue))."'".
					' where '.dataObjectsName.'='.
					"'".$objName."'".';');}	
		
	private final function setFreezed($freezed){
		$objClass = static::className;
		$objName = $this->name;

		dataObject::fetchResult(
				'update '.dataObjectsDatabase.'.'.$objClass.
					' set '.dataObjectsFreezed.'='.
					($freezed?'true':'false').
					' where '.dataObjectsName.'='.
					"'".$objName."'".';');}	
		
	private final function setDestroy(){}
	private final function unsetDestroy(){}


	
		
		
		
		
	private static final function fetchInstances(){
		$objClass = static::className;			

		if (! static::classExists()){
			#echo '<br>making new sql class'.$callCount;			
			static::makeClass();}

		$instances = array();
		
		$result = dataObject::fetchResult(
						'select '.dataObjectsName.
						' from '.dataObjectsDatabase.'.'.$objClass.
						' where '.dataObjectsFreezed.'= false'
						.';');
		
		while ($row = $result->fetch_assoc()) {
			$objName = $row[dataObjectsName];
			
			if (! dataObject::rememberInstance($objName)){
				static::memorizeInstance($objName);} 

			$instances[$objName]=static::rememberInstance($objName);}
		
		return $instances;}	
		
		
		
		
		


	public static final function getInstances(){
		return static::fetchInstances();}
		
		
	public static final function fetchInstance($objName){
		#static $callCount=0; $callCount++;		
		$objClass = static::className;

		if (! static::rememberInstance($objName)) {

			#echo '<br><br>making new memory object'.$callCount;			

			if (! static::classExists()){
				#echo '<br>making new sql class'.$callCount;			
				return null;}
			
			if (!static::instanceExists($objName)){
				#echo '<br>cannot fetch object'.$callCount;			
				return null;}
			
			static::memorizeInstance($objName);}
		
		return static::rememberInstance($objName);}
	public static final function getInstance($objName){
		#static $callCount=0; $callCount++;		
		$objClass = static::className;

		if (! static::rememberInstance($objName)) {

			#echo '<br><br>making new memory object'.$callCount;			

			if (! static::classExists()){
				#echo '<br>making new sql class'.$callCount;			
				static::makeClass();}
			
			if (!static::instanceExists($objName)){
				#echo '<br>making new sql object'.$callCount;			
				static::makeInstance($objName);}
						
			static::memorizeInstance($objName);}
		
		return static::rememberInstance($objName);}
		
		
	
	public final function destroy(){
		$objName = $this->name;
		
		static::forgetInstance($objName);
		static::unmakeInstance($objName);}
		
	public final function isFreezed(){
		return $this->fetchField(dataObjectsFreezed);}

	public final function freeze(){
		$this->setFreezed(true);
		$this->setDestroy();}

	public final function unfreeze(){
		$this->setFreezed(false);
		$this->unsetDestroy();}

	public final function getField($fieldName){		
		if (! static::fieldExists($fieldName))
			throw new Exception('Field does not exist');
	
		return $this->fetchField($fieldName);}

	public final function setField($fieldName, $fieldValue){
		if (! static::fieldExists($fieldName))
			throw new Exception('Field does not exist');
		
		$this->putField($fieldName, $fieldValue);}}