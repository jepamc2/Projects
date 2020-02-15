<?php
//Developer(s): Joshua Mercer
//Date: 3/27/2017
//Purpose: This allows the user to update Menu
$pagetitle = "Update Menu"; //page title
require_once "header.php"; //require the header file
require_once "connect.php"; //require the connection file
	//declare variables 
	$errormsg = "";
	$showform = 1; 
	$assignedperm = $_SESSION['staffloginpermit']; //set assigned perm
	$allowedperms = array(13,1,2,3); //allowed permissions for this page
			$formfield['fflocation'] = $_SESSION['stafflocid'];
				
			$formfield['ffmenuid'] = $_POST['menuid']; //set Category id
			$sqlselect = 'SELECT * from menu where dbmenuid = :bvmenuid'; //create an SQL string to select locations
			$result = $db->prepare($sqlselect); //prepare statement
			$result->bindValue(':bvmenuid', $formfield['ffmenuid']); //bind parameters
			$result->execute(); //execute query
			$row = $result->fetch(); //fetch result set
	
			$sqlselectt = 'SELECT * from menu where dbmenuid = :bvmenuid'; //create an SQL string to select locations
			$resultt = $db->prepare($sqlselectt); //prepare statement
			$resultt->bindValue(':bvmenuid', $formfield['ffmenuid']); //bind parameters
			$resultt->execute(); //execute query
	
		if( isset($_POST['thesubmit']) ) //if the user submits
		{	
			$showform = 2; //show 2nd form
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffmenuname'] = $row['dbmenuname'];
			$formfield['ffmenuprice'] = trim(strtolower($_POST['menuprice']));
			$formfield['ffmenudescr'] = trim($_POST['menudescr']);
			$formfield['ffmenuinventory'] = trim($_POST['menuinventory']);
			$formfield['ffmenuactive'] = ($_POST['menuactive']);

			if(empty($formfield['ffmenuprice'])){$errormsg .= "<p>Menu price is empty.</p>";}
			if(empty($formfield['ffmenudescr'])){$errormsg .= "<p>Your menu item description is empty.</p>";}
			if(empty($formfield['ffmenuinventory'])){$errormsg .= "<p>Your menu item inventory is empty.</p>";}
			
			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				try
				{
					$sqlinsert = 'UPDATE menu SET dbmenuprice = :bvmenuprice, dbmenudescr = :bvmenudescr, dbmenuinventory = :bvmenuinventory, dbmenuactive = :bvmenuactive WHERE dbmenuid = :bvmenuid'; //UPDATE sql string
					$stmtinsert = $db->prepare($sqlinsert); //prepare statement
					$stmtinsert->bindvalue(':bvmenuprice', $formfield['ffmenuprice']);
					$stmtinsert->bindvalue(':bvmenudescr', $formfield['ffmenudescr']);
					$stmtinsert->bindvalue(':bvmenuinventory', $formfield['ffmenuinventory']);
					$stmtinsert->bindvalue(':bvmenuactive', $formfield['ffmenuactive']);
					$stmtinsert->bindvalue(':bvmenuid', $formfield['ffmenuid']);
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
			<fieldset><legend>Menu info for: <?php echo $row['dbmenuname']; ?></legend>
				<table border>
					<tr>
						<th>Item Price:</th>
						<td><input type="text" name="menuprice" id="menuprice"
						value = "<?php echo $row['dbmenuprice']; ?>"></td>
					</tr>
					<tr>
						<th>Item Description:</th>
						<td><input type="text" name="menudescr" id="menudescr"
						value = "<?php echo $row['dbmenudescr']; ?>"></td>
					</tr>
					<tr>
						<th>Inventory Count: </th>
						<td><input type="text" name="menuinventory" id="menuinventory"
						value = "<?php echo $row['dbmenuinventory']; ?>"></td>
					</tr>
					<tr>
					<th><label for="menuactive">Item Status:</label></th>
						<td><select name="menuactive" id="menuactive">
								<option value="0" <?php if($row['dbmenuactive'] == "0" ){echo ' selected';}?>>Inactive</option>
								<option value="1" <?php if($row['dbmenuactive'] == "1" ){echo ' selected';}?>>Active</option>
							</select>
						</td>
					</tr>
				</table>
				<input type="hidden" name = "menuid" value=<?php echo $formfield['ffmenuid'] ?>>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2 && in_array($_SESSION['staffloginpermit'], $allowedperms)) { //form 2
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend></legend>
				<table border>
					<tr>
						<th>Item Price:</th>
						<td><input type="text" name="menuprice" id="menuprice"
						value = "<?php echo $formfield['ffmenuprice']; ?>"></td>
					</tr>
					<tr>
						<th>Item Description: </th>
						<td><input type="text" name="menudescr" id="menudescr"
						value = "<?php echo $formfield['ffmenudescr']; ?>"></td>
					</tr>
					<tr>
						<th>Inventory Count: </th>
						<td><input type="text" name="menuinventory" id="menuinventory"
						value = "<?php echo $formfield['ffmenuinventory']; ?>"></td>
					</tr>
					<tr>
					<th><label for="menuactive">Item Status: </label></th>
						<td><select name="menuactive" id="menuactive">
								<option value="0" <?php if( $formfield['ffmenuactive'] == "0" ){echo ' selected';}?>>Inactive</option>
								<option value="1" <?php if($formfield['ffmenuactive'] == "1" ){echo ' selected';}?>>Active</option>
							</select>
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
			<br><br>
		<table border>
	<tr>
		<th>ID</th>
		<th>Category</th>
		<th>Name</th>
		<th>Price</th>
		<th>Description</th>
		<th>Quantity</th>
		<th>Status</th>
	</tr>
	<?php 
		while ( $rowt = $resultt-> fetch() )
			{
				if ($row['dbmenuactive'] == 1){
					$active = 'YES';
				}else {$active = 'NO';}
				
				$sqlselectc = 'SELECT * FROM category WHERE dbcatid = :bvcatid AND dblocid = :bvlocid '; //create an SQL string to select locations
				$resultc = $db->prepare($sqlselectc); //prepare statement
				$resultc->bindValue(':bvcatid', $rowt['dbcatid']); //bind parameters
				$resultc->bindValue(':bvlocid', $formfield['fflocation']);
				$resultc->execute(); //execute query
				$rowc = $resultc-> fetch();
			
				echo '<tr><td> ' . $rowt['dbmenuid'] . 
				'</td><td> ' . $rowc['dbcatname'] . 
				'</td><td> ' . $rowt['dbmenuname'] . 
				'</td><td> ' . $rowt['dbmenuprice'] . 
				'</td><td> ' . $rowt['dbmenudescr'] . 
				'</td><td> ' . $rowt['dbmenuinventory'] . 
				'</td><td> ' . $active . '</td></tr>';
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