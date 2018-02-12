 <?php 
	require_once(dirname(__DIR__).'/meta.php');
 	require("Nav.php");
 	//Set page title variable depending on whether page is admin or user or error (defined in includes/meta.php)
 	if($isAdminPage === TRUE) {
		$title =  "Panhellenic " . ucfirst(SOCIETY) . " Admin";
	} elseif ($isErrorPage === TRUE) {
		$title = "";
	} else {
		$title = "Panhellenic " . ucfirst(SOCIETY) ." " . ucfirst(MEMBERSTATUS) . "s";
	}	
  ?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="https://necolas.github.io/normalize.css/5.0.0/normalize.css">
	<link rel="stylesheet" type="text/css" href="<?php echo HOST; ?>/styles/styles.css">
</head>

<body>
	<header id='header'>
		<h1 class = 'title'><?php echo $title;?></h1>
	</header>	
	<main>
<?php 

require_once(INCLUDESDIR . "User.php");

//Create Navigation menu
$nav = new Nav();
//Create user to check for admin state
$admin = new Admin();

//Create admin menu
if($admin->isAdmin() === TRUE) {
	$adminMenu = array(
		array("label" => "Home", "url"=> ADMINSECURE . "index.php"),		
		array("label" => ucfirst(SOCIETY) . " Member Grade Release Admin", "url" => ADMINSECURE . SOCIETY . "_member_grade_release_admin.php"),
		array("label" => ucfirst(SOCIETY) . " Recruit Grade Release Admin", "url" => ADMINSECURE . SOCIETY . "_recruit_grade_release_admin.php")
		);

	$adminHeading = "Admin";
	$nav->createMenu($adminMenu, $adminHeading);
	unset($admin);
}

//Create remote menu
$remoteMenu = array(
		array("label" => "Home", "url" => "http://www.colorado.edu/registrar/"),
		array("label" => "About our Office", "url" => "http://www.colorado.edu/registrar/about"),
		array("label" => "Academic Calendar", "url" => "http://www.colorado.edu/registrar/students/academic-calendar"),
		array("label" => "Students", "url" => "http://www.colorado.edu/registrar/students"),
		array("label" => "Faculty and Staff", "url" => "http://www.colorado.edu/registrar/faculty-staff/"),
		array("label" => "Accessibility", "url" => "http://www.colorado.edu/registrar/about/accessibility")
	);
$remoteHeader = "Office of the Registrar";
$remoteHeaderUrl = "http://registrar.colorado.edu";

//Create "members" menu
$localMemberMenu = array(
		array("label" => "Grade Release Form",  "url" => SECUREFOLDER . SOCIETY . "_member_grade_release_form.php"),
		array("label" => "Revoke Grade Release", "url" => SECUREFOLDER . SOCIETY . "_member_revoke_grade_release_form.php")
	);
$localMemberHeader = "Members";
$localMemberHeaderUrl = HOST . "/members.php";

//Create "recruits" Menu
$localRecruitMenu = array(
	array("label" => "Grade Release Form",  "url" => SECUREFOLDER .SOCIETY . "_recruit_grade_release_form.php"),
	array("label" => "Revoke Grade Release", "url" =>  SECUREFOLDER . SOCIETY . "_recruit_revoke_grade_release_form.php")
	);
$localRecruitHeader = "Recruits";
$localRecruitHeaderUrl = HOST . "/recruits.php";

//Print nav
$nav->createMenu($localMemberMenu, $localMemberHeader, $localMemberHeaderUrl);
$nav->createMenu($localRecruitMenu, $localRecruitHeader, $localRecruitHeaderUrl);
$nav->createMenu($remoteMenu, $remoteHeader, $remoteHeaderUrl);
$nav->closeNav();


?>


	





