<?php
//Developer(s): Joshua Mercer
//Date: 3/27/2017
//Purpose: This allows the user to search locations
$pagetitle = "Select Staff"; //page title
require_once 'header.php'; //require the header file
require_once 'connect.php'; //require the connection file
$errormsg = ""; //declare error message
$stringclause = "";
$allowedperms = array(13,1); //allowed permissions for this page

	$formfield['fflocation'] = $_SESSION['stafflocid'];

	if( isset($_POST['thesubmit']) ) //when the submit button is presses
		{
			$formfield['ffstaffname'] = trim($_POST['staffname']);//cleanse data
			
			if ($_POST['staffid'] != '') {
				$formfield['ffstaffid'] = trim($_POST['staffid']);//cleanse data
				$stringclause =  "AND dbstaffid = :bvstaffid";
			}
			
			//create sql string to select where staff is like entered data
			$sqlselect = "SELECT * FROM staff WHERE dbstaffusername like CONCAT('%', :bvstaffname, '%') AND dblocid = :bvlocation". $stringclause;
			$result = $db->prepare($sqlselect); //prepare statement
			$result->bindValue(':bvlocation', $formfield['fflocation']); //bind parameters
			$result->bindValue(':bvstaffname', $formfield['ffstaffname']); 
			if ($formfield['ffstaffid'] != '') {
				$result->bindValue(':bvstaffid', $formfield['ffstaffid']);
			}
			$result->execute(); //execute query
		}
	else //by default
		{
			$sqlselect = "SELECT * FROM staff WHERE dblocid = :bvlocation"; //generic select staff sql string
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvlocation', $formfield['fflocation']); //bind parameters
			$result->execute();
		}
	if (in_array($_SESSION['staffloginpermit'], $allowedperms)){ // if they meet permission requirements
	//below is the form search 
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Staff Info</legend>
				<table border>
					<tr>
						<th>Staff ID: </th>
						<td><input type="text" name="staffid" id="staffid"
						value = "<?php echo $formfield['ffstaffid']; ?>"></td>
					</tr>
					<tr>
						<th>Staff Username: </th>
						<td><input type="text" name="staffname" id="staffname"
						value = "<?php echo $formfield['ffstaffname']; ?>"></td>
					</tr>
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Job Title</th>
		<th>Email</th>
		<th>Emergency Contact</th>
		<th>Emergency Phone</th>
		<th>Pay Rate</th>
		<th>Edit</th>
	</tr>
	<?php 
			
		while ( $row = $result->fetch() )
			{
				$sqlselectpos = "SELECT * from permit WHERE dblocid = :bvlocation AND dbpermitid = :bvpermitid";
				$resultpos = $db->prepare($sqlselectpos);
				$resultpos->bindValue(':bvlocation', $formfield['fflocation']);
				$resultpos->bindValue(':bvpermitid', $row['dbpermitid']);
				$resultpos->execute();
				$rowpos = $resultpos->fetch();
				
				echo '<tr><td>' . $row['dbstafffname'] . '</td><td> ' . $row['dbstafflname'] . 
				'</td><td> ' . $rowpos['dbpermitid'] .  '</td><td> ' . $row['dbstaffemail'] .
				'</td><td>' . $row['dbstaffemcontact'] . '</td><td>' . $row['dbstaffemphone'] . '</td><td>' . $row['dbstaffrate'] . '</td><td>'.
				'<form action = "updatestaff.php" method = "post">
				<input type = "hidden" name = "staffid" value = "' . $row['dbstaffid'].'"><input type="submit" class="button" name = "theedit" value="Edit">
				</form></td></tr>';;
			}
		?>
	</table>
<?php
}
include_once 'footer.php'; //attempt to include footer file
?>