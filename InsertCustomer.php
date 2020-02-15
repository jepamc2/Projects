<?php
//Developer(s): Jeremy McGuire
//Date:
//Purpose: Insert Customers into the database
//Edited by: Joshua Mercer
$pagetitle = "Insert User Info";
require_once 'header.php';
require_once "connect.php";
//NECESSARY VARIABLES
$errormsg = "";
$showform = 1;
$allowedperms = array(13,1); //allowed permissions for this page
		if( isset($_POST['submit']) )
		{
			
			$formfield['first'] = trim($_POST['first']);
			$formfield['last'] = trim($_POST['last']);
			$formfield['address'] = trim($_POST['address']);
			$formfield['email'] = trim(strtolower($_POST['email']));
			$formfield['city'] = trim($_POST['city']);
			$formfield['state'] = trim($_POST['state']);
			$formfield['zip'] = trim($_POST['zip']);
			$formfield['pass'] = trim($_POST['pass']);
			$formfield['pass2'] = trim($_POST['pass2']);
			$formfield['phone'] = trim($_POST['phone']);
			$formfield['list'] = trim($_POST['list']);	
			echo '<p>'.$formfield['list'].'</p>';
			if (empty($formfield['first'])) {$errormsg = "<p>Your first is empty</p>";}
			if (empty($formfield['last'])) {$errormsg = "<p>Your last is empty</p>";}
			if (empty($formfield['address'])) {$errormsg = "<p>Your address is empty</p>";}
			if (empty($formfield['email'])) {$errormsg = "<p>Your email is empty</p>";}
			if (empty($formfield['city'])) {$errormsg = "<p>Your city is empty</p>";}
			if (empty($formfield['state'])) {$errormsg = "<p>Your state is empty</p>";}
			if (empty($formfield['zip'])) {$errormsg = "<p>Your zip is empty</p>";}
			if(empty($formfield['pass'])){$errormsg .= "<p>Your password is empty.</p>";}
			if(empty($formfield['pass2'])){$errormsg .= "<p>Your confirm password is empty.</p>";}
			if (empty($formfield['phone'])) {$errormsg = "<p>Your phone is empty</p>";}
			if ($formfield['list'] == "") {$errormsg = "<p>Your list is empty</p>";}
			
			//CHECK FOR MATCHING PASSWORDS
			if($formfield['pass'] != $formfield['pass2'])
			{
				$errormsg .= "<p>Your passwords do not match.</p>";
			}
			
     		//VALIDATE THE EMAIL
			
			if ($errormsg != "") {
					echo "<div>THERE ARE ERRORS!!!";
					echo $errormsg;
					echo "</div>";
			}	else {
				
				$options = [
					'cost' => 12,
					'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
				];
				$encpass = password_hash($formfield['pass'], PASSWORD_BCRYPT, $options);
				
				try 
				{
					$sqlinsert = 'INSERT INTO customers (dbcustfirstname, dbcustlastname, dbcustaddress,
										 dbcustcity, dbcuststate, dbcustzip, dbcustphone,dbcustemail,
										dbcustmailinglist, dbcustpassword, dbcustemailconfirm, dbpermitid)
								VALUES (:bvfirst, :bvlast, :bvaddress, :bvcity, :bvstate, :bvzip, 
								:bvphone,:bvemail, :bvmaillist, :bvpass, 0, 12)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindValue(':bvfirst', $formfield['first']);
					$stmtinsert->bindValue(':bvlast', $formfield['last']);
					$stmtinsert->bindValue(':bvaddress', $formfield['address']);
					$stmtinsert->bindValue(':bvcity', $formfield['city']);
					$stmtinsert->bindValue(':bvstate', $formfield['state']);
					$stmtinsert->bindValue(':bvzip', $formfield['zip']);
					$stmtinsert->bindValue(':bvphone', $formfield['phone']);
					$stmtinsert->bindValue(':bvemail', $formfield['email']);
					$stmtinsert->bindValue(':bvmaillist', $formfield['list']);
					$stmtinsert->bindvalue(':bvpass', $encpass);
					$stmtinsert->execute();
					
					echo "<div>Customer information has been submitted successfully.</div>";
					
				}	
				catch(PDOException $e)
				{
					echo 'ERROR!' . $e->getMessage();
					exit();
				}	
			}	
			
		}//if isset submit


	$sqlselect = 'SELECT * from customers';
	$result = $db->prepare($sqlselect);
	$result->execute();
	
	if(in_array($_SESSION['staffloginpermit'], $allowedperms))
	{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
			<fieldset><legend>Personal Information</legend>
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
						<th><label for="email">Email:</label></th>
						<td><input type="text" name="email" id="email" value="<?php if( isset($formfield['email'])){echo $formfield['email'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="address">Address:</label></th>
						<td><input type="text" name="address" id="address" value="<?php if( isset($formfield['address'])){echo $formfield['address'];}?>" /></td>
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
								<option value="" <?php if( isset($formfield['list']) && $formfield['list'] == "" )?>>SELECT ONE</option>
								<option value="0" <?php if( isset($formfield['list']) && $formfield['list'] == "0" )?>>No</option>
								<option value="1" <?php if( isset($formfield['list']) && $formfield['list'] == "1" )?>>Yes</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Submit:</th>
						<td><input type="submit" class="button" name="submit" value="SUBMIT" /></td>
					</tr>
				</table>
			</fieldset>
		</form>
			<br><br>

	<?php
	}
	include_once 'footer.php';
	?>