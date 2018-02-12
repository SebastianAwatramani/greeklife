<?php

//////////////////////////////////////////////////////////////////////////////////
// FormStatus() Checks the availability of the grade release forms 				//
//  																			//
// Arguments:																	//
// 		$formName: The name of the form you want to check.  E.g. "members"		//
// 		$db: A database object 													//
//																				//
//////////////////////////////////////////////////////////////////////////////////

class FormStatus
{
	private $formStatus;
	private $displayText;
	private $formName;
	
	function __construct($formName, $db) {
		$this->formStatus = "offline";
		$this->displayText = "Currently not available";
		
		$table = "forms_status";
		$where = "form_name = :formName";
		$rows = "*";
		$values = array("formName" => $formName);

		$res = $db->select($table, $rows, $where, null, $values);
		if($res) {
			$results = $db->getResults();			
			if(!empty($results)) {
				$this->formStatus = $results[0]['status'];
				$this->displayText = $results[0]['display_text'];
			}
		}
	}
	function getFormStatus() {
		return $this->formStatus;
	}
	function getDisplayText() {
		return $this->displayText;
	}
}

