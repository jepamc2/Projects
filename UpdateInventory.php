<?php
//Developer(s): Blakley Parker
//Date: 4/2/2017
//Purpose: This page will allow the user to update the inventory level of the items in the database.

//Declare page title as well as adding the header and connect file
$pagetitle = 'Update Inventory';
require_once 'header.php';
require_once 'connect.php';
	
	$errormsg = "";
	$showform = 1;
	$allowedperms = array(1,2,13,3); //allowed permissions for this page
	
			//Get the formfield menuid and create a SQL Select statement to select all the items in menu
			//where the menu id is equal to the bind value menu id
			$formfield['ffmenuid'] = $_POST['menuid'];
			$sqlselect = 'SELECT * from menu where dbmenuid = :bvmenuid';
			
			//Preapre the SQL Select statement
			$result = $db->prepare($sqlselect);
			//Bind the menu id from the menu id formfield
			$result->bindValue(':bvmenuid', $formfield['ffmenuid']);
			$result->execute();
			$row = $result->fetch(); 

		//If the submit button is clicked
		if( isset($_POST['thesubmit']) )
		{	
			//The showform session variable is set to 2
			$showform = 2;
			$formfield['ffmenuid'] = $_POST['menuid'];
			//A message will be displayed to the user stating that the form was submitted
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffitemname'] = trim($_POST['itemname']);
			$formfield['ffinventory'] = trim($_POST['inventory']);
		
			
     		//CHECK FOR EMPTY FIELDS
    		//Complete the lines below for any REQIURED form fields only.
			//Do not do for optional fields.
			
			if(empty($formfield['ffitemname'])){$errormsg .= "<p>Your item name field is empty.</p>";}
			if(empty($formfield['ffinventory'])){$errormsg .= "<p>Your inventory is empty.</p>";}
			
			//DISPLAY ERRORS
			//If we have concatenated the error message with details, then let the user know
			
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
					//Update the menu changing the item and inventory level if changed
					$sqlinsert = 'update menu set dbmenuname = :bvitemname,
								  dbmenuinventory = :bvinventory
								  where dbmenuid = :bvmenuid';
					//Prepare the SQL statement
					$stmtinsert = $db->prepare($sqlinsert);
					//Bind all the formfields to a bind value
					$stmtinsert->bindvalue(':bvitemname', $formfield['ffitemname']);
					$stmtinsert->bindvalue(':bvinventory', $formfield['ffinventory']);
					$stmtinsert->bindvalue(':bvmenuid', $formfield['ffmenuid']);
					$stmtinsert->execute();
					//Execute the SQL statement and display to the user that there are not errors
					echo "<div class='success'><p>There are no errors.  Thank you.</p></div>";
				}//try
				catch(PDOException $e)
				{
					echo 'ERROR!!!' .$e->getMessage();
					exit();
				}
			}//else statement end
		}//if isset submit

	if ($showform == 1 && in_array($_SESSION['staffloginpermit'], $allowedperms))
	{
	//Form to allow the user to change the item name and/or inventory level in the database
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Inventory</legend>
				<table border>
					<tr>
						<th>Item Name</th>
						<td><input type="text" name="itemname" id="itemname"
						value = "<?php echo $row['dbmenuname']; ?>"></td>
					</tr>
					<tr>
						<th>Inventory</th>
						<td><input type="text" name="inventory" id="inventory"
						value = "<?php echo $row['dbmenuinventory']; ?>"></td>
					</tr>
				</table>
				<input type="hidden" name = "menuid" value="<?php echo $formfield['ffmenuid'] ?>">
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2 && in_array($_SESSION['staffloginpermit'], $allowedperms)) {
	//Form to allow the user to change the item name and/or inventory level in the database
	?>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Inventory</legend>
				<table border>
					<tr>
						<th>Item Name</th>
						<td><input type="text" name="itemname" id="itemname"
						value = "<?php echo $formfield['ffitemname']; ?>"></td>
					</tr>
					<tr>
						<th>Inventory</th>
						<td><input type="text" name="inventory" id="inventory"
						value = "<?php echo $formfield['ffinventory']; ?>"></td>
					</tr>
				</table>
				<input type="hidden" name = "menuitemid" value=<?php echo $formfield['ffmenuid'] ?>>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
<?php
}
//Link the footer to the page
include_once 'footer.php';
?>