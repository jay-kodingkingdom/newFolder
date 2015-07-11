<?php

final class timeGrid{

	const repeatInterval = 'P7D';
	const dateTimeFormat = DateTime::ISO8601;
	const dateFormat = 'Y-m-d';	
	const timeFormat = 'D H:i';
	const epochDate = '2015-01-05';

	//const intervalFormat = 'P%yY%mM%dD';
	
	private static $timeIntervals = array();
	
	public static final function getRepeatInterval(){
		return new DateInterval(
				timeGrid::repeatInterval);}
	
	public static final function getWeekday($date){
		return date('D', strtotime($date));}
	
	public static final function getRepeatOffset($date){
    	return floor(
    				(new DateTime(timeGrid::epochDate))
    					->diff(
    							new DateTime($date))
    					->days
    				/7);}
    	
    public static final function simplifyIntervals($intervals){
    	$weekdays = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
    	$hours = array('00', '01', '02', '03', '04', '05', '06', '07', '08'
    					, '09', '10', '11', '12', '13', '14', '15', '16', '17'
    					, '18', '19', '21', '22', '23');
    	$minutes = array('00', '10', '20', '30', '40', '50');
    	
    	$getNextTimeslot = function($intervalName) use ($weekdays, $hours, $minutes){
    		$weekday = substr($intervalName, 0, 3);
    		$hour = substr($intervalName, 3, 2);
    		$minute = substr($intervalName, 5, 2);
    		$weekdayIndex = array_search($weekday, $weekdays);
    		$hourIndex = array_search($hour, $hours);
    		$minuteIndex = array_search($minute, $minutes);
    		$minuteIndex = ($minuteIndex + 1) % count($minutes);
    		if ($minuteIndex == 0) $hourIndex = ($hourIndex + 1) % count($hours);
    		if ($minuteIndex == 0 && $hourIndex == 0) $weekdayIndex = ($weekdayIndexIndex + 1) % count($weekdays);
    		$nextWeekday = $weekdays[$weekdayIndex];
    		$nextHour = $hours[$hourIndex];
    		$nextMinute = $minutes[$minuteIndex];
    		return $nextWeekday.$nextHour.$nextMinute;};
    	$getPrevTimeslot = function($intervalName) use ($weekdays, $hours, $minutes){
    		$weekday = substr($intervalName, 0, 3);
    		$hour = substr($intervalName, 3, 2);
    		$minute = substr($intervalName, 5, 2);
    		$weekdayIndex = array_search($weekday, $weekdays);
    		$hourIndex = array_search($hour, $hours);
    		$minuteIndex = array_search($minute, $minutes);
    		$minuteIndex = ($minuteIndex + count($minutes) - 1) % count($minutes);
    		if ($minuteIndex == count($minutes) - 1) $hourIndex = ($hourIndex + count($hours) - 1) % count($hours);
    		if ($minuteIndex == count($minutes) - 1 && $hourIndex == count($hours) - 1) $weekdayIndex = ($weekdayIndexIndex + count($weekdays) - 1) % count($weekdays);
    		$nextWeekday = $weekdays[$weekdayIndex];
    		$nextHour = $hours[$hourIndex];
    		$nextMinute = $minutes[$minuteIndex];
    		return $nextWeekday.$nextHour.$nextMinute;};
    		
    	$intervalsStack = array_merge(array(), $intervals); 
    	
    	$simpleIntervals = array();
    	
    	while (! empty($intervalsStack)){
    		end($intervalsStack);
    		$simpleInterval = key($intervalsStack);
    		array_pop($intervalsStack);
    		$simpleIntervalStart = $simpleInterval;
    		$simpleIntervalPre = $getPrevTimeslot($simpleIntervalStart); 
    		$simpleIntervalEnd = $getNextTimeslot($simpleIntervalStart);
    		//echo "processing $simpleInterval \n";
    		while (true){
	    		//echo "start is $simpleIntervalStart \n";
	    		//echo "pre is $simpleIntervalPre \n";
	    		//echo "end is $simpleIntervalEnd \n";
    			foreach ($intervalsStack as $interval => $dummy){
    				if ($interval == $simpleIntervalPre){
    					unset($intervalsStack[$interval]);
    					$simpleIntervalStart = $simpleIntervalPre;
    					$simpleIntervalPre = $getPrevTimeslot($simpleIntervalStart);
	    				//echo "found pre $interval \n";
    					continue 2;}
    				if ($interval == $simpleIntervalEnd){
    					unset($intervalsStack[$interval]);
    					$simpleIntervalEnd = $getNextTimeslot($simpleIntervalEnd);
    					//echo "found end $interval \n";
    					continue 2;}}
    			break;}
    		//echo "done $simpleIntervalStart to $simpleIntervalEnd \n";
    		$simpleIntervals[$simpleIntervalStart] = $simpleIntervalEnd;}
    	
    	return $simpleIntervals;}
	
		
	public static final function fetchGridInterval($weekday, $gridTime){
		if (! timeGrid::hasKey(timeGrid::$timeIntervals[$weekday], sprintf('%04d', $gridTime)))
			timeGrid::$timeIntervals[$weekday][sprintf('%04d', $gridTime)] =
				timeGrid::getGridInterval($weekday, $gridTime);		
		return timeGrid::$timeIntervals[$weekday]
											[sprintf('%04d', $gridTime)];}
		
