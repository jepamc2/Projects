<?php
//Developer(s): Joshua Mercer
//Date: 3/27/2017
//Purpose: This allows the user to search locations
$pagetitle = "Select Category"; //page title
require_once 'header.php'; //require the header file
require_once 'connect.php'; //require the connection file
$errormsg = ""; //declare error message
$allowedperms = array(1,2,13,3); //allowed permissions for this page

	$formfield['fflocation'] = $_SESSION['stafflocid'];

	if( isset($_POST['thesubmit']) ) //when the submit button is presses
		{
			$formfield['ffcategoryname'] = trim($_POST['category']);//cleanse data
			//create sql string to select where category is like entered data
			$sqlselect = "SELECT * FROM category WHERE dbcatname like CONCAT('%', :bvcategoryname, '%') AND dblocid = :bvlocation";
			$result = $db->prepare($sqlselect); //prepare statement
			$result->bindValue(':bvcategoryname', $formfield['ffcategoryname']); //bind parameters
			$result->bindValue(':bvlocation', $formfield['fflocation']); //bind parameters
			$result->execute(); //execute query
		}
	else //by default
		{
			$sqlselect = "SELECT * FROM category WHERE dblocid = :bvlocation"; //generic select category sql string
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvlocation', $formfield['fflocation']); //bind parameters
			$result->execute();
		}
	if (in_array($_SESSION['staffloginpermit'], $allowedperms)){ // if they meet permission requirements
	//below is the form search 
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Category Info</legend>
				<table border>
					<tr>
						<th>Category: </th>
						<td><input type="text" name="category" id="category"
						value = "<?php echo $formfield['ffcategoryname']; ?>"></td>
					</tr>
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Category</th>
		<th>Description</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() ) //print result set
			{
				//echo category name and add a edit button
				echo '<tr><td>' . $row['dbcatname'] . '</td><td> ' .$row['dbcatdescr'] . '</td><td> ' .
				'<form action = "updatecategory.php" method = "post">
						<input type = "hidden" name = "catid" value = "'
						. $row['dbcatid'] . 
						'"><input type="submit" class="button" name = "theedit" value="Edit">
				</form>'  . '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php'; //attempt to include footer file
?>