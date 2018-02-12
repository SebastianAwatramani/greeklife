<?php
//THIS CLASS IS INCOMPLETE UNTIL A FULL SERVER IS AVAILABLE.  CURRENTLY, MOST VALUES ARE HARDCODED
//GENERALLY SPEAKING, THIS CLASS PROVIDES A SET OF METHODS TO ADD AND REVOKE GRADE RELEASES, AS WELL AS RETREIVE USER INFORMATION
//THREE ARE THREE SUBCLASSES: Admin(), Member(), Recruit(), and CustomUser()
// Admin() for admin info; Member() and Recruit() are split due to differences in database structure, and CustomUser() to perform revokations from an admin account (e.g. user being removed is not logged in)
Class User {

	protected $refNum;
	protected $sid;
	protected $displayName;
	protected $firstName;
	protected $lastName;
	protected $fullName;
	protected $fullNameSisFormat;
	protected $society;
	protected $date;
	protected $sisFlag;
	protected $status;
	protected $db;


	function __construct($db) {
		$this->db = $db;

		$studentInfo = $this->getServerInfo();
		$this->setProps($studentInfo);		
	}
	public function getFullName() {
		return $this->fullName;
	}
	public function getFirstName() {
		return $this->firstName;
	}
	public function getLastName() {
		return $this->lastName;
	}
	public function getFullNameSisFormat() {
		return $this->fullNameSisFormat;
	}
	public function getRefNum() {
		return $this->refNum;
	}
	public function getDisplayName() {
		return $this->displayName;
	}

	private function getServerInfo() {
		//This class will get the relevant info from $_SERVER to construct the user
		//For now it just returns fake info
		// $_FAKESESSION = array();
		// $_FAKESESSION[''] = ;
		// $_FAKESESSION[''] = ;
		// $_FAKESESSION[''] = ;
		// $_FAKESESSION[''] = ;
		// $_FAKESESSION[''] = ;
		// $_FAKESESSION[''] = ;
		// $_FAKESESSION[''] = ;		


		$studentInfo = array();
		//$reflectionClass = new ReflectionClass('User');

		return true;

		// $props = $reflectionClass->getProperties();
		// foreach($props as $prop) {
		// 	if($prop->isPrivate()) {
		// 		$name = $prop->getName();
		// 		$studentInfo[$name] = null;
		// 	}
		// }
	}

	public function getSisFlag() {
		return $this->sisFlag;
	}
		
	public function getSid() {
		return $this->sid;
	}
	private function setProps($studentInfo) {
		$this->sid = 10151330 ;
		$this->displayName = "Sebastian Awatramani";
		$this->firstName = "Sebastian";
		$this->lastName = "Awatramani";
		$this->fullName = "Sebastian Awatramani";
		$this->fullNameSisFormat = "AWATRAMANI, SEBASTIAN";
		$this->society = SOCIETY;

	}	
	
	public function revokeGradeRelease($removedBy) {
		$removalReason = "Student revoked permission to release grades";
		$sql = "update " . MAINTABLE . " set removed='yes', removed_by=:removed_by, removed_comments=:removalReason, status='removed', removed_date=NOW() where sid=:sid";
		$values = array(
			"removed_by" => $removedBy,
			"removalReason" => $removalReason,
			"sid" => $this->getSid()
			);

		$results = $this->db->update($sql, $values);

		if($results) {
			return true;
		} else {
			return false;
		}
	}
	public function resubmitGradeRelease($society) {
			
		$this->setSisFlag($society);
		$sisFlag = $this->getSisFlag();

		$sql = "update " . MAINTABLE . " SET removed ='no', removed_by=null, status='web receipt', removed_date = null, removed_by = null, removed_comments = null, sis_flag=:sisFlag, " . SOCIETY . "=:" . SOCIETY . " where sid = :sid";

		$values = array(
			"sid" => $this->getSid(),
			SOCIETY => strtoupper($society),
			"sisFlag" => $sisFlag
			);

		$results = $this->db->update($sql, $values);

		if($results) {
			return true;
		} else {
			return false;
		}
	}
}

