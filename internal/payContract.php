<?php

require_once('dataObject.php');
require_once('Tutor.php');
require_once('timeSlot.php');

final class payment extends dataObject{

	const className='payment';
	const classFields=array('payInfoPointer',
			'timePointer'
			,'payAmount','payDone'
	);

	public final function getTutor(){
		return Tutor::fetchInstance($this->getField('tutorPointer'));}
	public final function getMonthTime(){
		return DateTime::createFromFormat(
			timeSlot::timeFormat
			,$this->getField('monthTime'));}
	public final function getPayment(){
		return $this->getField('payment');}
	public final function getPaymentAmount(){
		return $this->getField('paymentAmount');}
	private final function setTutor(Tutor $tutor){
		$this->setField('tutorPointer', $tutor->getName());}
	private final function setMonthTime(DateTime $time){
		$this->setField('monthTime',$time->format(timeInterval::timeFormat));}
	private final function setPayment($payment){
		$this->setField('payment', $payment);}
	private final function setPaymentAmount($paymentAmount){
		$this->setField('paymentAmount', $paymentAmount);}
	
	private final function generatePayment(){}
		
	public static final function getPayment(Tutor $tutor, DateTime $time){
		if (payment::fetchTutor($tutor, $time)) return null;
	
		while (static::fetchInstance($name=getRandomString())!==null){}
		$payment = payment::getInstance($name);
		$payment->setTutor($tutor);
		$payment->setMonthTime($time);
		$this->generatePayment();
		
		return $payment;}
	public static final function fetchPayment(Tutor $tutor, DateTime $time){
		foreach (payment::getInstances() as $payment){
			if ($payment->getStudent() === $student &&
					$payment->getMonthTime() === $time)
						return $payment;}
		return null;}}
				
		
final class payInfo extends dataObject{

	const className='payInfo';
	const classFields=array('userPointer','paymentsPointers');

	public final function getUser(){
		$userPointer = $this->getField('userPointer');
		if (!( $user = Tutor::fetchInstance($userPointer)))
			if (!( $user = Student::fetchInstance($userPointer)))
				if (!( $user = Admin::fetchInstance($userPointer)));
		return $user;}

	public function getPayment(){
		return $this->getField('payment');}
			
	public final function getPayment(DateTime $time){}
	public final function getPayments(){
		$payments = array();
		$paymentIds = $this->getField('paymentsPointers');
		foreach ($paymentIds as $paymentId){
			$payments[$paymentId] = payment::fetchInstance($paymentId);}
		return $payments;}}