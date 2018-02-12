<?php
require_once("includes/meta.php");
include ("includes/layout/header.php");

//
$errorCodes = array();
$errorCodes[0] = "There was an error connecting to the database.  Please try again later.";
$errorCodes[1] = "Request Id Missing";
$errorCodes[2] = "An Unknown Error Occured";

if(isset($_GET['errorCode'])) {
	$errorCode = $_GET['errorCode'];
	$errorCode = (int) $errorCode;
	if($errorCode < count($errorCodes)) {
		$error = $errorCodes[$errorCode];
	} else {
		$error = $errorCodes[2];
	}
} else {
	$error = $errorCodes[2];
}

?>
<section id="main">
	<h1>Error</h1>
	<p><?php echo $error ?></p>
	</section>
<?php include ("includes/layout/footer.php");?>