Class Member extends User {
	private $term;
	public function __construct($db){
		parent::__construct($db);
	}

	public function setRefNum() {
		//Get highest reference number from ref_num table

		$table = "ref_numbers_" . SOCIETY;
		$rows = "ref_num";
		$where = "status IS NOT NULL";
		$order = "ref_num DESC limit 1";

		$this->db->select($table, $rows, $where, $order, null, PDO::FETCH_ASSOC);
		$results = $this->db->getResults();
		$refNum = $results[0]['ref_num'];
		$refNum++;
		$this->refNum = $refNum;


		if(!empty($this->refNum)) {
			return true;
		} else {
			return false;
		}
	}
	protected function setTerm() {
		$this->term = "All";
	}
	protected function getTerm() {
		return $this->term;
	}
	public function getRefNum($raw = false) {
		if($raw) {
			return $this->refNum;
		} else {
			$refNumPrefix = "SGR_";
			$refNum = $this->refNum;
			$length = strlen($refNum);
			$totalLength = 9;

			if($totalLength - $length > 0) {
				$lengthDiff = $totalLength - $length;
				$count = 0;
				while($count < $lengthDiff) {
					$refNum = "0" . $refNum;
					$count++;
				}
			} else {
				//At this point we've run out of allocated ref numbers and need to devise a new system.
			}

			return $refNumPrefix . $refNum;			
			}

	}
	public function setSisFlag($society) {
		switch ($society) {
			case 'Alpha Chi Omega':
				$this->sisFlag = 'SORA';
				break;
			case 'Alpha Delta Chi':
				$this->sisFlag = 'SORB';
				break;
			case 'Alpha Omicron Pi':
				$this->sisFlag = 'SORC';
				break;
			case 'Alpha Phi':
				$this->sisFlag = 'SORD';
				break;
			case 'Chi Omega':
				$this->sisFlag = 'SORE';
				break;
			case 'Delta Delta Delta':
				$this->sisFlag = 'SORF';
				break;
			case 'Delta Gamma' :
				$this->sisFlag = 'SORG';
				break;
			case 'Gamma Phi Beta':
				$this->sisFlag = 'SORH';
				break;
			case 'Kappa Alpha Theta':
				$this->sisFlag = 'SORI';
				break;
			case 'Kappa Kappa Gamma':
				$this->sisFlag = 'SORJ';
				break;
			case 'Pi Beta Phi':
				$this->sisFlag = 'SORK';
				break;
			case 'Sigma Rho Lambda':
				$this->sisFlag = 'SORL';
				break;				
			default:
				# code...
				break;
		};
	}

	public function addUserToDb($society) {
		$sql = "insert into " . MAINTABLE . "(ref_num, sid, display_name, first_name, last_name, full_name, full_name_sis_format," . SOCIETY . ", term, date, sis_flag, status) values(:ref_num, :sid, :display_name, :first_name, :last_name, :full_name, :full_name_sis_format, :" . SOCIETY . ", :term, :date, :sis_flag, :status)";
		
		$date = date('m/d/y');

		if(is_null($this->getSisFlag())) {
			$this->setSisFlag($society);
		}
		if(is_null($this->getTerm())) {
			$this->setTerm();
		}

		//MUST FIGURE OUT FROM WHERE TO GET TERM
		$values = array(
			"ref_num" => $this->getRefNum(),
			"date" => date('m/d/Y'),
			"sid" => $this->getSid(),
			"display_name" => $this->getDisplayName(),
			"first_name" => $this->getFirstName(),
			"last_name" => $this->getLastName(),
			"full_name" => $this->getFullName(),
			"full_name_sis_format" => $this->getFullNameSisFormat(),
			SOCIETY => strtoupper($society),
			"sis_flag" => $this->getSisFlag(),
			"status" => "web receipt",
			"term" => $this->getTerm()
			);

		$results = $this->db->insert($sql, $values);
		
		if($results) {
			$this->updateRefNum();
			return true;
		} else {

			return false;
		}
	}
	public function updateRefNum() {
		$table = "ref_numbers_" . SOCIETY;
		$sql = "UPDATE $table SET status = 'used' WHERE ref_num = :refNum";
		$values = array("refNum" => $this->getRefNum(true));
		$this->db->update($sql, $values);
	}

}

