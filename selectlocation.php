<?php
//Developer(s): Joshua Mercer
//Date: 3/27/2017
//Purpose: This allows the user to search locations
$pagetitle = "Select Locations"; //page title
require_once 'header.php'; //require the header file
require_once 'connect.php'; //require the connection file
$errormsg = ""; //declare error message
$allowedperms = array(13); //allowed permissions for this page

	if( isset($_POST['thesubmit']) ) //when the submit button is presses
		{
			$formfield['fflocation'] = trim($_POST['location']);//cleanse data
			//create sql string to select where location is like entered data
			$sqlselect = "SELECT * FROM locations WHERE dblocname like CONCAT('%', :bvlocation, '%')";
			$result = $db->prepare($sqlselect); //prepare statement
			$result->bindValue(':bvlocation', $formfield['fflocation']); //bind parameters
			$result->execute(); //execute query
		}
	else //by default
		{
			$sqlselect = "SELECT * FROM locations"; //generic select location sql string
			$result = $db->prepare($sqlselect);
			$result->execute(); //get result from sql query
		}
	if (in_array($_SESSION['staffloginpermit'], $allowedperms)){ // if they meet permission requirements
	//below is the form search 
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Location Info</legend>
				<table border>
					<tr>
						<th>Location: </th>
						<td><input type="text" name="location" id="location"
						value = "<?php echo $formfield['fflocation']; ?>"></td>
					</tr>
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Location</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() ) //print result set
			{
				//echo location name and add a edit button
				echo '<tr><td>' . $row['dblocname'] . '</td><td> ' .
				'<form action = "updatelocation.php" method = "post">
						<input type = "hidden" name = "locid" value = "'
						. $row['dblocid'] . 
						'"><input type="submit" class="button" name = "theedit" value="Edit">
				</form>'  . '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php'; //attempt to include footer file
?>