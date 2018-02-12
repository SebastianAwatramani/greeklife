<?php 
//TO DO: Formatting

require_once("includes/meta.php");
include ("includes/layout/header.php");
require("includes/Database.php");
require("includes/FormStatus.php");
require("includes/createDatabase.php");

//Check if form is online
$formName = SOCIETY . "_" . MEMBERSTATUS . "s";
$formStatus = new FormStatus($formName, $db);


 ?>

<section id="main">
<h1>Panhellenic <?php echo ucfirst(SOCIETY)?> Grade Release Requests</h1>
<?php

//Build links to the release and revoke pages
if($formStatus->getFormStatus() === 'online'):
	//Construct links
	$gradeReleaseLink = "<a class = 'boldLink' href='secure/";
	$gradeReleaseLink .= FILEPREFIX;
	$gradeReleaseLink .= "grade_release_form.php'>";
	$gradeReleaseLink .= "Grade Release Form";
	$gradeReleaseLink .= "</a>";

	$gradeRevokeLink = "<a class = 'boldLink' href='secure/";
	$gradeRevokeLink .= FILEPREFIX;
	$gradeRevokeLink .= "revoke_grade_release_form.php'>";
	$gradeRevokeLink .= "Revoke Request Form";
	$gradeRevokeLink .= "</a>";

?>
<p>Eligibility for membership and/or eligibility to hold leadership positions within a Panhellenic <?php echo ucfirst(SOCIETY)?> may be based upon your semester and cumulative grade point averages at the University of Colorado. </p>
<p>FERPA, The Family Educational Rights And Privacy Act of 1974, requires your permission to release grade information to third parties. </p>
<p>The  electronic <?php echo $gradeReleaseLink; ?> allows you to give permission to the University of Colorado to release your grade information. <p>If you are no longer a member of a <?php echo SOCIETY?> you may revoke your grade release. Do NOT submit the following form if you are still a member of a <?php echo SOCIETY?> and wish to remain a member: <?php echo $gradeRevokeLink ?> </p>
<?php 
//If offline, display form offline message
elseif($formStatus->getFormStatus() === 'offline'):
	echo $formStatus->getDisplayText();
endif;
?>
</section>
<?php include ("includes/layout/footer.php");?>