Class Recruit extends User {

	public function __construct($db){
		parent::__construct($db);
	}

	public function setRefNum() {
		$this->refNum = "Registrar Web";
	}
	public function setSisFlag($society) {
		$this->sisFlag = "SORM";
	}

	public function addUserToDb($society) {
		$sql = "insert into " . MAINTABLE . "(ref_num, sid, display_name, first_name, last_name, full_name, full_name_sis_format," . SOCIETY . ", date, sis_flag, status) values(:ref_num, :sid, :display_name, :first_name, :last_name, :full_name, :full_name_sis_format, :" . SOCIETY . ", :date, :sis_flag, :status)";
		
		$date = date('m/d/y');
		if(is_null($this->getSisFlag())) {
			$this->setSisFlag($society);
		}
		if(is_null($this->getRefNum())) {
			$this->setRefNum();
		}

		//MUST FIGURE OUT FROM WHERE TO GET TERM
		$values = array(
			"ref_num" => $this->getRefNum(),
			"date" => date('m/d/Y'),
			"sid" => $this->getSid(),
			"display_name" => $this->getDisplayName(),
			"first_name" => $this->getFirstName(),
			"last_name" => $this->getLastName(),
			"full_name" => $this->getFullName(),
			"full_name_sis_format" => $this->getFullNameSisFormat(),
			SOCIETY => strtoupper($society),
			"sis_flag" => $this->getSisFlag(),
			"status" => "web receipt",
			);

		$results = $this->db->insert($sql, $values);

		if($results) {
			return true;
		} else {
			return false;
		}
	}
}

Class Admin extends User {
	private $employeeId = 23987324;
	protected $employeeFirstName = "Sebastian";
	protected $employeeLastName = "Awatramani";
	
	function __construct() {

		$isAdmin = TRUE;

		if($isAdmin === TRUE) {
			return true;
		} else {
			return false;
		}

	}
	public function getEmployeeId() {
		return $this->employeeId;
	}
	public function getEmployeeName() {
		return $this->employeeFirstName . " " . $this->employeeLastName;
	}
	public function isAdmin() {
		//When I have a chance to understand the server variables, this will verify that the user is an admin
		return true;
	}
}

Class CustomUser extends User {
	private $requestId;

	public function __construct(array $userInfo, $db) {
		//UserInfo should contain a first name, last name and requestId

		$this->firstName = $userInfo['firstName'];
		$this->lastName = $userInfo['lastName'];
		$this->requestId = $userInfo['requestId'];
		$this->db = $db;

	}
	public function getFirstName() {
		return $this->firstName;
	}
	public function getLastName() {
		return $this->lastName;
	}
	public function getRequestId() {
		return $this->requestId;
	}

	public function checkForUser() {
		$rows = "sid";
		$where = "first_name = :firstName AND last_name = :lastName AND request_id = :requestId";
		$values = array(
			"firstName" => $this->getFirstName(),
			"lastName" => $this->getLastName(),
			"requestId" =>$this->getRequestId()
			);

		$results = $this->db->select(MAINTABLE, $rows, $where, null, $values);
		if($results) {return true;} else {return false;}
	}
	public function revokeGradeRelease($reason) {

		$admin = new Admin();

		if($admin) {
			$employeeInfo  = "EmployeeID: " . $admin->getEmployeeId() . " " . $admin->getEmployeeName();
			$sql = "UPDATE " . MAINTABLE . " SET removed='yes', removed_by =:employeeInfo, removed_comments = :reason, status = 'removed', removed_date = NOW() WHERE request_id  = :requestId";
			
			$values = array(
				"reason" => $reason,
				"requestId" => $this->requestId,
				"employeeInfo" => $employeeInfo,
				);
			$results = $this->db->update($sql, $values);
			if($results) {
				return true;
			} else {
				return false;
			}

		}
	}
}

?>