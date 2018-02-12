<?php
//THIS PAGE NEEDS ITS TABLE CODE UPDATED ONCE PHP STUFF IS DONE ALL AROUND BECAUSE SOME LINES ARE TOO LONG (e.g. KAPPA KAPPA GAMMA)
//ADD PRINT BUTTON
require_once("../includes/meta.php");
require("../includes/Database.php");
require("../includes/MembersList.php");

$db = new Database();
$res = $db->connect();

if(!$res) {
  echo "There was a problem accessing the database.  Please contact the system administrator";
  exit();
}

if (isset($_GET['searchFor']) && !empty($_GET['searchFor'])) {
  $searchFor = $_GET['searchFor'];
} else {
  $searchFor = null;
}

if (isset($_GET['societySearch']) && !empty($_GET['societySearch']) && $_GET['societySearch'] != "All") {
  $society = $_GET['societySearch'];
 
} else { 
  $society = "All";
}

$membersList = new MembersList(MAINTABLE, $searchFor, $society, $db);

?>

<!DOCTYPE HTML> 
<html>
<head>
</head>
<body>

<?php
$membersList->printMembersTable(false);
?>   

   

</body>
</html>