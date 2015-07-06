<?php

require_once('dataObject.php');
require_once('misc.php');
require_once('allRoundConfig.php');


foreach(array_merge(array()
		, location::getInstances()) as $location){
	if (! startsWith($location->getName(), "Custom"))
		$location->destroy();}

foreach(json_decode(locations)
			as $locationName => $locationData){
	$slots=array();
	foreach ($locationData as
		$dataName => $data){
		if ($dataName=='address') $locationAddress = $data;
		else $slots[$data]=$data;}
	location::getLocation($locationName, $slots, $locationAddress);}

final class location extends dataObject{
	
	const className='location';
	const classFields=array('locationSlots','address');
	
	private $slots=null;
	
	public final function getLocationName(){
		preg_replace('/^' . preg_quote("Custom"
				, '/') . '/', '', $this->getName());}
	
	public static final function fetchLocation($name){
		return location::fetchInstance($name);}
	public static final function getLocation($name, $locationSlots, $address){
		if (!location::fetchLocation($name)){
			$location = location::getInstance($name);
			$location->setField('locationSlots',$locationSlots);
			$location->setField('address',$address);}
		return location::fetchLocation($name);}
	
	public final function getAddress(){
		return $this->getField('address');}
	public final function getSlots(){
		if ($this->slots===null){
			$this->slots = array();
			foreach ($this->getField('locationSlots')
						as $slotName){
				$this->slots[$slotName] = new locationSlot();
				$this->slots[$slotName]->slotName=$slotName;
				$this->slots[$slotName]->location=$this;}}
		return array_merge(array()
		, $this->slots);}}

final class locationSlot{
	public $slotName;
	public $location;
	
	public final function getName(){
		return $this->slotName;}
	public final function getLocation(){
		return $this->location;}}