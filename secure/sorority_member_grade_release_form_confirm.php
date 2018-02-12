<?php 

//THIS PAGE RELIES ON THE HEADER INFORMATION TO GET THE CORRECT VALUES TO INSERT INTO THE DATABASE.  AS SUCH, IT CANNOT BE FINISHED UNTIL WE FIGURE THAT PART OUT.   IT CURRENTLY USES HARDCODED INFORMATION

require_once("../includes/meta.php");
require_once ("../includes/layout/header.php");
require_once("../includes/FormStatus.php");
require_once("../includes/DataBase.php");
require_once("../includes/GradeReleaseCheck.php");
require_once("../includes/User.php");
require_once("../includes/Email.php");
require("../includes/createDatabase.php");
require_once("../includes/Log.php");

//$society stores the particular society for which they're signing up, or "ALL" for recruits
$society = $_POST['societySearch'];

(MEMBERSTATUS === "member") ? $user = new Member($db) : $user = new Recruit($db);

//Set a reference number in the $user class
$user->setRefNum();

$formName = SOCIETY . "_" . MEMBERSTATUS . "s";
$formStatus = new FormStatus($formName, $db);

//Double check to see that there isn't already a record for them in the system
$gradeReleaseCheck = new GradeReleaseCheck($user->getSid(), $db);
$gradeReleaseSubmitted = $gradeReleaseCheck->getGradeReleaseStatus();
$studentRemoved = $gradeReleaseCheck->getRemovedStatus();

//Set $success to false to check for errors along the way
$success = false;

if(isset($_POST['studentAgrees']) && $_POST['studentAgrees']=== "yes") {	
	if($formStatus->getFormStatus() === "online") {
			if($gradeReleaseSubmitted === false || $studentRemoved === true) {
			//Email info
			$email = new Email($user);
			$send = $email->send();
			if(!$send) {
				//log the problem if the email doesn't work for whatever reason
				//Log function is implemented, but will have to be on server before I can get the email error codes and such (no server locally)
			}

			//Inset new member into Db or remove the removed attributes from a removed user
			($gradeReleaseSubmitted === false) ? $result = $user->addUserToDb($society) : $result = $user->resubmitGradeRelease($society);
			
			if($result) {
				$success = true;
			} else {
				$success = false;
				$error = "There was an error attempting to add you to the database.  Please try again later.";
				$log = new Log("../logs/log.txt");
				$log->writeLog($db->getError());
			}
		} else {
			$success = false;
			$error = "You have already submitted a grade release form.";  
		}
	} else {
		//This should log the error and display an error.  Maybe to come back later?  Although we really shouldn't ever run into this problem unless the form goes offline randomly.

		$success = false;
		$error = "Form is offline";
	}
} else {
	//Technically, this should never happen.  The studentAgrees field is a hidden field and unless someone manually edits the HTML before submitting, it will always be yes.  However, for the sake of being thorough it's confirmed before submitting.
	$success = false;
	$error = "You Must Agree to the Grade Release.  <a class = 'back' href='javascript:history.back()''>Go Back</a>";
}


?>
<section id="main">
<h1><?php echo ucfirst(SOCIETY) . " " . ucfirst(MEMBERSTATUS).  " " ?>Grade Release Form</h1>

<?php
//Will have to update this after header info is available (student name, etc.)

if($success === true):

?>
<h2>Thank you!</h2>
<h3>Your Grade Release Form has been accepted.</h3> 
</section>
<?php  
///Should I create an errorBox clasS?
else : echo "<p class='error'>" . $error . "</p>";
endif;

include ("../includes/layout/footer.php");
?>




