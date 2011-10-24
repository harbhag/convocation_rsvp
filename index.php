<?php
require_once('layout/header.php');
require_once('lib/functions.php');


/*If Someone directly comes to this link, redirect to the http://gndec.ac.in
 */

if(!isset($_GET['q']) && !isset($_GET['email'])) {
	if(isset($_GET['attending'])) {
		mysql_query("update student_profile set attending='".$_GET['attending']."',rehearsal='".$_GET['rehearsal']."' WHERE confirm_link='".$_GET['confirm_link']."'");
		echo "<div id='user_input_text'>Your following response has been recorded:
		<br /><ul>
		<li> Attending Rehearsal   : ".$_GET['rehearsal']."</li>
		<li> Attending Convocation : ".$_GET['attending']."</li>
		</ul>
		</div>";
	}
	else {
		header("location:http://gndec.ac.in");
	}
}

/*If the users has submitted the email ID, then run the block below
 */

if(isset($_GET['email'])) {
	check_email($_GET['email']);
}

if(isset($_GET['q'])) {
	if($_GET['q']=='new') {
		new_user();
	}
	if($_GET['q']!='new' && $_GET['q']!='') {
		confirm_rsvp($_GET['q']);
	}
}

require_once('layout/footer.php');
?>
