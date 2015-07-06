<?php
echo '<pre>???????';

require_once('../debug.php');
require_once('User.php');
require_once('login.php');
require_once('Subject.php');
require_once('timeSlot.php');
require_once('Tutor.php');
require_once('Student.php');
require_once('Admin.php');
require_once('lesson.php');
require_once('encryption.php');

Admin::getAdmin('nishman','totalnish','nish');

/*var_dump(timeGrid::getTimeGrid());

echo '<br>';
echo date(timePeriod::timeFormat, strtotime('this Monday 06:00', strtotime('05-01-2015')));
echo '<br>';
echo date(timePeriod::timeFormat, strtotime('this Tuesday 06:00', strtotime('05-01-2015')));
echo '<br>';
echo date(timePeriod::timeFormat, strtotime('this Wednesday 06:00', strtotime('05-01-2015')));
echo '<br>';
echo date(timePeriod::timeFormat, strtotime('this Thursday 06:00', strtotime('05-01-2015')));
echo '<br>';
echo date(timePeriod::timeFormat, strtotime('this Friday 06:00', strtotime('05-01-2015')));
echo '<br>';
echo date(timePeriod::timeFormat, strtotime('this Saturday 06:00', strtotime('05-01-2015')));
echo '<br>';
echo date(timePeriod::timeFormat, strtotime('this Sunday 06:00', strtotime('05-01-2015')));

dataObject::getInstance('objname');
dataObject::getInstance('objname2');
dataObject::getInstance('objname3');
dataObject::getInstance('objname');
dataObject::getInstance('objname');
dataObject::getInstance('objname');
dataObject::getInstance('objname');
dataObject::getInstance('objname');


User::getUser('jamesMan','jammypw');
User::getUser('john','kidscoding1113');
User::getUser('brainy','manybrains');

$john = User::fetchUser('john','kidscoding1113');

$obj3=dataObject::getInstance('objname3');

echo '<br><br><br><br><br>';
var_dump($obj3);

echo '<br><br><br><br><br>';
var_dump($obj3->getName());

echo '<br><br><br><br><br>';
var_dump(dataObject::getInstances());

echo '<br><br><br><br><br>';
var_dump(dataObject::getClassName());

echo '<br><br><br><br><br>';
var_dump(User::getClassName());

$john->unfreeze();

echo '<br><br><br><br><br>';
var_dump(User::getInstances());

echo '<br><br><br><br><br>';
var_dump($john->getUsername());

echo '<br><br><br><br><br>';
var_dump($john->getPassword());

$john->freeze();

echo '<br><br><br><br><br>';
var_dump(User::getInstances());

$john->unfreeze();

echo '<br><br><br><br><br>';
var_dump(User::getInstances());

$john->destroy();

echo '<br><br><br><br><br>';
var_dump(User::getInstances());

$john->setUsername('notjohn');
$john->setPassword('bidscoding1113');
$john=User::getUser('notjohn','bidscoding1113');
$realnotjohn=User::getUser('actuallyjohn','bidscoding1113');

echo '<br><br><br><br><br>';
var_dump($john);

echo '<br><br><br><br><br>';
var_dump($realnotjohn);


$private = '-----BEGIN PRIVATE KEY-----
MIIBUwIBADANBgkqhkiG9w0BAQEFAASCAT0wggE5AgEAAkEAroiq34bCOQzQ2bXk
+xhG9N7mDqkaokDDUtJnKO+9pKXaKGis0j4OxKiSq0YcF2UjtV8XvhwCX9RiHERf
9i2DJwIDAQABAkBnF7UO2XOp7ScEIgwCQUHQbEUpzbs8sdJt/ngO1yWGtbJOD3fZ
T79Z2V17OmCBBCW7vXEhbsX+lhqV1WGEUlaBAiEA4k8YhSFiBXIov2DhPOndk5cy
tRLKjJpKz2KZgvr+lkECIQDFbqHH/nk+1OKdyxKjuuMTqPERE9h6HuSfC7/iSyFP
ZwIgFHzOnnbINfAAylqN6YLOgWcFuyjJV3M8ZIvrk9T/KUECIBPQO3onpqFQmgF9
7LvzuHAzpyWwmSwAR69SbYpXQduHAiAA6jDsdwwu8unXuT4KGRgGERujeAqTiIb2
xHQ0S6TczQ==
-----END PRIVATE KEY-----'
;

$public = '-----BEGIN PUBLIC KEY-----'."\n".
'MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAK6Iqt+GwjkM0Nm15PsYRvTe5g6pGqJA'."\n".
'w1LSZyjvvaSl2ihorNI+DsSokqtGHBdlI7VfF74cAl/UYhxEX/YtgycCAwEAAQ=='."\n".
'-----END PUBLIC KEY-----';


$encryption = encryption::getEncryption();

$encrypted_text = $encryption->encrypt('love and peace');
$decrypted_text = $encryption->decrypt($encrypted_text);

echo '<br><br><br><br><br>';
var_dump($encrypted_text);

echo '<br><br><br><br><br>';
var_dump($decrypted_text);





if (isset($_POST['input'])) {

	//Load private key:
	$privateKey = openssl_pkey_get_private($private, "");

	echo '<br><br><br><br><br>';
	var_dump($_POST['input']);

	//Decrypt
	$decrypted_text = "";
	openssl_private_decrypt(hex2bin($_POST['input']), $decrypted_text, $privateKey);
	$decrypted_text=base64_decode($decrypted_text);

	echo '<br><br><br><br><br>';
	var_dump($decrypted_text);

	//Free key
	openssl_free_key($privateKey);}
*/

