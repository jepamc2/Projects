<?php
//Developer(s): Joshua Mercer
//Date: 3/18/2017
//Purpose: This is order system for the staff
	$pagetitle = 'Insert Order'; //set title page 
	require_once "header.php"; //require the header file
	require_once "connect.php"; //require the connection file
	$showform = 1; //showfrom
	$allowedperms = array(1,2,13,3,8,9,10,5);
	
	$formfield['fflocation'] = $_SESSION['stafflocid'];
	
	$sqlselectt= "SELECT * from tables"; //SQL string 
	$resultt = $db->prepare($sqlselectt); //prepare statement
	$resultt->execute(); //execute statement
	
	if (isset($_POST['thesubmit']) )
	{	//cleanse data
		$formfield['ffordertype'] = $_POST['ordertype'];
		$formfield['fftableid'] = $_POST['table'];
		//validate data
		if(empty($formfield['ffordertype'])) {
			$errormsg .= "<p>You Have not Selected a Delivery Option</p>";
		}	
		if ($errormsg != "") { //print errors if there are any
			echo "YOU HAVE ERRORS!!!!";
			echo $errormsg;
		} 
		else //if  no errors 
		{			
			$sqlmax = "SELECT MAX(dborderid) AS maxid from orders"; //SQL string 
			$resultmax = $db->prepare($sqlmax); //prepare statement
			$resultmax->execute(); //execute statement
			$rowmax = $resultmax->fetch(); //fetch result set
			$maxid = $rowmax["maxid"];	// set max id
			$maxid = $maxid + 1; //set max equal to next id
			//SQL string 
			$sqlinsert = 'INSERT INTO orders 
				(dborderid, dbstaffid, dbcustid, dborderdate, dbordercomplete, dbordertypeid, dbtableid, dbordermade, dblocid) 
				VALUES (:bvorderid, :bvstaffid, 0, now(),0,:bvordertype,:bvtable,0, :bvlocid)';
			
			$stmtinsert = $db->prepare($sqlinsert); //prepare statement
			//bind values
			$stmtinsert->bindvalue(':bvorderid', $maxid);
			$stmtinsert->bindvalue(':bvstaffid', $_SESSION['staffloginid']);
			$stmtinsert->bindvalue(':bvtable', $formfield['fftableid']);
			$stmtinsert->bindvalue(':bvordertype', $formfield['ffordertype']);
			$stmtinsert->bindvalue(':bvlocid', $formfield['fflocation']);
			$stmtinsert->execute(); //execute statement
			//echo the order number and a button to continue to the next page
			echo "Order Number: " . $maxid;
			echo "<br>";
			echo "Table Number: " . $formfield['fftableid']; 
			echo '<br><br><form class="button" action="insertorderitem.php" method = "post">';
			echo '<input type = "hidden" name = "orderid" value = "'. $maxid .'">';
			echo '<input type="submit" class="button" name="thesubmit" value="Enter Order Items">';
			echo "</form>";
			$showform = 0;//echo the order number and a button to continue to the next page
		}
	}
	
//if the user has permission and is logged in
if ($showform == 1 && in_array($_SESSION['staffloginpermit'], $allowedperms))
{
	//below for is for user to choose order type
?>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		<fieldset><legend>Order Info</legend>
		
		<table border>
					<tr>
						<th><label>Table:</label></th>
						<td><select name="table" id="table">
						<option value = "">Please Select a Table</option>
						<?php while ($rowt = $resultt->fetch() )
							{
							if ($rowt['dbtableid'] == $formfield['fftableid'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowt['dbtableid'] . '" ' . $checker . '>' . $rowt['dbtablename'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Pick a Delivery Type:</th>
						<td><input type="radio" name="ordertype" id="ordertype" 
									value=3 <?php echo ' checked';?> />
							<label for="pickup">In-House</label>
							<input type="radio" name="ordertype" id="ordertype" 
									value=4 />
									<label for="delivery">Takeout</label>
							</td>
					</tr>
			</tr>
		</table>
		<input type="submit" class="button" name="thesubmit" value="Enter">
		</fieldset>
	</form>

<?php
}//visible
include_once 'footer.php';//include to footer once
?>