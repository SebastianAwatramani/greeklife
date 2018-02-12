<?php
require_once("../includes/meta.php");
include ("../includes/layout/header.php");
include_once("../includes/DataBase.php");
include_once("../includes/User.php");
require("../includes/createDatabase.php");

(MEMBERSTATUS === "member") ? $user = new Member($db): $user = new Recruit($db);

//This gets added into the DB to let us know that the student revoked their own request
$removedBy = "self";

//Perform revocation
$results = $user->revokeGradeRelease($removedBy);

?>

<section id="main">
	<h1><?php echo ucfirst(SOCIETY) . " " . ucfirst(MEMBERSTATUS).  " " ?>Grade Release Form</h1>
<?php include("../includes/studentInfo.php"); ?>
<?php if($results) :?>

	<p>Your grade release revokation request has been received and processed.</p> 
<?php else:
//log
 ?>
	<p>There was an error processing your request.  Please try again later.</p>
<?php endif; ?>
</section>

<?php include ("../includes/layout/footer.php");?>