echo '<br><br><br><br>';
echo 'Tutors have Lessons, TimeSlots, Progress Reports, Payment, and Homework Help';

$week = new DateInterval('P7D');
//$twodays = new DateInterval('P2D');


$timeSlot = timeGrid::fetchGridSlots('Wednesday',1530,1730);
//$timeSlot1=$timeSlot ;

//$startTime = DateTime::createFromFormat(timeSlot::timeFormat,'2015-08-14T14:30:00+0800');
//$endTime = DateTime::createFromFormat(timeSlot::timeFormat,'2015-08-14T15:31:00+0800');
$timeSlot2 = timeGrid::fetchGridSlots('Tuesday',600,630);

//$startTime = DateTime::createFromFormat(timeSlot::timeFormat,'2015-08-14T16:00:00+0800');
//$endTime = DateTime::createFromFormat(timeSlot::timeFormat,'2015-08-14T18:00:00+0800');
$timeSlot3 = timeGrid::fetchGridSlots('Friday',1530,1730);

//$startTime = DateTime::createFromFormat(timeSlot::timeFormat,'2015-08-14T10:30:00+0800');
//$endTime = DateTime::createFromFormat(timeSlot::timeFormat,'2015-08-14T12:31:00+0800');
$timeSlot4 = timeGrid::fetchGridSlots('Sunday',1530,1730);

/*echo '<br><br><br><br>';
for($i=1;$i<5;$i++){
for($j=1;$j<5;$j++){
echo '<br>';
echo "$i,$j";$ivar='timeSlot'.$i;$jvar='timeSlot'.$j;
echo (timeSlot::overlaps($$ivar,$$jvar)? 'overlap' : 'nolap');}}*/

$physics = Subject::getSubject('Physics');
$literature = Subject::getSubject('lovelit');
$econ = Subject::getSubject('ecooon');

$hisslots = timeSlot::getTimeSlots();
$hisslots->addTimePeriods($timeSlot->getTimePeriods());
$hisslots->addTimePeriods($timeSlot2->getTimePeriods());
var_dump($hisslots->getTimePeriods());

$marktutor = (Tutor::fetchTutor('littleman') ? Tutor::fetchTutor('littleman') : 
		
		
		Tutor::getTutor(
				'bigmark'
				,'myname'
				,array($physics,$literature,$econ)
				,$hisslots
				,"Genius in everything. French. Speaks 24 languages fluently."
				, false
				, 2000
				, 'littleman'));

$matthewstu = (Student::fetchStudent('kiddo') ? Student::fetchStudent('kiddo') : 
		
		
		Student::getStudent(
				'micky'
				,'ricky'
				,'loves licky, needs lit help'
				, 'kiddo'));


/*
$litlesssch = lessonSchedule::getLessonSchedule(
array($marktutor)
, array($matthewstu)
, $timeSlotSchedule
, location::fetchLocation('Central')->getSlots()['Slot 1']
, $literature);

printdebug($litlesssch);

$litless2sch = lessonSchedule::getLessonSchedule(
array($marktutor)
, array($matthewstu)
, $timeSlotSchedule2
, location::fetchLocation('Central')->getSlots()['Slot 1']
, $literature);

printdebug($litless2sch);

$litless3sch = lessonSchedule::getLessonSchedule(
array($marktutor)
, array($matthewstu)
, $timeSlotSchedule3
, location::fetchLocation('Central')->getSlots()['Slot 1']
, $literature);

printdebug($litless3sch);

$litless4sch = lessonSchedule::getLessonSchedule(
array($marktutor)
, array($matthewstu)
, $timeSlotSchedule3
, location::fetchLocation('Central')->getSlots()['Slot 2']
, $literature);

printdebug($litless4sch);

$litless5sch = lessonSchedule::getLessonSchedule(
array($marktutor)
, array($matthewstu)
, $timeSlotSchedule4
, location::fetchLocation('Central')->getSlots()['Slot 1']
, $literature);

printdebug($litless5sch);

$litless6sch = lessonSchedule::getLessonSchedule(
array($marktutor)
, array($matthewstu)
, $timeSlotSchedule4
, location::fetchLocation('Central')->getSlots()['Slot 2']
, $literature);

printdebug($litless6sch);

if ($litlesssch !== null) $litlesssch->destroy();
if ($litless2sch !== null) $litless2sch->destroy();
if ($litless3sch !== null) $litless3sch->destroy();
if ($litless4sch !== null) $litless4sch->destroy();
if ($litless5sch !== null) $litless5sch->destroy();
if ($litless6sch !== null) $litless6sch->destroy();*/
echo '</pre>';