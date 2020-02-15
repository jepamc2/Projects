<?php
//Developer(s): Blakley Parker
//Date: 4/2/2017
//Purpose: This page will allow the user to view the inventory of the items in the database.

//Declare page title as well as adding the header and connect file
$pagetitle = 'View Inventory';
require_once 'header.php';
require_once 'connect.php';

	//Declare a
	$errormsg = "";
	$showform = 1;
	$allowedperms = array(1,2,13,3); //allowed permissions for this page

	$formfield['fflocation'] = $_SESSION['stafflocid'];

	//if statement to check if the submit button was checked
	if( isset($_POST['thesubmit']) )
		{
			//if it is, get the formfield for the item name 
			$formfield['ffitemname'] = trim($_POST['itemname']);
			
			//SQL statement to acquire all the items from the menu for the location
			$sqlselect = "SELECT * from menu where dbmenuname like CONCAT('%', :bvitemname, '%')
							AND dblocid = :bvlocation ";
						
			//Prepare the SQL statement and bind the formfield values
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvitemname', $formfield['ffitemname']);
			$result->bindValue(':bvlocation', $formfield['fflocation']);
			$result->execute();
		}
	else
		{
			//SQL statement to acquire just the name and inventory level from the menu table
				$sqlselect = "SELECT * FROM menu WHERE dblocid = :bvlocation";
				$result = $db->prepare($sqlselect);
				$result->bindValue(':bvlocation', $formfield['fflocation']);
				$result->execute();
		}

if (in_array($_SESSION['staffloginpermit'], $allowedperms))
{		
	?>

	<!--Form to allow the user to search for the item name-->
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Inventory</legend>
				<table border>
					<tr>
						<th>Item Name</th>
						<td><input type="text" name="itemname" id="itemname"
						value ="<?php echo $formfield['ffitemname']; ?>"></td>
					</tr>
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Search">
			</fieldset>
		</form>
			<br><br>

			<br><br>
	<!--Output table to display the information gathered from the database-->
	<table border>
	<tr>

		<th>Item Name</th>
		<th>Inventory</th>
		<th>Edit</th>

	<?php 
	//Acquiring the item name and the inventory level of the menu items as well as creating a edit button that
	//sends the user to an Update Inventory page
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbmenuname']  . '</td><td> '
				.$row['dbmenuinventory'] . '</td><td>' .
				
				'<form action = "UpdateInventory.php" method = "post">
						<input type = "hidden" name = "menuid" value = "'
						. $row['dbmenuid'] . 
						'"><input type="submit" class="button" name = "theedit" value="Edit">
				</form>'
			
				. '</td></tr>';
			}
		?>
	</table>
<?php
}
//Link the footer
include_once 'footer.php';
?>