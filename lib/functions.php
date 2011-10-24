<?php
require_once('../config/config.php');

$conn = mysql_connect($hostname,$username,$password);
mysql_select_db("convocation",$conn);

function new_user() {
	echo "<div id='user_input'>Please submit your email ID for further instructions. <br />Please make sure that you use the same email ID that was used in Souvenir, 2011.
	<form action='' method='get'>
	<br />Email: <input type='text' name='email' id='email' />
	<input type='submit' value='Submit' />
	</form></div>";
}

function check_email($email) {
	if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
		echo "<div id='user_input_text'>Please Use Valid Email ID.</div>";
	}
	else {
		$emails = mysql_query("SELECT * FROM student_profile WHERE email='".$email."'");
		$emails_r = mysql_fetch_assoc($emails);
		if(mysql_num_rows($emails)==0) {
			echo "<div id='user_input_text'>Sorry, But we dont have record for any user with the email: <i>".$email."</i>. Please make sure that you are using the same email ID that was used in souvenir, 2011. If you dont remember that email ID and your name is present in this <a href='http://gndec.ac.in/convocation/2011/eligible_students.xls'>list</a> of eligible students for the Convocation, 2011 then please send an email to <i>convocation@gndec.ac.in</i> with the following details.
			<ul>
			<li>Name</li>
			<li>Roll No</li>
			<li>University Roll No</li>
			<li>Batch</li>
			<li>Branch</li>
			<li>Will you attend Rehearsal before convocation ?</li>
			<li>Will you attend the actual convocation ceremony ?</li>
			</ul></div>";
		}
		else {
			if($emails_r['eligible']=='Yes') {
				$att = mysql_fetch_assoc(mysql_query("SELECT attending,rehearsal FROM student_profile WHERE email='".$email."'"));
				if($att['attending']=='Yes') {
				echo "<div id='user_input_text'>User with email: <i>".$email."</i> has already responded with the following :
				<ul>
				<li> Attending Rehearsal   : ".$att['rehearsal']."</li>
				<li> Attending Convocation : ".$att['attending']."</li>
				</ul>
				</div>";
				}
		
				if($att['attending']=='No') {
					$link = md5(time());
					$url = "http://gndec.ac.in/convocation/index.php?q=".$link;
					mysql_query("UPDATE student_profile set confirm_link='".$link."' WHERE email='".$email."'");
					$details = mysql_fetch_assoc(mysql_query("SELECT * FROM student_profile WHERE email='".$email."'"));
					$to = $email;
					$subject = "Confirm RSVP to Convocation 2011 at GNDEC, Ludhiana";
					$message = "Hi, ".$details['firstname']." ".$details['middlename']." ".$details['lastname']."\nIf you want to attend the convocation as well as rehearsal please click on the link below\n".$url;
					$from = "convocation@gndec.ac.in";
					$headers = "From:" . $from;
					mail($to,$subject,$message,$headers);
					echo "<div id='user_input_text'>An email has been sent to <i>".$email."</i>, please check your Inbox/Spam/Junk folder for further instructions.</div>";
				}
			}
			else {
				echo "<div id='user_input_text'>You are not eligible for the Convocation, 2011. Because we have not received your Degree from PTU yet. If you have passed all your exams and your name is not present in this <a href='http://gndec.ac.in/convocation/2011/eligible_students.xls'>list</a> of eligible students then please contact Dean Academic through H.O.D of your concerned department.</div>";
			}
		}
	}
}

function confirm_rsvp($confirm_link) {
	$rsvp = mysql_query("SELECT confirm_link from student_profile WHERE confirm_link='".$confirm_link."'");
	if(mysql_num_rows($rsvp)==0) {
		echo "Wrong Confirmation Link, Please click <a href='http://gndec.ac.in/convocation?q=new'>here</a> to try again";
	}
	else {
		
		echo "<div id='radio_input'>Will You attend the rehearsal ?
			<form action='' method='get'>
			<br/><input type='radio' id='rehearsal' name='rehearsal' onClick='disable_radio(this.value)' value='Yes' checked='checked'> Yes<br>
			<input type='radio' id='rehearsal' name='rehearsal' onclick='disable_radio(this.value)' value='No'> No<br>
			
			<br />Will You attend the actual Convocation ceremony ?
			<br/><input type='radio' id='attending1' name='attending' value='Yes' checked='checked'> Yes<br>
			<input type='radio' id='attending2' name='attending' value='No' disabled> No<br>
			<input type='hidden' name='confirm_link' value='".$confirm_link."' />
			<input type='submit' id='submit' value='Submit' /></div>";
			
	}
}
	
