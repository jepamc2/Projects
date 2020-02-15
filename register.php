<?php
//Developer(s): Joshua Mercer
//Date: 3/16/2018
//Purpose: Allow users to register with an email confirmation
//Edited by: 
$pagetitle = "Registration";
require_once 'header.php';

//NECESSARY VARIABLES
$errormsg = "";
$showform = 1;
$URL = 'http://groupc18.istwebclass.org/Group4_V5';
//DATABASE CONNECTION
require_once "connect.php";
//use namespace from PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';	

	echo '<div class="bigpaper">';
	echo '<center>';
	echo '<br><br><br><br>';
	
		if( isset($_POST['submit']) )
		{
			$showform = 2; //set show form to 2 to show email confirm message
			
			//data cleansing all fields entered by user
			$formfield['fffirst'] = trim($_POST['first']);
			$formfield['fflast'] = trim($_POST['last']);
			$formfield['ffaddress'] = trim($_POST['address']);
			$formfield['ffemail'] = trim(strtolower($_POST['email']));
			$formfield['ffcity'] = trim($_POST['city']);
			$formfield['ffstate'] = trim($_POST['state']);
			$formfield['ffzip'] = trim($_POST['zip']);
			$formfield['ffpass'] = trim($_POST['pass']);
			$formfield['ffpass2'] = trim($_POST['pass2']);
			$formfield['ffphone'] = trim($_POST['phone']);
			$formfield['fflist'] = ($_POST['list']);	
			
			//check for empty form fields
			if (empty($formfield['fffirst'])) {$errormsg = "<p>Your first is empty</p>";}
			if (empty($formfield['fflast'])) {$errormsg = "<p>Your last is empty</p>";}
			if (empty($formfield['ffaddress'])) {$errormsg = "<p>Your address is empty</p>";}
			if (empty($formfield['ffemail'])) {$errormsg = "<p>Your email is empty</p>";}
			if (empty($formfield['ffcity'])) {$errormsg = "<p>Your city is empty</p>";}
			if (empty($formfield['ffstate'])) {$errormsg = "<p>Your state is empty</p>";}
			if (empty($formfield['ffzip'])) {$errormsg = "<p>Your zip is empty</p>";}
			if(empty($formfield['ffpass'])){$errormsg .= "<p>Your password is empty.</p>";}
			if(empty($formfield['ffpass2'])){$errormsg .= "<p>Your confirm password is empty.</p>";}
			if (empty($formfield['ffphone'])) {$errormsg = "<p>Your phone is empty</p>";}
			if ($formfield['fflist'] == "") {$errormsg = "<p>Your list is empty</p>";}
			
			//PASSWORD POLICY
			$lowercase = preg_match('@[a-z]@',$formfield['ffpass']); //checking for any lowercase values
			$uppercase = preg_match('@[A-Z]@',$formfield['ffpass']); //checking for any uppercase values
			$numeric = preg_match('@[0-9]@',$formfield['ffpass']); //checking for any numeric values
			$specialchars = preg_match('@[^\w\s]@',$formfield['ffpass']); //checking for any non-alphanumeric values
			if (!$lowercase || !$uppercase || !$numeric || !$specialchars || strlen($formfield['ffpass']) < 8){
				//print error particular to false return
				if (!$lowercase){$errormsg .= '<p>Password must contain at least one lower-case letter.</p>';}
				if (!$uppercase){$errormsg .= '<p>Password must contain at least one upper-case letter.</p>';}
				if (!$numeric){$errormsg .= '<p>Password must contain at least one numeric character.</p>';}
				if (!$specialchars){$errormsg .= '<p>Password must contain at least one special character.</p>';}
				if (strlen($formfield['ffpass']) < 8){$errormsg .= '<p>Password must contain at least 8 characters long.</p>';}
			}
			
			//CHECK FOR MATCHING PASSWORDS
			if($formfield['ffpass'] != $formfield['ffpass2'])
			{
				$errormsg .= "<p>Your passwords do not match.</p>";
			}
			
			//VALIDATE THE EMAIL
			try{ //checking to see if email exists in database
			$sqlemailcheck ='SELECT dbcustid from customers WHERE dbcustemail = :bvemailcheck'; //SQL query
			$stmtemailcheck = $db->prepare($sqlemailcheck); //preparing statement
			$stmtemailcheck->bindValue(':bvemailcheck', $formfield['ffemail']);
			$stmtemailcheck->execute(); //executing prepared statements
			$count = $stmtemailcheck->rowCount();
			if ($count > 0){ //counting rows in database where email exists
				$errormsg .= "<p>Email already exist!</p>";
			}else{
				//create a token for email
				$emailtoken = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM/$#1234567890';
				$emailtoken = substr(str_shuffle($emailtoken),0,15);	

				if ($errormsg != "") {
						echo "<div>THERE ARE ERRORS!!!";
						echo $errormsg;
						echo "<br><br>";
						echo "</div>";
				}else{
					//create an options associative array
					$options = [
						'cost' => 12, 
						'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM), 
					];
					$encpass = password_hash($formfield['pass'], PASSWORD_BCRYPT, $options); //hash the salted password using bycrypt
					
					try 
					{
						///Create the sql statement string
						$sqlinsert = 'INSERT INTO customers (dbcustfirstname, dbcustlastname, dbcustaddress,
											 dbcustcity, dbcuststate, dbcustzip, dbcustphone,dbcustemail,
											dbcustmailinglist, dbcustpassword, dbcustemailconfirm, dbpermitid, dbcusttoken)
									VALUES (:bvfirst, :bvlast, :bvaddress, :bvcity, :bvstate, :bvzip, 
									:bvphone,:bvemail, :bvmailList, :bvpass, 0, 12, :bvtoken)';
						$stmtinsert = $db->prepare($sqlinsert); //prepare statement passing query string
						//bind all parameters to binding values
						$stmtinsert->bindValue(':bvfirst', $formfield['fffirst']);
						$stmtinsert->bindValue(':bvlast', $formfield['fflast']);
						$stmtinsert->bindValue(':bvaddress', $formfield['ffaddress']);
						$stmtinsert->bindValue(':bvcity', $formfield['ffcity']);
						$stmtinsert->bindValue(':bvstate', $formfield['ffstate']);
						$stmtinsert->bindValue(':bvzip', $formfield['ffzip']);
						$stmtinsert->bindValue(':bvphone', $formfield['ffphone']);
						$stmtinsert->bindValue(':bvemail', $formfield['ffemail']);
						$stmtinsert->bindValue(':bvmailList', $formfield['fflist']);
						$stmtinsert->bindvalue(':bvpass', $encpass);
						$stmtinsert->bindValue(':bvtoken', $emailtoken);
						//execute prepared statement
						$stmtinsert->execute();
						//echo success
						echo "<div>Customer information has been submitted successfully.</div>";
						
					}	
					catch(PDOException $e)
					{
						echo 'ERROR! - 2 ' . $e->getMessage();
						exit();
					}	
		
					try {
						//set vars for email confirmation 
						$email = $formfield['ffemail'];
						$name = $formfield['fffirst']. ' ' . $formfield['fflast'];
						
						$mail = new PHPMailer(true); //mail object 
						//Server settings
						$mail->isSMTP(); //set SMTP
						$mail->SMTPDebug = 2; //verbose
						$mail->SMTPAuth = true;
						$mail->Host = 'mail.groupc18.istwebclass.org'; //set host
						$mail->Username = 'email@groupc18.istwebclass.org'; //set username
						$mail->Password = 'password'; //set password
						$mail->SMTPSecure = 'tls'; //set secure TLS 
						$mail->Port = 25; //set port                                   

						//Recipients
						$mail->setFrom('email@groupc18.istwebclass.org', 'Sweetgrass Kitchen'); 
						$mail->addAddress($email, $name);
						//Content
						$mail->isHTML(true); //set html
						$mail->Subject = 'Email Confirmation'; //set subject
						$mail->Body = 'Please click the link below to confirm email. <br><br>'.$URL.'/confirmemail.php?email='.$email.'&token='.$emailtoken; //body 

						$mail->send(); //send email
						if (!$mail->send()){ //check to see if email has sent
							echo "Registration Error! Please try again!";
						}else{
							echo "You have been registered please verify email!";
						}
					}catch (Exception $e) {echo $e->errorMessage();} //throw exception error

				}
			}
			}catch(PDOException $e){ //catch errors
				echo 'ERROR! - 1' . $e->getMessage();
				echo 'ERROR IN EMAIL CHECK';
				exit();
			}	
		}
	if ($showform = 1){ //if form field one
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
				<table border>
					<tr>
						<th><label for="first">First Name:</label></th>
						<td><input type="text" name="first" id="first" size="10" value="<?php if( isset($formfield['first'])){echo $formfield['first'];}?>"/></td>
					</tr>
					<tr>
						<th><label for="last">Last Name:</label></th>
						<td><input type="text" name="last" id="last" size="10" value="<?php if( isset($formfield['last'])){echo $formfield['last'];}?>"/></td>
					</tr>
					<tr>
						<th><label for="address">Address:</label></th>
						<td><input type="text" name="address" id="address" value="<?php if( isset($formfield['address'])){echo $formfield['address'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="email">Email:</label></th>
						<td><input type="text" name="email" id="email" value="<?php if( isset($formfield['email'])){echo $formfield['email'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="city">City:</label></th>
						<td><input type="text" name="city" id="city" value="<?php if( isset($formfield['city'])){echo $formfield['city'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="state">State:</label></th>
						<td><input type="text" name="state" id="state" value="<?php if( isset($formfield['state'])){echo $formfield['state'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="zip">Zip:</label></th>
						<td><input type="text" name="zip" id="zip" value="<?php if( isset($formfield['zip'])){echo $formfield['zip'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="pass">Password:</label></th>
						<td><input type="password" name="pass" id="pass" value="<?php if( isset($formfield['pass'])){echo $formfield['pass'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="pass2">Confirm Password:</label></th>
						<td><input type="password" name="pass2" id="pass2" value="<?php if( isset($formfield['pass2'])){echo $formfield['pass2'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="phone">Phone:</label></th>
						<td><input type="text" name="phone" id="phone" value="<?php if( isset($formfield['phone'])){echo $formfield['phone'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="list">Mail List:</label></th>
						<td><select name="list" id="list">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" name="submit" value="Submit" class="button" /></td>
					</tr>
				</table>
			</fieldset>
		</form>
			<br><br>
	<?php
	}else if ($showform = 2){ //secondary message
	?>
		<h5>Confirmation Email has been sent to your email account. Please confirm before continuing.<h5>
	<?php
		echo '<br><br><br><br>';
		echo '</center>';
		echo '</div>';
	}
	include_once 'footer.php'; //include footer
	?>