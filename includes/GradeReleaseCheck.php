<?php
//////////////////////////////////////////////////////////////////////////////////////////////
// This class checks to see if (a) the user aleady has a grade release submitted, and (b)   //
// if they are in the DB but have revoked their grade release 								//
// $sid: student ID, generally set by the User() class 			                            //
// $db: A database object                                                                     //
//////////////////////////////////////////////////////////////////////////////////////////////
Class GradeReleaseCheck {
	private $status;
	private $removed;

	function __construct($sid, $db) {
		$rows = "COUNT(sid) as count";
		$where = "sid = :sid";
		$values = array("sid" => $sid);
		$results = $db->select(MAINTABLE, $rows, $where, null, $values);

		if($results) {
			$results = $db->getResults();
			$row = array_shift($results);
			if($row['count'] > 0) {
				$this->status = true;
			
				$where = "sid = :sid and status = 'removed'";
				$results = $db->select(MAINTABLE, $rows, $where, null, $values);
				if($results) $results = $db->getResults();
				$row = array_shift($results);

				if($row['count'] > 0) {					
					$this->removed = true;
				} else {
					$this->removed = false;
				}				
			} else {
				$this->status = false;
				$this->removed = null;
			}
		}
	}
	public function getGradeReleaseStatus() {
		return $this->status;
	}
	public function getRemovedStatus() {
		return $this->removed;
	}
}



?>