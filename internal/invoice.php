<?php
require_once('dataObject.php');
require_once('Student.php');
require_once('timeSlot.php');

final class invoice extends dataObject{

	const className='invoice';
	const classFields=array('studentPointer',
			'monthTime','invoice','invoiceAmount'
	);

	public final function getStudent(){
		return Student::fetchInstance($this->getField('studentPointer'));}
	public final function getMonthTime(){
		return DateTime::createFromFormat(
			timeSlot::timeFormat
			,$this->getField('monthTime'));}
	public final function getInvoice(){
		return $this->getField('invoice');}
	public final function getInvoiceAmount(){
		return $this->getField('invoiceAmount');}
	private final function setStudent(Student $student){
		$this->setField('studentPointer', $student->getName());}
	private final function setMonthTime(DateTime $time){
		$this->setField('monthTime',$time->format(timePeriod::timeFormat));}
	private final function setInvoice($invoice){
		$this->setField('invoice', $invoice);}
	private final function setInvoiceAmount($invoiceAmount){
		$this->setField('invoiceAmount', $invoiceAmount);}
	
	private final function generateInvoice(){}
		
	public static final function getProgressReport(Student $student, DateTime $time){
		if (invoice::fetchProgressReport($student, $time)) return null;
	
		while (static::fetchInstance($name=getRandomString())!==null){}
		$invoice = invoice::getInstance($name);
		$invoice->setStudent($student);
		$invoice->setMonthTime($time);
		$this->generateInvoice();
		
		return $invoice;}
	public static final function fetchProgressReport(Student $student, DateTime $time){
		foreach (invoice::getInstances() as $invoice){
			if ($invoice->getStudent() === $student &&
					$invoice->getMonthTime() === $time)
						return $invoice;}
		return null;}}