	public static final function fetchGridIntervals($weekday, $startGridTime, $endGridTime){
		$timeIntervals = array();
		$hour = (int)($startGridTime/100);
		$minute = $startGridTime - $hour * 100;
		for ($gridTime = $startGridTime; $gridTime < $endGridTime; $gridTime += ($minute < 50? 10: 50)){
			$hour = (int)($gridTime/100);
			$minute = $gridTime - $hour * 100;
			$timeIntervals[$weekday.$gridTime]=timeGrid::fetchGridInterval($weekday, $gridTime);}
		return $timeIntervals;}		
	
	private static function hasKey(&$array, $key){
		if (!is_array($array)){
			$array=array();}
		return array_key_exists($key, $array);}
		
	private static final function getGridInterval($weekday, $gridTime){
		$hour = (int)($gridTime/100);
		$minute = $gridTime - $hour * 100;
		$nextMinute = ($minute + 10 >= 60? $minute - 50 : $minute + 10);
		$nextHour = ($nextMinute < 10 ? $hour+1 : $hour);
		$startTime = new DateTime (date(timeGrid::dateTimeFormat, strtotime('this '.$weekday.' '.$hour.':'.$minute, strtotime(timeGrid::epochDate))));
		$endTime = new DateTime (date(timeGrid::dateTimeFormat, strtotime('this '.$weekday.' '.$nextHour.':'.$nextMinute, strtotime(timeGrid::epochDate))));
		$timeInterval = timeInterval::getTimeInterval($startTime, $endTime);
		$timeInterval->name = $weekday . sprintf('%04d', $gridTime);
		return $timeInterval;}}		

		
		
final class timeInterval{

	public $startTime;
	public $endTime;
	public $name;

	private $dateIntervals = array();
	
	public final function fetchDateInterval($repeatNumber){
		if ( ! isset($this->dateIntervals[$repeatNumber]))
			$this->dateIntervals[$repeatNumber] = $this->getDateInterval($repeatNumber);
	
		return $this->dateIntervals[$repeatNumber];}
	
	public static final function getTimeInterval(DateTime $startTime, DateTime $endTime){
		$timeSlot = new timeInterval();
		$timeSlot->startTime = $startTime;
		$timeSlot->endTime = $endTime;
		return $timeSlot;}

	private final function getDateInterval($repeatNumber){
		$dateInterval = _dateInterval::getDateInterval($this, $repeatNumber);
		return $dateInterval;}}

		
		
final class _dateInterval{

	public $startTime;
	public $endTime;

	private $timeInterval;
	private $repeatNumber;
	
	private final function getStartTime(){
		$startTime = $this->timeInterval->startTime;
		for ($repeatNumber=1;
			$repeatNumber<$this->repeatNumber;
			$repeatNumber++){
				$startTime->add(timeGrid::getRepeatInterval());}
		return $startTime;}
	private final function getEndTime(){
		$endTime = $this->timeInterval->endTime;
		for ($repeatNumber=1;
			$repeatNumber<$this->repeatNumber;
			$repeatNumber++){
				$endTime->add(timeGrid::getRepeatInterval());}
		return $endTime;}

	public static final function getDateInterval(timeInterval $timeInterval, $repeatNumber){
		$dateInterval = new _dateInterval();
		$dateInterval->timeInterval = $timeInterval;
		$dateInterval->repeatNumber = $repeatNumber;
		$dateInterval->startTime = $dateInterval->getStartTime();
		$dateInterval->endTime = $dateInterval->getEndTime();
		return $dateInterval;}}