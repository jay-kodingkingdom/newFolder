<?php

define('userClasses',json_encode(array('Student','Tutor','Admin')));
define('locations',json_encode(array(
		'Central'=>array('Slot 1','Slot 2','address'=>'blah'),
		'Tsim Sha Tsui'=>array('Slot 1','Slot 2','Slot 3','Slot 4','Slot 5','address'=>'blah')
	)));
define('subjects', json_encode(array(
		'English','Math','SAT','blahblahblah',
		'IB'=>array('IB Math HL','IB English A','blah'),
		'IGCSE'=>array('Physics','blabla')
	)));
