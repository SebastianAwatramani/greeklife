<?php
//////////////////////////////////////////////////////////////////////////////////////////////////
// SocietyList() gets a list of e.g. sororities or fraternities and provides a method to print 	//
//  																							//
// Arguments:																					//
// 		$db: A database object 																	//
//		$societySearch: Name of society, null for all 											//
//		$printAll: bool, set true to print "all" as the first society (usful for admin searches //
//		$autoSubmit: bool, set true to filter society when admin select radio button 			//
//		$required: bool, set true to made selection required									//
//////////////////////////////////////////////////////////////////////////////////////////////////


class SocietyList {
	private $results;
	private $societySearch;
	private $printAll;
	function __construct($db, $societySearch = null, $printAll = true) {
		$rows = "distinct " . SOCIETY;
		$where = SOCIETY . " != ''";
		$order = SOCIETY;
		$results = $db->select(MAINTABLE, $rows, $where, $order);
		if($results) {
			$results = $db->getResults();
			if($printAll === true) {
				$all = array("sorority" => "All");	
				array_unshift($results, $all);
			}
			$this->results = $results;
			$this->societySearch = $societySearch;
		}
	}
	function printSocietyRadioButtons($autoSubmit, $required = false) {
		echo "<fieldset id='societyList' required>\n";
		echo "<legend>Select your " . ucfirst(SOCIETY) . ":</legend>";
		for($i = 0; $i < count($this->results); $i++) {
			$societyName = strtolower($this->results[$i][SOCIETY]);
			$societyName = ucwords($societyName);

			$html = "\t\t<input name='societySearch' type='radio' value='{$societyName}'";
			if($autoSubmit === true) {
				$html .= " onclick='document.search.submit()'";
			}
			if($required === true) {
				$html .= " required ";
			}
			if(isset($this->societySearch) && $this->societySearch == $societyName) {
          		$html .= " checked>"; 
          	} else {
          		$html .= ">";
          	}
          	$html .= "<label>{$societyName}</label>";
          	echo $html . PHP_EOL;
		}
		echo "\t</fieldset>";
	}
}


?>