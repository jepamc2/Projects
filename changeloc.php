<?php
//Developer(s): Joshua Mercer
//Date: 4/17/2018
//Purpose: This allows the admin to change location

$pagetitle = "Change Location"; //page title
require_once "header.php"; //require the header file
require_once "connect.php"; //require the connection file
	//declare variables 
	$errormsg = "";
	$showform = 1; 
	$allowedperms = array(13); //allowed permissions for this page
	
			$sqlselectloc = "SELECT * from locations";
			$resultloc = $db->prepare($sqlselectloc);
			$resultloc->execute();
			
		if( isset($_POST['thesubmit']) ) //if the user submits
		{	
			
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['fflocid'] = $_POST['locid'];

			if($formfield['fflocid'] == ""){$errormsg .= "<p>Your location choice is empty.</p>";} //error message

			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				$_SESSION['stafflocid'] = $formfield['fflocid'];
				$showform = 2; //show 2nd form
			}
		}

	if ($showform == 1 && in_array($_SESSION['staffloginpermit'], $allowedperms)) //form 1
	{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
				<table border>
					<tr>
						<th><label>Location:</label></th>
						<td><select name="locid" id="locid">
						<option value = "">Please Select a Location</option>
						<?php while ($rowloc = $resultloc->fetch() )
							{
							if ($rowloc['dblocid'] == $_SESSION['stafflocid'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowloc['dblocid'] . '" ' . $checker . '>' . $rowloc['dblocname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Set Location">
		</form>
	<?php
	}
	else if ($showform == 2 && ($_SESSION['staffloginpermit'] == 13)) { //form 2

		$sqlselectlocn = "SELECT * from locations WHERE dblocid = :bvlocid";
		$resultlocn = $db->prepare($sqlselectlocn);
		$resultlocn->bindvalue(':bvlocid', $formfield['fflocid']); //bind parameters
		$resultlocn->execute();
		$rowlocn = $resultlocn->fetch();
		
		echo '<p>Your Location is Now '.$rowlocn['dblocname'].'<p>';

		}else{
		echo "You do not have permission to update"; //let user know they don't have perms
		}
include_once 'footer.php'; //attempt to include footer file
?>