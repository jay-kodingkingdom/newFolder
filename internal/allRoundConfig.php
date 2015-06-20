<?php

define('userClasses',json_encode(array('Student','Tutor','Admin')));
define('locations',json_encode(array(
		array('name'=>'Central','address'=>'blah')=>array('Slot 1','Slot 2'),
		array('name'=>'Tsim Sha Tsui','address'=>'blah')=>array('Slot 1','Slot 2','Slot 3','Slot 4','Slot 5')
	)));
define('subjects', json_encode(array(
		'English','Math','SAT','blahblahblah',
		'IB'=>array('IB Math HL','IB English A','blah'),
		'IGCSE'=>array('Physics','blabla')
	)));
