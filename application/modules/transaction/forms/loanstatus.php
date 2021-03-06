<?php
/**Loan Disbursement Module
This Class will
-Create an disbursement form having 3 fields namely date description n disbursed amount
*/
class Transaction_Form_loanstatus extends Zend_Form
    {
		public function __construct($options = null)
		{
			parent::__construct($options);

			$accountCode= new Zend_Form_Element_Hidden('accountcode');
			$memberId = new Zend_Form_Element_Hidden('membercode');
			$categoryId = new Zend_Form_Element_Hidden('categoryId');

			$newStatus = new Zend_Form_Element_Select('newStatus');
			$newStatus->setAttrib('class', 'NormalBtn');
            $newStatus->setRequired(true);
            $newStatus->addMultiOption('','Select...');

			$description = new Zend_Form_Element_Textarea('description');
			$description->setAttrib('rows', '2');
			$description->setAttrib('cols', '20');
			$description->setRequired(true);

			$newStatus1 = new Zend_Form_Element_Hidden('newStatus1');
			$description1 = new Zend_Form_Element_Hidden('description1');


            $submit = new Zend_Form_Element_Submit('Submit');
            $submit->setAttrib('class', 'officesubmit');

            $Confirm = new Zend_Form_Element_Submit('Confirm');
            $Confirm->setAttrib('class', 'officesubmit');

            $this->addElements( array ($submit,$newStatus,$description,$accountCode,$memberId,$categoryId,$Confirm,$newStatus1,$description1));
        }
    }
