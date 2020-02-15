<?php
//Developer(s): Joshua Mercer
//Date: 4/9/2017
//Purpose: This allows the user to update staff info
$pagetitle = "Update Staff"; //page title
require_once "header.php"; //require the header file
require_once "connect.php"; //require the connection file
	//declare variables 
	$errormsg = "";
	$showform = 1; 
	$allowedperms = array(13,1); //allowed permissions for this page
	
			$formfield['ffstaffid'] = $_POST['staffid']; //set staff id
			$formfield['fflocation'] = $_SESSION['stafflocid']; //set location
			
			$sqlselect = 'SELECT * from staff where dbstaffid = :bvstaffid'; //create an SQL string to select locations
			$result = $db->prepare($sqlselect); //prepare statement
			$result->bindValue(':bvstaffid', $formfield['ffstaffid']); //bind parameters
			$result->execute(); //execute query
			$row = $result->fetch(); //fetch result set
	
			$sqlselectf = 'SELECT * from staff where dbstaffid = :bvstaffid'; //create an SQL string to select locations
			$resultf = $db->prepare($sqlselectf); //prepare statement
			$resultf->bindValue(':bvstaffid', $formfield['ffstaffid']); //bind parameters
			$resultf->execute(); //execute query
			$rowf = $resultf->fetch(); //fetch result set
			
			$sqlselectpos = "SELECT * from permit WHERE dblocid = :bvlocation";
			$resultpos = $db->prepare($sqlselectpos);
			$resultpos->bindValue(':bvlocation', $formfield['fflocation']);
			$resultpos->execute();
	
		if( isset($_POST['thesubmit']) ) //if the user submits
		{	
			$showform = 2; //show 2nd form
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffstafffname'] = trim($_POST['stafffname']);
			$formfield['ffstafflname'] = trim($_POST['stafflname']);
			$formfield['ffpermitid'] = trim($_POST['permitid']);
			$formfield['ffstaffphone'] = trim($_POST['staffphone']);
			$formfield['ffstaffemail'] = trim($_POST['staffemail']);
			$formfield['ffstaffstreet'] = trim($_POST['staffstreet']);
			$formfield['ffstaffcity'] = trim($_POST['staffcity']);
			$formfield['ffstaffstate'] = trim($_POST['staffstate']);
			$formfield['ffstaffzip'] = trim($_POST['staffzip']);
			$formfield['ffstaffemployed'] = trim($_POST['employed']);
			$formfield['ffstaffemcontact'] = trim($_POST['staffemcontact']);
			$formfield['ffstaffemphone'] = trim($_POST['staffemphone']);
			$formfield['ffstaffrate'] = $_POST['staffrate'];
			$formfield['fflocation'] = $_SESSION['stafflocid'];
			if($formfield['ffstaffrate'] == aa){
				$formfield['ffstaffrate'] = trim($_POST['pay']);
			}
			//check for empty fields
			if(empty($formfield['ffpermitid'])){$errormsg .= "<p>Please enter staff permit empty.</p>";}
			if(empty($formfield['ffstafffname'])){$errormsg .= "<p>Your customer name field is empty.</p>";}
			if(empty($formfield['ffstafflname'])){$errormsg .= "<p>Your title is empty.</p>";}
			if(empty($formfield['ffstaffphone'])){$errormsg .= "<p>Please enter phone number empty.</p>";}
			if(empty($formfield['ffstaffemail'])){$errormsg .= "<p>Your email is empty.</p>";}
			if(empty($formfield['ffstaffstreet'])){$errormsg .= "<p>Your street is empty.</p>";}
			if(empty($formfield['ffstaffcity'])){$errormsg .= "<p>Your city is empty.</p>";}
			if(empty($formfield['ffstaffstate'])){$errormsg .= "<p>Your state is empty.</p>";}
			if(empty($formfield['ffstaffzip'])){$errormsg .= "<p>Your zip is empty.</p>";}
			if(empty($formfield['ffstaffemcontact'])){$errormsg .= "<p>Your em contact is empty.</p>";}
			if(empty($formfield['ffstaffemphone'])){$errormsg .= "<p>Your em phone is empty.</p>";}
			if(empty($formfield['ffstaffrate'])){$errormsg .= "<p>Your rate is empty.</p>";}
			
			if($rowf['dbstaffemployed'] == 0 && $formfield['ffstaffemployed'] == 1){
				$firedate = '0000-00-00';
			}else if ($rowf['dbstaffemployed'] == 1 && $formfield['ffstaffemployed'] == 1){
				$firedate = '0000-00-00';
			}else if ($rowf['dbstaffemployed'] == 0 && $formfield['ffstaffemployed'] == 0){
				$firedate = $rowf['stafffiredate'];
			}else if($rowf['dbstaffemployed'] == 1 && $formfield['ffstaffemployed'] == 0){
				$firedate = $Now();
				$formfield['ffpermitid'] = 0;	
			}

			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				try
				{
					$sqlinsert = 'UPDATE staff SET dbpermitid = :bvpermitid,
								dbstafffname = :bvstafffname,dbstafflname = :bvstafflname, dbstaffphone = :bvstaffphone, dbstaffemail  = :bvstaffemail,
								dbstaffstreet = :bvstaffstreet, dbstaffcity = :bvstaffcity, dbstaffstate = :bvstaffstate, dbstaffzip = :bvstaffzip,
								dbstaffemployed  = :bvstaffemployed, dbstafffiredate = :bvstafffiredate, 
								dbstaffemcontact = :bvstaffemcontact, dbstaffemphone = :bvstaffemphone, dbstaffrate = :bvstaffrate, dblocid = :bvlocation
								WHERE dbstaffid = :bvstaffid';
					$stmtinsert = $db->prepare($sqlinsert); //prepare statement
					$stmtinsert->bindvalue(':bvpermitid', $formfield['ffpermitid']);
					$stmtinsert->bindvalue(':bvstafffname', $formfield['ffstafffname']);
					$stmtinsert->bindvalue(':bvstafflname', $formfield['ffstafflname']);
					$stmtinsert->bindvalue(':bvstaffphone', $formfield['ffstaffphone']);
					$stmtinsert->bindvalue(':bvstaffemail', $formfield['ffstaffemail']);
					$stmtinsert->bindvalue(':bvstaffstreet', $formfield['ffstaffstreet']);
					$stmtinsert->bindvalue(':bvstaffcity', $formfield['ffstaffcity']);
					$stmtinsert->bindvalue(':bvstaffstate', $formfield['ffstaffstate']);
					$stmtinsert->bindvalue(':bvstaffzip', $formfield['ffstaffzip']);
					$stmtinsert->bindvalue(':bvstaffemployed', $formfield['ffstaffemployed']);
					$stmtinsert->bindvalue(':bvstafffiredate', $firedate);
					$stmtinsert->bindvalue(':bvstaffemcontact', $formfield['ffstaffemcontact']);
					$stmtinsert->bindvalue(':bvstaffemphone', $formfield['ffstaffemphone']);
					$stmtinsert->bindvalue(':bvstaffrate', $formfield['ffstaffrate']);
					$stmtinsert->bindValue(':bvlocation', $formfield['fflocation']);
					$stmtinsert->bindValue(':bvstaffid', $formfield['ffstaffid']);
					$stmtinsert->execute(); //execute query
					echo "<p>There are no errors.  Thank you.</p>";
				}
				catch(PDOException $e) //if exception
				{
					echo $e->getMessage(); //print exception
					exit(); //exit
				}
			}
		}

	if ($showform == 1 && in_array($_SESSION['staffloginpermit'], $allowedperms)){ //if they have perms
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Staff Information</legend>
				<table border>
					<tr>
						<th>First Name</th>
						<td><input type="text" name="stafffname" id="stafffname"
						value = "<?php echo $row['dbstafffname']; ?>"></td>
					</tr>
					<tr>
						<th>Last Name</th>
						<td><input type="text" name="stafflname" id="stafflname"
						value = "<?php echo $row['dbstafflname']; ?>"></td>
					</tr>
					<tr>
						<th><label>Job Title:</label></th>
						<td><select name="permitid" id="permitid">
						<option value = "">Please Select a  Position</option>
						<?php while ($rowpos = $resultpos->fetch() )
							{
							if ($rowpos['dbpermitid'] == $row['dbpermitid'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowpos['dbpermitid'] . '" ' . $checker . '>' . $rowpos['dbpermitposition'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Phone</th>
						<td><input type="text" name="staffphone" id="staffphone"
						value = "<?php echo $row['dbstaffphone']; ?>"></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><input type="text" name="staffemail" id="staffemail"
						value = "<?php echo $row['dbstaffemail']; ?>"></td>
					</tr>
					<tr>
						<th>Address</th>
						<td><input type="text" name="staffstreet" id="staffstreet"
						value = "<?php echo $row['dbstaffstreet']; ?>"></td>
					</tr>
					<tr>
						<th>City</th>
						<td><input type="text" name="staffcity" id="staffcity"
						value = "<?php echo $row['dbstaffcity']; ?>"></td>
					</tr>
					<tr>
						<th>State</th>
						<td><input type="text" name="staffstate" id="staffstate"
						value = "<?php echo $row['dbstaffstate']; ?>"></td>
					</tr>
					<tr>
						<th>Zip</th>
						<td><input type="text" name="staffzip" id="staffzip"
						value = "<?php echo $row['dbstaffzip']; ?>"></td>
					</tr>
					<tr>
					<th><label for="employed">Employed Status:</label></th>
						<td><select name="employed" id="employed">
								<option value="0" <?php if($row['dbstaffemployed'] == "0" ){echo ' selected';}?>>No</option>
								<option value="1" <?php if($row['dbstaffemployed'] == "1" ){echo ' selected';}?>>Yes</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Emergency Contact</th>
						<td><input type="text" name="staffemcontact" id="staffemcontact"
						value = "<?php echo $row['dbstaffemcontact']; ?>"></td>
					</tr>
					<tr>
						<th>Emergency Phone</th>
						<td><input type="text" name="staffemphone" id="staffemphone"
						value = "<?php echo $row['dbstaffemphone']; ?>"></td>
					</tr>
					<tr>
						<th>Pay:</th>
						<td><input type="radio" name="staffrate" id="12.50" 
									value="12.50" <?php if( $row['dbstaffrate'] == "12.50" ){echo ' checked';}?> /><label for="12.50">$12.50</label>
							<input type="radio" name="staffrate" id="14.50" 
									value="14.50" <?php if( $row['dbstaffrate'] == "14.50" ){echo ' checked';}?>/><label for="14.50">$14.50</label>
							<input type="radio" name="staffrate" id="15.50" 
									value="15.50" <?php if( $row['dbstaffrate'] == "15.50" ){echo ' checked';}?>/><label for="15.50">$15.50</label>
							<input type="radio" name="staffrate" id="19.75" 
									value="19.75" <?php if( $row['dbstaffrate'] == "19.75" ){echo ' checked';}?>/><label for="19.75">$19.75</label>
							<input type="radio" name="staffrate"id="other"
							value="aa"<?php if( $row['dbstaffrate'] != "12.50" || $row['dbstaffrate'] != "14.50" || $row['dbstaffrate'] != "15.50" ||$row['dbstaffrate'] != "19.75"){echo ' checked';}?>/>Other <input type="text" name="pay" id = "pay" value="<?php echo $row['dbstaffrate']?>"/>​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​		
							
						</td>
					</tr>

				</table>
				<input type="hidden" name = "staffid" value="<?php echo $formfield['ffstaffid'] ?>">
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2 && in_array($_SESSION['staffloginpermit'], $allowedperms)) { //form 2
	?>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Staff Information</legend>
				<table border>
					<tr>
						<th>First Name</th>
						<td><input type="text" name="stafffname" id="stafffname"
						value = "<?php echo $formfield['ffstafffname']; ?>"></td>
					</tr>
					<tr>
						<th>Last Name</th>
						<td><input type="text" name="stafflname" id="stafflname"
						value = "<?php echo $formfield['ffstafflname']; ?>"></td>
					</tr>
					<tr>
						<th><label>Job Title:</label></th>
						<td><select name="permitid" id="permitid">
						<option value = "">Please Select a  Position</option>
						<?php while ($rowpos = $resultpos->fetch() )
							{
							if ($rowpos['dbpermitid'] == $formfield['ffpermitid'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowpos['dbpermitid'] . '" ' . $checker . '>' . $rowpos['dbpermitposition'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Phone</th>
						<td><input type="text" name="staffphone" id="staffphone"
						value = "<?php echo $formfield['ffstaffphone']; ?>"></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><input type="text" name="staffemail" id="staffemail"
						value = "<?php echo $formfield['ffstaffemail']; ?>"></td>
					</tr>
					<tr>
						<th>Address</th>
						<td><input type="text" name="staffstreet" id="staffstreet"
						value = "<?php echo $formfield['ffstaffstreet']; ?>"></td>
					</tr>
					<tr>
						<th>City</th>
						<td><input type="text" name="staffcity" id="staffcity"
						value = "<?php echo $formfield['ffstaffcity']; ?>"></td>
					</tr>
					<tr>
						<th>State</th>
						<td><input type="text" name="staffstate" id="staffstate"
						value = "<?php echo $formfield['ffstaffstate']; ?>"></td>
					</tr>
					<tr>
						<th>Zip</th>
						<td><input type="text" name="staffzip" id="staffzip"
						value = "<?php echo $formfield['ffstaffzip']; ?>"></td>
					</tr>
					<tr>
					<th><label for="employed">Employed Status:</label></th>
						<td><select name="employed" id="employed">
								<option value="0" <?php if($formfield['ffstaffemployed'] == "0" ){echo ' selected';}?>>No</option>
								<option value="1" <?php if($formfield['ffstaffemployed'] == "1" ){echo ' selected';}?>>Yes</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Emergency Contact</th>
						<td><input type="text" name="staffemcontact" id="staffemcontact"
						value = "<?php echo $formfield['ffstaffemcontact']; ?>"></td>
					</tr>
					<tr>
						<th>Emergency Phone</th>
						<td><input type="text" name="staffemphone" id="staffemphone"
						value = "<?php echo $formfield['ffstaffemphone']; ?>"></td>
					</tr>
					<tr>
						<th>Pay:</th>
						<td><input type="radio" name="staffrate" id="12.50" 
									value="12.50" <?php if(isset($_POST['staffrate']) && $formfield['ffstaffrate'] == "12.50" ){echo ' checked';}?> /><label for="12.50">$12.50</label>
							<input type="radio" name="staffrate" id="14.50" 
									value="14.50" <?php if(isset($_POST['staffrate']) && $formfield['ffstaffrate'] == "14.50" ){echo ' checked';}?>/><label for="14.50">$14.50</label>
							<input type="radio" name="staffrate" id="15.50" 
									value="15.50" <?php if(isset($_POST['staffrate']) && $formfield['ffstaffrate'] == "15.50" ){echo ' checked';}?>/><label for="15.50">$15.50</label>
							<input type="radio" name="staffrate" id="19.75" 
									value="19.75" <?php if(isset($_POST['staffrate']) && $formfield['ffstaffrate'] =="19.75" ){echo ' checked';}?>/><label for="19.75">$19.75</label>
							<input type="radio" name="staffrate"id="other"
							value="aa"<?php if( isset($_POST['other'])){echo ' checked';}?>/>Other <input type="text" name="pay" id = "pay" value="<?php echo $formfield['ffstaffrate']?>" />​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​		
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
	<br><br>
	<table border>
	<tr>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Job Title</th>
		<th>Phone Number</th>
		<th>Email</th>
		<th>Address</th>
		<th>City</th>
		<th>State</th>
		<th>Zip</th>
		<th>Emergency Contact</th>
		<th>Emergency Phone</th>
		<th>Pay Rate</th>
	</tr>
	<?php 
	
			$sqlselectt = 'SELECT * from staff where dbstaffid = :bvstaffid'; //create an SQL string to select staff
			$resultt = $db->prepare($sqlselectt); //prepare statement
			$resultt->bindValue(':bvstaffid', $formfield['ffstaffid']); //bind parameters
			$resultt->execute(); //execute query
			
		while ( $rowt = $resultt->fetch() )
			{
				$sqlselectposs = "SELECT * from permit WHERE dblocid = :bvlocation AND dbpermitid = :bvpermitid";
				$resultposs = $db->prepare($sqlselectposs);
				$resultposs->bindValue(':bvlocation', $formfield['fflocation']);
				$resultposs->bindValue(':bvpermitid', $rowt['dbpermitid']);
				$resultposs->execute();
				$rowposs = $resultposs->fetch();
				
				echo '<tr><td>' . $rowt['dbstafffname'] . '</td><td> ' . $rowt['dbstafflname'] . 
				'</td><td> ' . $rowposs['dbpermitid'] . 
				'</td><td> ' . $rowt['dbstaffphone'] .  '</td><td> ' . $rowt['dbstaffemail'] .
				'</td><td> ' . $rowt['dbstaffstreet'] . '</td><td>' . $rowt['dbstaffcity'] . '</td><td>' . $rowt['dbstaffstate'] . '</td><td>' . $rowt['dbstaffzip'] . '</td><td>' . $rowt['dbstaffemcontact'] . '</td><td>' . $rowt['dbstaffemphone'] . '</td><td>' . $rowt['dbstaffrate'] . '</td></tr>';
			}
	?>
	</table>
	<?php
		}
		else {
		echo "You do not have permission to update"; //let user know they don't have perms
		}
include_once 'footer.php'; //attempt to include footer file
?>