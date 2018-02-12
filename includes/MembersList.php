<?php

//////////////////////////////////////////////////////////////////////////////////////////////////
// MemberList() Provides a set of methods that query the database of members, store the data for//
// later usage, and prints a table of members 													//
//  																							//
// Arguments:																					//
// 		$table: The table of members, e.g. sorority_recruits, sorority_members					//
// 		$searchFor: A query string, e.g. name, sid, request_id 									//
//		$societySearch: If searching a specific society, e.g. phi beta kappa 					//
//		$db: A database object 																	//
//		$removeLink: bool, set true to print a remove link next to names (admin function)   	//								//                  																			//
//																								//
//////////////////////////////////////////////////////////////////////////////////////////////////

Class MembersList {
	private $members;
	private $blankRow;
	private $firstName;
	private $lastName;
	private $sorority;
	private $requestId;
	private $memberCount;
	private $societySearch;
	private $error;

	function __construct($table, $searchFor = null, $societySearch = null, $db) {

		$this->error = false;

		$rows = "request_id, last_name, first_name, sorority, date";
		$where = " status != 'graduated' AND status != 'removed' AND removed != 'yes'";
		$order = "sorority, last_name";

		if($societySearch !== "All" && $societySearch != null) {
			$where .= " AND sorority = '$societySearch'";
		}

		if(!empty($searchFor)) {
			$where .= " AND (first_name = :searchFor or last_name = :searchFor or sid = :searchFor or request_id = :searchFor)";
			$values = array('searchFor' => $searchFor);

			if(!$res = $db->select($table, $rows, $where, $order, $values)) $this->error = true;
		} else {
			if(!$res = $db->select($table, $rows, $where, $order)) $this->error = true;
		}

		$this->members = $db->getResults();
		$this->memberCount = count($this->members);
		$this->searchFor = $searchFor;
		$this->societySearch = $societySearch;
			//
		$this->blankRow = <<<EOF
					<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					</tr>

					<tr>
					<td height=10 bgcolor="#E5E5E5"></td>
					<td bgcolor="#E5E5E5"></td>
					<td bgcolor="#E5E5E5"></td>
					<td bgcolor="#E5E5E5"></td>
					<td></td>
					</tr>

					<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					</tr>
EOF;

	}

	public function getMembers() {
		return $this->members;
	}
	public function getFirstName() {
		return $this->firstName;
	}
	public function getLastName() {
		return $this->lastName;
	}
	public function getSociety() {
		return $this->sorority;
	}
	public function getRequestId() {
		return $this->requestId;
	}
	public function getMemberCount() {
		return $this->memberCount;
	}
	public function checkError() {
		return $this->error;
	}

	public function printMembersTable($removeLink) {

		echo <<<EOF
		 <table id="membersTable">
		<THEAD>
            <tr>
              <td><strong><u>Last Name </u></strong></td>
              <td><strong><u>First Name </u></strong></td>
              <td><strong><u>Sorority</u></strong></td>
              <td><strong><u>Date</u></strong></td>
              <td>&nbsp;</td>
            </tr>
        </THEAD>
EOF;

		$count = 0;
		reset($this->members);
		$insertBlankRow = '';

		if(count($this->members) > 0) {
		do {
			$this->requestId = array_shift($this->members[$count]);
			$this->firstName = htmlspecialchars($this->members[$count]['first_name']);
			$this->lastName = htmlspecialchars($this->members[$count]['last_name']);
			$this->sorority = htmlspecialchars($this->members[$count]['sorority']);
			$date = htmlspecialchars($this->members[$count]['date']);

			if($this->members[$count]['sorority'] != $insertBlankRow) {
				echo $this->blankRow;
			} 
			echo "<tr>";
			foreach ($this->members[$count] as $key => $value) {
				echo "<td>$value</td>";

			}			
			
			if($removeLink == true) {
				echo "<td>";
				echo "<a href='";
				$removeUrl = FILEPREFIX . "remove.php?request_id={$this->requestId}&societySearch={$this->societySearch}'>Remove</a></td>";
				echo $removeUrl;
			}
			echo "</tr>";

			$insertBlankRow = $this->members[$count]['sorority'];
			$count++;
			
		} while ($count != count($this->members));
	}
	echo "</table>";


	}

}




?>