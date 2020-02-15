<?php
//Developer(s): Joshua Mercer
//Date: 3/27/2017
//Purpose: This allows the user to search locations
$pagetitle = "Select Menu"; //page title
require_once 'header.php'; //require the header file
require_once 'connect.php'; //require the connection file
$errormsg = ""; //declare error message
	$allowedperms = array(13,1,2,3); //allowed permissions for this page
	
	$formfield['fflocation'] = $_SESSION['stafflocid'];

	if( isset($_POST['thesubmit']) ) //when the submit button is presses
		{
			$formfield['ffmenuname'] = trim($_POST['menuname']);//cleanse data
			//create sql string to select where manu is like entered data
			$sqlselect = "SELECT * FROM menu WHERE dbmenuname like CONCAT('%', :bvmenuname, '%') AND dblocid = :bvlocation";
			$result = $db->prepare($sqlselect); //prepare statement
			$result->bindValue(':bvmenuname', $formfield['ffmenuname']); //bind parameters
			$result->bindValue(':bvlocation', $formfield['fflocation']); //bind parameters
			$result->execute(); //execute query
		}
	else //by default
		{
			$sqlselect = "SELECT * FROM menu WHERE dblocid = :bvlocation"; //generic select manu sql string
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvlocation', $formfield['fflocation']); //bind parameters
			$result->execute();
		}
	if (in_array($_SESSION['staffloginpermit'], $allowedperms)){ // if they meet permission requirements
	//below is the form search 
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Menu Info</legend>
				<table border>
					<tr>
						<th>Menu Name: </th>
						<td><input type="text" name="menuname" id="menuname"
						value = "<?php echo $formfield['ffmenuname']; ?>"></td>
					</tr>
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Menu</th>
		<th>Inventory</th>
		<th>Price</th>
		<th>Active</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() ) //print result set
			{
				if ($row['dbmenuactive'] == 1){
					$active = 'YES';
				}else {$active = 'NO';}
			
				//echo manu name and add a edit button
				echo '<tr><td>' . $row['dbmenuname'] . '</td><td> ' .$row['dbmenuinventory'] .'</td><td> ' .$row['dbmenuprice'] . '</td><td> ' . $active . '</td><td> ' .
				'<form action = "updatemenu.php" method = "post">
						<input type = "hidden" name = "menuid" value = "'
						. $row['dbmenuid'] . 
						'"><input type="submit" class="button" name = "theedit" value="Edit">
				</form>'  . '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php'; //attempt to include footer file
?>