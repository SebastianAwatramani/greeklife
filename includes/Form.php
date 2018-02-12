<?php

//////////////////////////////////////////////////////////////////////////////////////////////////
// Form() and its associated classes provide a number of form construction methods 			  	//
//  																							//
// Arguments:																					//
// 		$formName: The name of the form you want to check.  E.g. "members"						//
// 		$db: A database object 																	//
//		$action: post/get 																		//
//		$attributes: an array of key value pairs where key is an attribute (e.g. id => elmnId)	//								//                  																			//
//																								//
//////////////////////////////////////////////////////////////////////////////////////////////////
class Form {
	protected $label;
	protected $table;
	function __construct($formName, $method, $action, $attributes = null) {
		$formHeader = "<form name='";
		$formHeader .= "{$formName}' ";
		$formHeader .= "method='";
		$formHeader .= "{$method}' ";
		$formHeader .= "action='";
		$formHeader .= "$action' ";
		if(!empty($attributes)) {
			foreach ($attributes as $attribute => $value) {
				$formHeader .= "$attribute='$value' ";
			}
		}
		$formHeader .= "autocomplete='off'>";
		echo "$formHeader \n";
	}
	public function closeForm() {
		echo "</form>";
	}
	
}

//////////////////////////////////////////////////////////////////////////////////////////////
// name: Name attribute                                                                     //
// type: type attribute                                                                     //
// attributes: an array of key value pairs where key is an attribute (e.g. id => elmnId)    //
// printLabel: bool, set true to print the inputbox's assoicated lable                      //
//////////////////////////////////////////////////////////////////////////////////////////////

class InputBox {
	private $name;
	private $type;
	private $attributes;
	private $printLabel;
	public function __construct($name, $type, $attributes = null, $printLabel = false) {
		$this->name = $name;
		$this->type = $type;
		$this->attributes = $attributes;
		$this->printLabel = $printLabel;
		$this->writeInputBox();
	}
	private function writeInputBox() {
		if($this->type !== "hidden" && $this->printLabel === true) {
			$labelHTML = "<label>" . ucfirst($this->name) . "</label>\n";
			echo $labelHTML;
		}
		$this->name = str_replace(" ", "", $this->name);
		$inputHTML = "\t<input type='{$this->type}' name='{$this->name}'";
		if($this->attributes != null) {
			foreach ($this->attributes as $key => $value) {
				$inputHTML .= " {$key} = '{$value}'";
			}
		}
		$inputHTML .= " />\n";
		echo $inputHTML;
	}
}
class SubmitButton { 
	private $name;
	private $value;

	public function __construct($value = "Submit", $name = "Submit") {
		$this->name = $name;
		$this->value = $value;
		$this->writeSubmitBox();
	}
	private function writeSubmitBox() {
		echo "\t<input type='Submit' value='$this->value' name='{$this->name}'/>";
	}
}
//This class created a submit style cancel button used for cancelling form submission
class CancelButton {
	public function __construct() {
		echo "\t<input type='Submit' value='Cancel' name='Cancel' formnovalidate/>";
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////
// This class constructs a group of radio buttons											//
// name: Name attribute                                                                     //
// type: type attribute                                                                     //
// values: An array containing value attributes.  The number of values will determine the   //
// number of radio buttons printed    														//
// printRadioGroup::required: bool, set true to set the radio group as a required field     //
// NOTE: This class automatically prints, no need to call printRadioGroup()
//////////////////////////////////////////////////////////////////////////////////////////////

Class RadioGroup {
	private $legend;
	private $name;
	private $type = "radio";
	private $values;

	public function __construct($name, array $values, $legend, $required = false) {
		$this->name = $name;
		$this->values = $values;
		$this->legend = $legend;
		$this->printRadioGroup($required);
	}
	private function printRadioGroup($required) {
		echo "<fieldset id='$this->name'>\n";
		echo "<legend>$this->legend</legend>\n";

		for($i = 0; $i < count($this->values); $i++) {

			$radioHTML = "<input name='$this->name' type='$this->type' value='{$this->values[$i]['value']}'";
			($required === true) ? $radioHTML .= "required>\n" : $radioHTML .= ">\n";
			echo $radioHTML;

			echo "<label>{$this->values[$i]['label']}</label>";

		}
		echo "</fieldset>";

	}




}
?>