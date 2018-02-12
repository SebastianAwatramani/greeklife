<?php 

require_once("../includes/meta.php");
require_once ("../includes/layout/header.php");
require_once("../includes/FormStatus.php");
require_once("../includes/DataBase.php");
require_once("../includes/SocietyList.php");
require_once("../includes/GradeReleaseCheck.php");
require_once("../includes/User.php");
require_once("../includes/Form.php");
require("../includes/createDatabase.php");

//Instantiate user class
(MEMBERSTATUS === "member") ? $user = new Member($db) : $user = new Recruit($db);


$formName = SOCIETY . "_" . MEMBERSTATUS . "s";
$formStatus = new FormStatus($formName, $db);

$gradeReleaseCheck = new GradeReleaseCheck($user->getSid(), $db);
$gradeReleaseSubmitted = $gradeReleaseCheck->getGradeReleaseStatus();
$studentRemoved = $gradeReleaseCheck->getRemovedStatus();

?>
<section id="main">
<h1 class = 'sectionHeading'><?php echo ucfirst(SOCIETY) . " " . ucfirst(MEMBERSTATUS) .  " " ?>Grade Release Form</h1>
<?php include("../includes/studentInfo.php"); ?>

<?php 
if ($formStatus->getFormStatus() === 'online') :
	if($gradeReleaseSubmitted === false || $studentRemoved === true) : 

	//Build form
	$formName = "gradeRequest";
	$method = "post";
	$action = FILEPREFIX . "grade_release_form_confirm.php";
	$attributes = array("id" => $formName);
	$form = new Form($formName, $method, $action, $attributes);

		if(MEMBERSTATUS == "member") {
			$societyList = new SocietyList($db, null, false);
			$societyList->printSocietyRadioButtons(false, true);
		}
		
?>
	<article>
		<p>I, <?php echo htmlspecialchars($user->getFullName()) ?>, hereby give my permission to the University of Colorado Boulder to release my semester and cumulative grade point averages to the following organizations and persons:
		</p>
		<ul id="organizations">
			<li>University of Colorado Boulder Office of Greek Life</li>
			<li>My Local Chapter Office</li>
			<li>My Chapter National Office</li>
			<li>My Chapter Advisor and/or the designated agents of the Sorority or Fraternity for which I am a member</li>
		</ul>
<?php
	//This section of the next is only needed for members, not recruits
	if(MEMBERSTATUS == "member") :
?>
		<p>This information may be released for the duration of my membership in a Multicultural Greek Organization while I am a student of the University of Colorado Boulder.</p>

		<p>I also give my permission for the above organizations and persons to see my semester and cumulative grade point averages for the duration of my membership in a Multicultural Greek Organization while I am a student of the University.</p>
<?php endif; ?>
		<p>My semester and cumulative grade point averages will be released by the Office of the Registrar at the University of Colorado Boulder to the above organizations and persons for the purpose of determining eligibility for membership in the above named organizations and/or ability to hold leadership positions within the sorority</p>

		<p class="disclaimer">**By submitting this form by clicking on the submit button below you are giving your official legal permission for the Univesity of Colorado Boulder to release your semester and cumulative grade point average to the above third parties</p>

	</article>

<?php 
//No society list for recruits, we just set it to all
if(MEMBERSTATUS === "recruit") {
	$name = "societySearch";
	$type = "hidden";
	$attributes = array("value" => "ALL");
	new InputBox($name, $type, $attributes);
}

//Student agrees hidden field
$name = "studentAgrees";
$type = "hidden";
$attributes = array("value" => "yes");
new InputBox($name, $type, $attributes);

new SubmitButton();

$form->closeForm();



?>	

</section>
<?php 

elseif ($gradeReleaseSubmitted === true && $studentRemoved === false) : ?>
<p>You have already submitted a grade release request.</p>

<?php endif;  //Ends if($gradeReleaseSubmitted === false || $studentRemoved === true) : 

else : ?>
	<p>*This Form is Currently Not Available.</p>
<?php endif; //ends if ($formStatus->getFormStatus() === 'online') : ?>

<?php  

include ("../includes/layout/footer.php");


?>