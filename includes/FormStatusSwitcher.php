<?php
//////////////////////////////////////////////////////////////////
// This class switches form status between online and offline.  //
// $db: A database obj 					                       //
////////////////////////////////////////////////////////////////
Class FormStatusSwitcher {
	private $db;

	public function __construct($db) {
		$this->db = $db;
	}
	public function switchFormStatus() {

		if(isset($_GET['formStatus']) && (($_GET['formStatus'] === "online") || ($_GET['formStatus'] === "offline"))) {
			$tableName = "forms_status";
			$formName = htmlspecialchars_decode($_GET['formName']);

			//Since this class is used with $_GET inputs, we make sure that only the forms specific to the instantiated site will actually work.  E.g. if site = greeklife then the only valid forms are sorority_members and sorority_recruits
			$validFormNames = array(SOCIETY . "_" . "members", SOCIETY . "_" . "recruits"); 
			
			if(array_search($formName, $validFormNames) !== false) {
				$formStatus = $_GET['formStatus'];
				$sql = "UPDATE $tableName SET status = :formStatus WHERE form_name = :formName";
				$values = array(
					"formStatus" => $formStatus, 
					"formName" => $formName
					);
				if($results = $this->db->update($sql, $values)) {
					return true;
				} else {
					return false;
				}
			}
		} else  {
			return false;
		}
	}
}


?>