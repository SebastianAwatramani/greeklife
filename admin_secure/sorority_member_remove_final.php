<?php

require_once("../includes/meta.php");

//If user cancels
if(isset($_POST['Cancel'])) {
	$returnUrl = FILEPREFIX;
	$returnUrl .= "grade_release_admin.php?";
	$returnUrl .= "&societySearch=";
	$returnUrl .= urlencode($_POST['societySearch']);
	echo $returnUrl;
	header("Location: " . $returnUrl);
	exit();
}

include("../includes/layout/header.php");
require("../includes/Database.php");
require_once("../includes/Redirect.php");
require("../includes/createDatabase.php");

//Prepare info
if($_POST['reason'] === "other") {
	$reason = $_POST['reason_other'];
} else {
	$reason = $_POST['reason'];

}

$userInfo = array(
	"firstName" => $_POST['firstName'],
	"lastName" => $_POST["lastName"],
	"requestId" => $_POST['requestId']
	);

$user = new CustomUser($userInfo, $db);
if($user->checKForUser()) {
	$result = $user->revokeGradeRelease($reason);

	if($result) {
		$success = TRUE;
	} else {
		$success = FALSE;
		$error = $db->getError();
	}
}

?>

<section id="main">
<?php include("../includes/adminSectionHeader.php"); ?>

<?php if($success === true) :

?>
<h2>Success!</h2>
<p><a href='<?php echo FILEPREFIX; ?>grade_release_admin.php' class = 'boldLink'>Click here</a> if you are not redirected in 3 seconds</p>
<?php $redirect = new Redirect(FILEPREFIX . "grade_release_admin.php");

else :
	?>
<h2>Failure!</h2>

<?php 
//LOG

endif;

?>
</section>

<?php

require("../includes/layout/footer.php");

?>