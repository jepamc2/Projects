<?php
//Developer(s): Maggie Hussey
//Date: 3/13/2018
//Purpose: to allow the user to enter new staff info
//Edited by: Joshua Mercer
$pagetitle = 'Insert Menu Item';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1; 
$allowedperms = array(13,1,2,3); //allowed permissions for this page

$formfield['fflocation'] = $_SESSION['stafflocid'];

$sqlselectc = "SELECT * from category WHERE dblocid = :bvlocation";
$resultc = $db->prepare($sqlselectc);
$resultc->bindValue(':bvlocation', $formfield['fflocation']);
$resultc->execute();

		if( isset($_POST['thesubmit']) )
		{
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			
			$formfield['ffcatid'] = trim($_POST['catid']);
			$formfield['ffmenuname'] = trim($_POST['menuname']);
			$formfield['ffmenuprice'] = trim(strtolower($_POST['menuprice']));
			$formfield['ffmenudescr'] = trim($_POST['menudescr']);
			$formfield['ffmenuinventory'] = trim($_POST['menuinventory']);
			$formfield['ffmenuactive'] = trim($_POST['menuactive']);
			
		
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffcatid'])){$errormsg .= "<p>Category is empty.</p>";}
			if(empty($formfield['ffmenuname'])){$errormsg .= "<p>Item name is empty.</p>";}
			if(empty($formfield['ffmenuprice'])){$errormsg .= "<p>Menu price is empty.</p>";}
			if(empty($formfield['ffmenudescr'])){$errormsg .= "<p>Your menu item description is empty.</p>";}
			if(empty($formfield['ffmenuinventory'])){$errormsg .= "<p>Your menu item inventory is empty.</p>";}
			if(empty($formfield['ffmenuactive'])){$errormsg .= "<p>Item activity is empty.</p>";}
			
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
					$sqlinsert = 'INSERT INTO menu (dbcatid, dbmenuname, dbmenuprice, dbmenudescr, dbmenuinventory, dbmenuactive, dblocid)
								  VALUES (:bvcatid, :bvmenuname, :bvmenuprice, :bvmenudescr, :bvmenuinventory, :bvmenuactive, :bvlocation)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvcatid', $formfield['ffcatid']);
					$stmtinsert->bindvalue(':bvmenuname', $formfield['ffmenuname']);
					$stmtinsert->bindvalue(':bvmenuprice', $formfield['ffmenuprice']);
					$stmtinsert->bindvalue(':bvmenudescr', $formfield['ffmenudescr']);
					$stmtinsert->bindvalue(':bvmenuinventory', $formfield['ffmenuinventory']);
					$stmtinsert->bindvalue(':bvmenuactive', $formfield['ffmenuactive']);
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
		
		$sqlselect = "SELECT menu.*, category.dbcatname
							FROM menu, category
							WHERE menu.dblocid = :bvlocation
							AND menu.dblocid = category.dblocid 
							AND menu.dbcatid = category.dbcatname";
	$result = $db->prepare($sqlselect);
	$result->bindValue(':bvlocation', $formfield['fflocation']);
	$result->execute();
	
if(in_array($_SESSION['staffloginpermit'], $allowedperms))
	{
	?>
	
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Menu Items</legend>
				<table border>
					<tr>
						<th><label>Category:</label></th>
						<td><select name="catid" id="catid">
						<option value = "">Please Select a Category</option>
						<?php while ($rowc = $resultc->fetch() )
							{
							if ($rowc['dbcatid'] == $formfield['ffcatname'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowc['dbcatid'] . '" ' . $checker . '>' . $rowc['dbcatname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Menu Item Name</th>
						<td><input type="text" name="menuname" id="menuname"
						value = "<?php echo $formfield['ffmenuname']; ?>"></td>
					</tr>
					<tr>
						<th>Menu Item Price</th>
						<td><input type="text" name="menuprice" id="menuprice"
						value = "<?php echo $formfield['ffmenuprice']; ?>"></td>
					</tr>
					<tr>
						<th>Menu Item Description</th>
						<td><input type="text" name="menudescr" id="menudescr"
						value = "<?php echo $formfield['ffmenudescr']; ?>"></td>
					</tr>
					<tr>
						<th>Menu Item Inventory</th>
						<td><input type="text" name="menuinventory" id="menuinventory"
						value = "<?php echo $formfield['ffmenuinventory']; ?>"></td>
					</tr>
					<tr>
					<th><label for="menuactive">Menu Item Status:</label></th>
						<td><select name="menuactive" id="menuactive">
								<option value="" <?php if( isset($formfield['menuactive']) && $formfield['menuactive'] == "" )?>>SELECT ONE</option>
								<option value="0" <?php if( isset($formfield['menuactive']) && $formfield['menuactive'] == "0" )?>>Inactive</option>
								<option value="1" <?php if( isset($formfield['menuactive']) && $formfield['menuactive'] == "1" )?>>Active</option>
							</select>
						</td>
					</tr>
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
<?php
}
include_once 'footer.php';
?>