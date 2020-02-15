<?php
//Developer(s): Joshua Mercer
//Date: 4/18/2018
//Purpose: This is to allow the user to change their info
require_once "header.php";
require_once "connect.php";

$errormsg = "";
$showform = 1;

		$formfield['ffcustid'] = $_SESSION['custloginid'];
		
		$sqlselect = "SELECT * FROM customers WHERE dbcustid = :bvcustid";
		$result = $db->prepare($sqlselect);
		$result->bindValue(':bvcustid', $formfield['ffcustid']);
		$result->execute();
		$row = $result->fetch(); 
		
	echo '<div class="bigpaper">';
	echo '<center>';
	echo '<br><br><br><br>';
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			$formfield['fffirst'] = trim($_POST['first']);
			$formfield['fflast'] = trim($_POST['last']);
			$formfield['ffaddress'] = trim($_POST['address']);
			$formfield['ffemail'] = trim(strtolower($_POST['email']));
			$formfield['ffcity'] = trim($_POST['city']);
			$formfield['ffstate'] = trim($_POST['state']);
			$formfield['ffzip'] = trim($_POST['zip']);
			$formfield['ffphone'] = trim($_POST['phone']);
			$formfield['fflist'] = trim($_POST['list']);	
			
			if (empty($formfield['fffirst'])) {$errormsg = "<p>Your first is empty</p>";}
			if (empty($formfield['fflast'])) {$errormsg = "<p>Your last is empty</p>";}
			if (empty($formfield['ffaddress'])) {$errormsg = "<p>Your address is empty</p>";}
			if (empty($formfield['ffemail'])) {$errormsg = "<p>Your email is empty</p>";}
			if (empty($formfield['ffcity'])) {$errormsg = "<p>Your city is empty</p>";}
			if (empty($formfield['ffstate'])) {$errormsg = "<p>Your state is empty</p>";}
			if (empty($formfield['ffzip'])) {$errormsg = "<p>Your zip is empty</p>";}
			if (empty($formfield['ffphone'])) {$errormsg = "<p>Your phone is empty</p>";}
			
			if($errormsg != "")
			{
				echo "<div class='error'><p>THERE ARE ERRORS!</p>";
				echo $errormsg;
				echo "</div>";
			}
			else
			{
				try
				{
					//enter data into database
					$sqlinsert = 'UPDATE customers 
								  SET dbcustfirstname = :bvfirst,
								  dbcustlastname = :bvlast,
								  dbcustaddress = :bvaddr,
								  dbcustcity = :bvcity,
								  dbcuststate = :bvstate,
								  dbcustzip = :bvzip,
								  dbcustphone = :bvphone,
								  dbcustemail = :bvemail,
								  dbcustmailinglist = :bvml
								  WHERE dbcustid = :bvcustid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvfirst', $formfield['fffirst']);
					$stmtinsert->bindvalue(':bvlast', $formfield['fflast']);
					$stmtinsert->bindvalue(':bvaddr', $formfield['ffaddress']);
					$stmtinsert->bindvalue(':bvcity', $formfield['ffcity']);
					$stmtinsert->bindvalue(':bvstate', $formfield['ffstate']);
					$stmtinsert->bindvalue(':bvzip', $formfield['ffzip']);
					$stmtinsert->bindvalue(':bvphone', $formfield['ffphone']);
					$stmtinsert->bindvalue(':bvemail', $formfield['ffemail']);
					$stmtinsert->bindvalue(':bvml', $formfield['fflist']);
					$stmtinsert->bindvalue(':bvcustid', $formfield['ffcustid']);
					$stmtinsert->execute();
					echo "<div class='success'><h5>There are no errors.  Thank you.</h5></div>";
					echo "<br><br>";
				}//try
				catch(PDOException $e)
				{
					echo 'ERROR!!!' .$e->getMessage();
					exit();
				}
			}//else statement end
		}//if isset submit
	if ($_SESSION['custloginpermit'] == 12){

	if ($showform == 1)
	{
	?>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
				<table border>
					<tr>
						<th><label for="first">First Name:</label></th>
						<td><input type="text" name="first" id="first" size="10" value="<?php echo $row['dbcustfirstname'];?>"/></td>
					</tr>
					<tr>
						<th><label for="last">Last Name:</label></th>
						<td><input type="text" name="last" id="last" size="10" value="<?php echo $row['dbcustlastname'];?>"/></td>
					</tr>
					<tr>
						<th><label for="email">Email:</label></th>
						<td><input type="text" name="email" id="email" value="<?php echo $row['dbcustemail'];?>" /></td>
					</tr>
					<tr>
						<th><label for="address">Address:</label></th>
						<td><input type="text" name="address" id="address" value="<?php echo $row['dbcustaddress'];?>" /></td>
					</tr>
					<tr>
						<th><label for="city">City:</label></th>
						<td><input type="text" name="city" id="city" value="<?php echo $row['dbcustcity'];?>" /></td>
					</tr>
					<tr>
						<th><label for="state">State:</label></th>
						<td><input type="text" name="state" id="state" value="<?php echo $row['dbcuststate'];?>" /></td>
					</tr>
					<tr>
						<th><label for="zip">Zip:</label></th>
						<td><input type="text" name="zip" id="zip" value="<?php echo $row['dbcustzip'];?>" /></td>
					</tr>
					<tr>
						<th><label for="phone">Phone:</label></th>
						<td><input type="text" name="phone" id="phone" value="<?php echo $row['dbcustphone'];?>" /></td>
					</tr>
					<tr>
						<th><label for="list">Mail List:</label></th>
						<td><select name="list" id="list">
								<option value="0" <?php if( $row['dbcustmailinglist'] == "0" ){echo ' selected';}?>>No</option>
								<option value="1" <?php if( $row['dbcustmailinglist'] == "1" ){echo ' selected';}?>>Yes</option>
							</select>
						</td>
					</tr>
				</table>
				<br><br>
				<input type="hidden" name = "custid" value="<?php echo $row['dbcustid'] ?>">
				<input type="submit" class="button" name = "thesubmit" value="Update Information">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2) {
	?>
		<table border>
			<tr>
				<th>First Name: </th>
				<td><?php echo $formfield['fffirst']; ?></td>
			</tr>
			
			<tr>
				<th>Last Name: </th>
				<td><?php echo $formfield['fflast']; ?></td>
			</tr>
			
						<tr>
				<th>Email: </th>
				<td><?php echo $formfield['ffemail']; ?></td>
			</tr>
			
			<tr>
				<th>Address: </th>
				<td><?php echo $formfield['ffaddress']; ?></td>
			</tr>
			<tr>
				<th>City: </th>
				<td><?php echo $formfield['ffcity']; ?></td>
			</tr>
			<tr>
				<th>State: </th>
				<td><?php echo $formfield['ffstate']; ?></td>
			</tr>
			<tr>
				<th>Zip: </th>
				<td><?php echo $formfield['ffzip']; ?></td>
			</tr>
			<tr>
				<th>Phone: </th>
				<td><?php echo $formfield['ffphone']; ?></td>
			</tr>
			
			<tr>
				<th>Mailing List: </th>
				<td><?php 
				if ($formfield['ffmaillist'] == 1){
					echo "Yes"; 
				}else{
					echo "No"; 
				}
				?></td>
			</tr>
		</table>
	

	<?php
		}
		else {
		echo "You do not have permission to update";
		}
		echo '<br><br><br><br>';
		echo '</center>';
		echo '</div>';
	?>	
	
<?php
}
include_once 'footer.php';
?>