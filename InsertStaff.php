<?php
//Developer(s): Maggie Hussey
//Date: 3/13/2018
//Purpose: to allow the user to enter new staff info
//Edited by: Joshua Mercer
$pagetitle = 'Insert Staff';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
	
$formfield['fflocation'] = $_SESSION['stafflocid'];
	
$sqlselectpos = "SELECT * from permit WHERE dblocid = :bvlocation";
$resultpos = $db->prepare($sqlselectpos);
$resultpos->bindValue(':bvlocation', $formfield['fflocation']);
$resultpos->execute();

		if( isset($_POST['thesubmit']) )
		{
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
			$formfield['ffstaffDOB'] = trim($_POST['staffDOB']);
			$formfield['ffstaffhiredate'] = trim($_POST['staffhiredate']);
			$formfield['ffstaffemcontact'] = trim($_POST['staffemcontact']);
			$formfield['ffstaffemphone'] = trim($_POST['staffemphone']);
			$formfield['ffstaffrate'] = $_POST['staffrate'];
			$formfield['ffstaffpassword'] = trim($_POST['staffpassword']);
			$formfield['ffstaffpassword2'] = trim($_POST['staffpassword2']);
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffstafffname'])){$errormsg .= "<p>Your customer name field is empty.</p>";}
			if(empty($formfield['ffstafflname'])){$errormsg .= "<p>Your title is empty.</p>";}
			if(empty($formfield['ffstaffphone'])){$errormsg .= "<p>Please enter phone number empty.</p>";}
			if(empty($formfield['ffpermitid'])){$errormsg .= "<p>Please enter staff permit empty.</p>";}
			if(empty($formfield['ffstaffemail'])){$errormsg .= "<p>Your email is empty.</p>";}
			if(empty($formfield['ffstaffstreet'])){$errormsg .= "<p>Your street is empty.</p>";}
			if(empty($formfield['ffstaffcity'])){$errormsg .= "<p>Your city is empty.</p>";}
			if(empty($formfield['ffstaffstate'])){$errormsg .= "<p>Your state is empty.</p>";}
			if(empty($formfield['ffstaffzip'])){$errormsg .= "<p>Your zip is empty.</p>";}
			if(empty($formfield['ffstaffDOB'])){$errormsg .= "<p>Your DOB is empty.</p>";}
			if(empty($formfield['ffstaffhiredate'])){$errormsg .= "<p>Your hiredate empty.</p>";}
			if(empty($formfield['ffstaffemcontact'])){$errormsg .= "<p>Your em contact is empty.</p>";}
			if(empty($formfield['ffstaffemphone'])){$errormsg .= "<p>Your em phone is empty.</p>";}
			if(empty($formfield['ffstaffrate'])){$errormsg .= "<p>Your rate is empty.</p>";}
			if(empty($formfield['ffstaffpassword'])){$errormsg .= "<p>Your password is empty.</p>";}
			if(empty($formfield['ffstaffpassword2'])){$errormsg .= "<p>Your confirm password is empty.</p>";}
			
			//CHECK FOR MATCHING PASSWORDS
			if($formfield['ffstaffpassword'] != $formfield['ffstaffpassword2'])
			{
				$errormsg .= "<p>Your passwords do not match.</p>";
			}
			$sqlemailcheck ='SELECT * from staff WHERE dbstaffemail = :bvemailcheck'; //SQL query
					$stmtemailcheck = $db->prepare($sqlemailcheck); //preparing statement
					$stmtemailcheck->bindValue(':bvemailcheck', $formfield['ffstaffemail']);
					$stmtemailcheck->execute(); //executing prepared statements
					$count = $stmtemailcheck->rowCount();
					
					if ($count > 0){ //counting rows in database where email exists
					$errormsg .= "<p>Email already exist!</p>";
					}
			/*  ****************************************************************************
			DISPLAY ERRORS
			If we have concatenated the error message with details, then let the user know
			**************************************************************************** */
			if($errormsg != "")
			{
				echo "<div class='error'><p>THERE ARE ERRORS!</p>";
				echo $errormsg;
				echo "</div>";
			}
			else
			{

				$options = [
					'cost' => 12,
				];
				
				$encpass = password_hash($formfield['ffstaffpassword'], PASSWORD_BCRYPT, $options);
				
				$basestaffusername = strtolower($formfield['ffstafffname'].$formfield['ffstafflname']);
				
				 
					$sqluncount = "SELECT * from staff
							where dbstaffusername LIKE CONCAT('%', :bvstaffusername, '%')";
					$countunresult = $db-> prepare($sqluncount);
					$countunresult->bindValue(':bvstaffusername',$basestaffusername);
					$countunresult->execute();
					$rowuncount = $countunresult-> rowcount();
					
					if ($rowuncount > 0) {
						$finalstaffusername = $basestaffusername.$rowuncount;
						
					} else {
						$finalstaffusername = $basestaffusername;
					}
					
					
				

				
				try
				{
					//enter data into database
					$sqlinsert = 'INSERT INTO staff (dbpermitid,dbstaffusername,dbstaffpassword,dbstafffname,dbstafflname,dbstaffphone, 
						dbstaffemail,dbstaffstreet, dbstaffcity, dbstaffstate,dbstaffzip,dbstaffDOB,dbstaffhiredate,
						dbstaffemployed,dbstafffiredate, dbstaffemcontact,dbstaffemphone,dbstaffrate, dblocid)
					VALUES(:bvpermitid,:bvstaffusername,:bvstaffpassword,:bvstafffname,:bvstafflname,:bvstaffphone,
						:bvstaffemail,:bvstaffstreet,:bvstaffcity, :bvstaffstate,:bvstaffzip,:bvstaffDOB,:bvstaffhiredate,
						1,0,:bvstaffemcontact,:bvstaffemphone,:bvstaffrate, :bvlocation)';		
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvstafffname', $formfield['ffstafffname']);
					$stmtinsert->bindvalue(':bvstafflname', $formfield['ffstafflname']);
					$stmtinsert->bindvalue(':bvstaffusername', $finalstaffusername);
					$stmtinsert->bindvalue(':bvpermitid', $formfield['ffpermitid']);
					$stmtinsert->bindvalue(':bvstaffphone', $formfield['ffstaffphone']);
					$stmtinsert->bindvalue(':bvstaffemail', $formfield['ffstaffemail']);
					$stmtinsert->bindvalue(':bvstaffstreet', $formfield['ffstaffstreet']);
					$stmtinsert->bindvalue(':bvstaffcity', $formfield['ffstaffcity']);
					$stmtinsert->bindvalue(':bvstaffstate', $formfield['ffstaffstate']);
					$stmtinsert->bindvalue(':bvstaffzip', $formfield['ffstaffzip']);
					$stmtinsert->bindvalue(':bvstaffDOB', $formfield['ffstaffDOB']);
					$stmtinsert->bindvalue(':bvstaffhiredate', $formfield['ffstaffhiredate']);
					$stmtinsert->bindvalue(':bvstaffemcontact', $formfield['ffstaffemcontact']);
					$stmtinsert->bindvalue(':bvstaffemphone', $formfield['ffstaffemphone']);
					$stmtinsert->bindvalue(':bvstaffrate', $formfield['ffstaffrate']);
					$stmtinsert->bindvalue(':bvstaffpassword', $encpass);
					$stmtinsert->bindValue(':bvlocation', $formfield['fflocation']);
					$stmtinsert->execute();
					echo "<div class='success'><p>There are no errors.  Thank you.</p></div>";
				}//try
				catch(PDOException $e)
				{
					echo 'ERROR!!!' .$e->getMessage();
					exit();
				}
			}//else statement end
		}//if isset submit


	$sqlselect = 'SELECT * from staff WHERE dblocid = :bvlocation';
	$result = $db->prepare($sqlselect);
	$result->bindValue(':bvlocation', $formfield['fflocation']);
	$result->execute();

