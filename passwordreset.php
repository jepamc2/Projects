<?php
//Developer(s): Joshua Mercer
//Date: 3/16/2018
//Purpose: to allow user to reset password
//Edited by: 
$pagetitle = "Reset Password";
require_once 'header.php'; //header
require_once "connect.php"; //connection
$showform = 0;
$URL = 'http://groupc18.istwebclass.org/Group4_V4';
//use namespace from PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';	

	if (!isset($_GET['email']) || !isset($_GET['token'])){ //make sure token and email are passed
		$showform = 0;	
	}else{ //if token and email are passed
		//set variables to passed variables
		$showform = 1;	
		$emailaddress = $_GET['email'];
		$token = $_GET['token'];
	}
	if(isset($_POST['updatesubmit'])){
		$errormeg = "";
		//cleanse data
		$formfield['ffpass'] = trim($_POST['pass']);
		$formfield['ffpass2'] = trim($_POST['pass2']);
		//validate data
		if(empty($formfield['ffpass'])){$errormsg .= "<p>Your password is empty.</p>";}
		if(empty($formfield['ffpass2'])){$errormsg .= "<p>Your confirm password is empty.</p>";}
		//if passwords dont match
		if($formfield['ffpass'] != $formfield['ffpass2']){$errormsg .= "<p>Your passwords do not match.</p>";}
		
		$lowercase = preg_match('@[a-z]@',$formfield['pass']); //checking for any lowercase values
		$uppercase = preg_match('@[A-Z]@',$formfield['pass']); //checking for any uppercase values
		$numeric = preg_match('@[0-9]@',$formfield['pass']); //checking for any numeric values
		$specialchars = preg_match('@[/W]@',$formfield['pass']); //checking for any non-alphanumeric values

		if (!$lowercase || !$uppercase || !$numeric || !$specialchars || strlen($formfield['pass']) > 8){
			//print error particular to false return
			if (!$lowercase){$errormsg .= '<p>Password must contain at least one lower-case letter.</p>'};
			if (!$uppercase){$errormsg .= '<p>Password must contain at least one upper-case letter.</p>'};
			if (!$numeric){$errormsg .= '<p>Password must contain at least one numeric character.</p>'};
			if (!$specialchars){$errormsg .= '<p>Password must contain at least one special character.</p>'};
			if (strlen($formfield['pass']) > 8){$errormsg .= '<p>Password must contain at least 8 characters long.</p>'};
		}
		if($errormsg != ""){
				echo $errormsg;
			}else{
				$options = [
					'cost' => 12,
					'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
				];
				$encpass = password_hash($formfield['ffpass'], PASSWORD_BCRYPT, $options);
				try{
					//select from database where url values match database information
					$sqlchecktoken = "SELECT * FROM staff WHERE dbstaffemail = :bvemail AND dbstafftoken = :bvtoken"; //sql query
					$stmtchecktoken = $db->prepare($sqlchecktoken); //prepare statement
					$stmtchecktoken->bindValue(':bvemail', $emailaddress); //bind email to value
					$stmtchecktoken->bindValue(':bvtoken', $token);//bind email to value
					$stmtchecktoken->execute(); //execute statement
					$count = $stmtchecktoken->rowCount();
					if ($count > 0){
					$sqlupdateconfirm = "UPDATE staff SET dbstafftoken = '' , dbstaffpassword = :bvpass WHERE dbstaffemail = :bvemail";
					$stmtupdateconfirm = $db->prepare($sqlupdateconfirm); //prepare statement
					$stmtupdateconfirm->bindValue(':bvemail', $emailaddress); //bind email to value
					$stmtupdateconfirm->bindValue(':bvpassword', $encpass); //bind password to value
					$stmtupdateconfirm->execute();//execute statement
					echo '<h2>Password has been Reset!</h2>'; //tell password has been reset
					echo "<p> You can now <a href='login.php'>Log In</a></p>";
					echo '';
					$showform = 2;
					}else{
						echo '<script>window.location = "login.php";</script>'; //redirect back to register page
					}
				}catch(PDOException $e){
					echo 'ERROR!!' .$e->getMessage();
					exit();
				}
				}
	}
	if(isset($_POST['sendsubmit'])){
		$errormeg = "";
		$formfield['ffemail'] = trim(strtolower($_POST['email']));
		if(empty($formfield['ffemail'])){$errormsg .= "<p>Your email is empty.</p>";}
		
		if($errormeg != ""){
			echo $errormsg;
		}else{
			//VALIDATE THE EMAIL
			try{ //checking to see if email exists in database
				$sqlemailcheck ='SELECT * from staff WHERE dbstaffemail = :bvstaffcheck'; //SQL query
				$stmtemailcheck = $db->prepare($sqlemailcheck); //preparing statement
				$stmtemailcheck->bindValue(':bvemailcheck', $formfield['ffemail']);
				$stmtemailcheck->execute(); //executing prepared statements
				$count = $stmtemailcheck->rowCount();
			if ($count > 0){ //counting rows in database where email exists
				//create a token
				$passtoken = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM/$#1234567890';
				$passtoken = substr(str_shuffle($passtoken),0,15);	
				//send email
				try {
					$email = $formfield['ffemail'];
					
					$mail = new PHPMailer(true); //mail object 
					//Server settings
					$mail->isSMTP(); //set SMTP
					$mail->SMTPDebug = 2; //verbose
					$mail->SMTPAuth = true; 
					$mail->Host = 'mail.groupc18.istwebclass.org'; //set host
					$mail->Username = 'emailconfirm@groupc18.istwebclass.org'; //set username
					$mail->Password = 'password'; //set password
					//$mail->SMTPSecure = 'tls'; //set secure TLS 
					$mail->Port = 25; //set port                                   

					//Recipients
					$mail->setFrom('emailconfirm@groupc18.istwebclass.org', 'Sweetgrass');
					//$mail->setFrom('mail.groupc18.istwebclass.org', 'Sweetgrass'); 
					$mail->addAddress($email, "");
					//Content
					$mail->isHTML(true); //set html
					$mail->Subject = 'Password Reset'; //set subject
					$mail->Body = 'Please click the link below to reset your password. <br><br>'. $URL.'/passwordreset.php?email='.$email.'&token='.$passtoken; //body 
					$mail->AltBody = $URL.'/passwordreset.php?email='.$email.'&token='.$passtoken;//alt body
					$mail->send(); //send email
					if (!$mail->send()){ //check to see if email has sent
						echo "Error! Please try again!";
					}else{
						echo "You have been a Reset Password email!";
					}
				}catch (Exception $e) {echo $e->errorMessage();} //throw exception error
				
			}else{
				echo 'ERROR: Email not found';
			}
			}catch(PDOException $e){ //catch errors
				echo 'ERROR!' . $e->getMessage();
				echo 'ERROR IN EMAIL CHECK';
				exit();
			}	

		}	
	}
	if ($showform == 0){
?>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
			<fieldset>
				<legend>
					Customer Email
				</legend>
				<table>
					<tr>
						<th><label for="email">Email:</label></th>
						<td><input type="text" name="email" id="email" /></td>
					</tr>
					<tr>
						<th>Submit:</th>
						<td><input type="submit" name="sendsubmit" value="SUBMIT"/></td>
					</tr>
				</table>
			</fieldset>
		</form>
<?php
}else if ($showform == 1){
?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
			<fieldset>
				<legend>
					Reset Password
				</legend>
				<table>
					<tr>
						<th><label for="pass">Password:</label></th>
						<td><input type="password" name="pass" id="pass" /></td>
					</tr>
					<tr>
						<th><label for="pass2">Confirm Password:</label></th>
						<td><input type="password" name="pass2" id="pass2" /></td>
					</tr>
					<tr>
						<th>Submit:</th>
						<td><input type="submit" name="updatesubmit" value="SUBMIT"/></td>
					</tr>
				</table>
			</fieldset>
		</form>
<?php
}else if ($showform == 2){
?>

<?php
}
include_once 'footer.php';
?>