<?php
require("../includes/meta.php");
require("../includes/layout/header.php");
require("../includes/Form.php");
require("../includes/Database.php");
require("../includes/TextCleaner.php");
require("../includes/MembersList.php");
require("../includes/createDatabase.php");

if(isset($_GET['request_id'])) {

	$requestId = $_GET['request_id'];
	$membersList = new MembersList(MAINTABLE, $requestId, null, $db);

} else {
	header("Location: " . HOST . "/error.php?errorCode=1");
}

if(isset($_GET['searchFor'])) {
	$searchFor = $_GET['searchFor'];
}

if(isset($_GET['societySearch'])) {
	$societySearch = $_GET['societySearch'];
} else {
	$societySearch = "All";
}

?>

<section id="main">

<h1><?php echo ucfirst(SOCIETY) . " " . ucfirst(MEMBERSTATUS) . " " . "Remove"; ?></h1>

 <?php
 //This form is basically a confirmation for to delete, and also asks for a deletion reason
$formName = "remove";
$method = "post";
$action = FILEPREFIX . "remove_final.php";
$attributes = array(
	"id" => $formName,
	"onSubmit" => "return check_form();"
	);
$form = new Form($formName, $method, $action, $attributes);

$membersList->printMembersTable(false);

//Print a radio group with the removal reasons
$name = "reason";
$values = array(
	array(
		"value" => "No longer a Member of this Sorority.",
		"label" => $membersList->getFirstName() . " is  no longer a member of " . $membersList->getSociety()
		),
	array(
		"value" => "No longer a student at CU.",
		"label" => $membersList->getFirstName() . " is no longer a student at the University of Colorado, Boulder"
		),
	array(
		"value" => "Other",
		"label" => "Other"
		)
	);
$legend = "Removal Reason";

new RadioGroup($name, $values, $legend, true);

echo <<<EOT
	<script>
	var fieldSet = document.getElementById("reason");
	var inputBox = document.createElement("input");
	inputBox.id = "reason_other";
	inputBox.name = "reason";
	inputBox.type = "text";
	fieldSet.lastChild.parentNode.insertBefore(inputBox, fieldSet.lastChild.nextSibling);
	</script>
EOT;

//Print hidden fields
$name = "requestId";
$type = "hidden";
$attributes = array(
	"id" => $name,
	"value" => $membersList->getrequestId()
	);
new InputBox($name, $type, $attributes);

$name = "firstName";
$attributes['id'] = $name;
$attributes['value'] = $membersList->getFirstName();
new InputBox($name, $type, $attributes);

$name = "lastName";
$attributes['id'] = $name;
$attributes['value'] = $membersList->getLastName();
new InputBox($name, $type, $attributes);

$name = "societySearch";
$attributes['id'] = $name;
$attributes['value'] = $societySearch;
new InputBox($name, $type, $attributes);

new SubmitButton("Remove");
new CancelButton();


$form->closeForm();

?>

</section>

<script type="text/javascript">
	//This script marks the "other" textbox required in the event that the admin selects "other" as a removal reason

	var otherRadioInput = document.querySelector("input[value=Other]");
	var mainRadioInputs = document.querySelectorAll("input[name=reason]");
	var reasonOtherTextInput = document.getElementById("reason_other");

	for(var i=0;  i < mainRadioInputs.length; i++) {
		mainRadioInputs[i].addEventListener('change', function() {
			reasonOtherTextInput.required = false;
		});
	};

	otherRadioInput.addEventListener('change', function(e) {
		reasonOtherTextInput.required = this.checked;
		
	});



</script>
<?php
require("../includes/layout/footer.php");

   ?>