if($_SESSION['staffloginpermit'] == 1)
	{
/**
 * States Dropdown 
 *
 * @uses check_select
 * @param string $post, the one to make "selected"
 * @param string $type, by default it shows abbreviations. 'abbrev', 'name' or 'mixed'
 * @return string
 */
function StateDropdown($post=null, $type='abbrev') {
	$states = array(
		array('AK', 'Alaska'),
		array('AL', 'Alabama'),
		array('AR', 'Arkansas'),
		array('AZ', 'Arizona'),
		array('CA', 'California'),
		array('CO', 'Colorado'),
		array('CT', 'Connecticut'),
		array('DC', 'District of Columbia'),
		array('DE', 'Delaware'),
		array('FL', 'Florida'),
		array('GA', 'Georgia'),
		array('HI', 'Hawaii'),
		array('IA', 'Iowa'),
		array('ID', 'Idaho'),
		array('IL', 'Illinois'),
		array('IN', 'Indiana'),
		array('KS', 'Kansas'),
		array('KY', 'Kentucky'),
		array('LA', 'Louisiana'),
		array('MA', 'Massachusetts'),
		array('MD', 'Maryland'),
		array('ME', 'Maine'),
		array('MI', 'Michigan'),
		array('MN', 'Minnesota'),
		array('MO', 'Missouri'),
		array('MS', 'Mississippi'),
		array('MT', 'Montana'),
		array('NC', 'North Carolina'),
		array('ND', 'North Dakota'),
		array('NE', 'Nebraska'),
		array('NH', 'New Hampshire'),
		array('NJ', 'New Jersey'),
		array('NM', 'New Mexico'),
		array('NV', 'Nevada'),
		array('NY', 'New York'),
		array('OH', 'Ohio'),
		array('OK', 'Oklahoma'),
		array('OR', 'Oregon'),
		array('PA', 'Pennsylvania'),
		array('PR', 'Puerto Rico'),
		array('RI', 'Rhode Island'),
		array('SC', 'South Carolina'),
		array('SD', 'South Dakota'),
		array('TN', 'Tennessee'),
		array('TX', 'Texas'),
		array('UT', 'Utah'),
		array('VA', 'Virginia'),
		array('VT', 'Vermont'),
		array('WA', 'Washington'),
		array('WI', 'Wisconsin'),
		array('WV', 'West Virginia'),
		array('WY', 'Wyoming')
	);
	
	$options = '<option value=""></option>';
	
	foreach ($states as $state) {
		if ($type == 'abbrev') {
    	$options .= '<option value="'.$state[0].'" '. check_select($post, $state[0], false) .' >'.$state[0].'</option>'."\n";
    } elseif($type == 'name') {
    	$options .= '<option value="'.$state[1].'" '. check_select($post, $state[1], false) .' >'.$state[1].'</option>'."\n";
    } elseif($type == 'mixed') {
    	$options .= '<option value="'.$state[0].'" '. check_select($post, $state[0], false) .' >'.$state[1].'</option>'."\n";
    }
	}
		
	echo $options;
}

/**
 * Check Select Element 
 *
 * @param string $i, POST value
 * @param string $m, input element's value
 * @param string $e, return=false, echo=true 
 * @return string 
 */
function check_select($i,$m,$e=true) {
	if ($i != null) { 
		if ( $i == $m ) { 
			$var = ' selected="selected" '; 
		} else {
			$var = '';
		}
	} else {
		$var = '';	
	}
	if(!$e) {
		return $var;
	} else {
		echo $var;
	}
}
		
	?>
	


	
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Staff Information</legend>
				<table border>
					<tr>
						<th>First Name</th>
						<td><input type="text" name="stafffname" id="stafffname"
						value = <?php echo $formfield['ffstafffname']; ?>	></td>
					</tr>
					<tr>
						<th>Last Name</th>
						<td><input type="text" name="stafflname" id="stafflname"
						value = <?php echo $formfield['ffstafflname']; ?>	></td>
					</tr>
					<tr>
						<th><label>Job Title:</label></th>
						<td><select name="permitid" id="permitid">
						<option value = "">Please Select a Position</option>
						<?php while ($rowpos = $resultpos->fetch() )
							{
							if ($rowpos['dbpermitid'] == $formfield['ffpermitposition'])
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
						value = <?php echo $formfield['ffstaffphone']; ?>></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><input type="text" name="staffemail" id="staffemail"
						value = <?php echo $formfield['ffstaffemail']; ?>></td>
					</tr>
					<tr>
						<th>Address</th>
						<td><input type="text" name="staffstreet" id="staffstreet"
						value = <?php echo $formfield['ffstaffstreet']; ?>></td>
					</tr>
					<tr>
						<th>City</th>
						<td><input type="text" name="staffcity" id="staffcity"
						value = <?php echo $formfield['ffstaffcity']; ?>></td>
					</tr>
					<tr>
						<th>State</th>
						<td><select name="staffstate"><?php echo StateDropdown(null, 'abbrev'); ?>
						</td>
					</tr>
					<tr>
						<th>Zip</th>
						<td><input type="text" name="staffzip" id="staffzip"
						value = <?php echo $formfield['ffstaffzip']; ?>></td>
					</tr>
					<tr>
						<th>Birth Date</th>
						<td><input type="date" name="staffDOB" id="staffDOB"
						value = <?php echo $formfield['ffstaffDOB']; ?>></td>
					</tr>
					<tr>
						<th>Hire Date</th>
						<td><input type="date" name="staffhiredate" id="staffhiredate"
						value = <?php echo $formfield['ffstaffhiredate']; ?>></td>
					</tr>
					<tr>
						<th>Emergency Contact</th>
						<td><input type="text" name="staffemcontact" id="staffemcontact"
						value = <?php echo $formfield['ffstaffemcontact']; ?>></td>
					</tr>
					<tr>
						<th>Emergency Phone</th>
						<td><input type="text" name="staffemphone" id="staffemphone"
						value = <?php echo $formfield['ffstaffemphone']; ?>></td>
					</tr>
					<tr>
						<th>Pay:</th>
						<td><input type="radio" name="staffrate" id="12.50" 
									value="12.50" <?php if( isset($_POST['staffrate']) && $formfield['ffstaffrate'] == "12.50" ){echo ' checked';}?> />
							<label for="12.50">$12.50</label>
							<input type="radio" name="staffrate" id="14.50" 
									value="14.50" <?php if( isset($_POST['staffrate']) && $formfield['ffstaffrate'] == "14.50" ){echo ' checked';}?>/><label for="14.50">$14.50</label>
							<input type="radio" name="staffrate" id="15.50" 
									value="15.50" <?php if( isset($_POST['staffrate']) && $formfield['ffstaffrate'] == "15.50" ){echo ' checked';}?>/><label for="15.50">$15.50</label>
							<input type="radio" name="staffrate" id="19.75" 
									value="19.75" <?php if( isset($_POST['staffrate']) && $formfield['ffstaffrate'] == "19.75" ){echo ' checked';}?>/><label for="19.75">$19.75</label>
							<input type="radio" name="staffrate"id="other" value=""<?php if( isset($_POST['other']) && $formfield['ffstaffrate'] == "" ){echo ' checked';}?>/>Other <input type="text" name="other" id = "other" />​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​
						</td>
					</tr>
					<tr>
						<th>Password</th>
						<td><input type="password" name="staffpassword" id="staffpassword"></td>
					</tr>
					<tr>
						<th>Confirm Password</th>
						<td><input type="password" name="staffpassword2" id="staffpassword2"></td>
					</tr>
					</tr>
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
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
		<th>Birth Date</th>
		<th>Hire Date</th>
		<th>Emergency Contact</th>
		<th>Emergency Phone</th>
		<th>Pay Rate</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbstafffname'] . '</td><td> ' . $row['dbstafflname'] . 
				'</td><td> ' . $row['dbpermitid'] . 
				'</td><td> ' . $row['dbstaffphone'] .  '</td><td> ' . $row['dbstaffemail'] .
				'</td><td> ' . $row['dbstaffstreet'] . '</td><td>' . $row['dbstaffcity'] . '</td><td>' . $row['dbstaffstate'] . '</td><td>' . $row['dbstaffzip'] . '</td><td>' . $row['dbstaffDOB'] . '</td><td>' . $row['dbstaffhiredate'] . '</td><td>' . $row['dbstaffemcontact'] . '</td><td>' . $row['dbstaffemphone'] . '</td><td>' . $row['dbstaffrate'] . '</td></tr>';
			}
	?>
	</table>
<?php
	}	
include_once 'footer.php'
?>