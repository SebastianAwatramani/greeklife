<?php
require("../includes/meta.php");
require("../includes/Database.php");
require("../includes/TextCleaner.php");
require("../includes/MembersList.php");
require("../includes/Form.php");
require("../includes/layout/header.php");
require("../includes/createDatabase.php");

if(isset($_GET['request_id'])) {
	$searchFor = $_GET['request_id'];
	$membersList = new MembersList(MAINTABLE, $searchFor, null, $db);
}
?>

<section id="main">
<h1><?php echo ucfirst(SOCIETY) . " " . ucfirst(MEMBERSTATUS) . " " . "Remove"; ?></h1>						

<?php
	if(!$membersList->checkError()) {
		$membersList->printMembersTable(false);
		//Build form
		$formName = "remove";
		$method = "post";
		$action = FILEPREFIX . "remove_final.php";
		$attributes = array(
			"id" => $formName,
			"onSubmit" => "return check_form();"
			);
		$form = new Form($formName, $method, $action, $attributes);

		$name = "reason";
		$type = "text";
		$attributes = array("id" => $formName, "required" => "required");
		new InputBox($name, $type, $attributes, true);

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
		$attributes['value'] = $membersList->getSociety();
		new InputBox($name, $type, $attributes);

		$name = "searchFor";
		$attributes['id'] = $name;
		$attributes['value'] = $searchFor;
		new InputBox($name, $type, $attributes);

		new SubmitButton("Remove");
		new CancelButton();

$form->closeForm();
	} else {
		echo "<p class='error'>No results matched your search.  Please go back and try again.</p>";
	}

?>


</section>

<?php include("../includes/layout/footer.php"); ?>