<?php

require_once("../includes/meta.php");
include ("../includes/layout/header.php");
include_once("../includes/FormStatus.php");
include_once("../includes/DataBase.php");
include_once("../includes/GradeReleaseCheck.php");
include_once("../includes/User.php");
include_once("../includes/Form.php");
require("../includes/createDatabase.php");

(MEMBERSTATUS === "member") ? $user = new Member($db): $user = new Recruit($db);

$gradeReleaseCheck = new GradeReleaseCheck($user->getSid(), $db);
$gradeReleaseStatus = $gradeReleaseCheck->getGradeReleaseStatus();
$removed = $gradeReleaseCheck->getRemovedStatus();

?>

<section id="main">
<h1 class = 'sectionHeading'>Revoke Grade Release Form</h1>
<?php
if($gradeReleaseStatus === true && $removed === false):
	//Build form
	$formName = "grade_request";
	$method = "post";
	$action = FILEPREFIX . "revoke_grade_release_form_confirm.php";
	$attributes = array("id" =>"grade_request");
	$form = new Form($formName, $method, $action, $attributes);

?>
	<article>
		<p>I, <?php echo $user->getFullName(); ?>, hereby revoke my permission to the University of Colorado Boulder to release my future semester and cumulative grade point averages to the following organizations and persons:
		</p>
		<ul id="organizations">
			<li>University of Colorado Boulder Office of Greek Life</li>
			<li>My Local Chapter Office</li>
			<li>My Chapter National Office</li>
			<li>My Chapter Advisor and/or the designated agents of the Sorority or Fraternity for which I am a member</li>
		</ul>
		<p>*This request is limited to grades released to the above organizations.</p>
		<p class="disclaimer">**By submitting this form by clicking on the submit button below you are giving your official legal permission for the Univesity of Colorado Boulder to release your semester and cumulative grade point average to the above third parties</p>
	</article>

<?php 
new SubmitButton(); 
$form->closeForm(); 

elseif($removed === true): ?>

<p>You have already revoked your grade release</p>

<?php else: ?>

<p>You currently do not have an active <?php echo MEMBERSTATUS; ?> grade release in our system.</p>

<?php endif; ?>
</section>

<?php  include ("../includes/layout/footer.php"); ?>

