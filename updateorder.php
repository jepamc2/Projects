<?php
//Developer(s): Joshua Mercer
//Date: 4/11/2017
//Purpose: This allows the user to update order information
$pagetitle = 'Update Order';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$stringclause = "";
$allowedperms = array(1,2,13,3,8,9,10,5); //allowed permissions for this page

			$formfield['fflocation'] = $_SESSION['stafflocid'];

			$sqlselectstaff = "SELECT * FROM staff WHERE dblocid = :bvlocation";
			$resultstaff = $db->prepare($sqlselectstaff);
			$resultstaff->bindValue(':bvlocation', $formfield['fflocation']);
			$resultstaff->execute();

			$formfield['fforderid'] = $_POST['orderid'];
			$sqlselect = 'SELECT * FROM orders WHERE dborderid = :bvorderid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvorderid', $formfield['fforderid']);
			$result->execute();
			$row = $result->fetch(); 
			$showform = 1;
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			echo '<p>The form was submitted.</p>';
			
			//Data Cleansing
			$formfield['ffstaffid'] = trim($_POST['staffid']);
			$formfield['fforderid'] = $_POST['orderid'];
			if ($_POST['orderdate'] != '0000-00-00 00:00:00') {
				$formfield['fforderdate'] = date_create(trim($_POST['orderdate']));
				$formfield['fforderdate']  = date_format($formfield['fforderdate'], 'Y-m-d h:i:s');
				if(empty($formfield['fforderdate'])){$errormsg .= "<p>Your order date is empty.</p>";}
				$stringclause= ', dborderdate = :bvorderdate';
			} 

			if(empty($formfield['ffstaffid'])){$errormsg .= "<p>Your staff choice is empty.</p>";}
			
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
					$sqlinsert = 'UPDATE orders SET dbstaffid = :bvstaffid'.$stringclause.' WHERE dborderid = :bvorderid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvstaffid', $formfield['ffstaffid']);
					if($_POST['orderdate'] != '0000-00-00 00:00:00'){
					$stmtinsert->bindvalue(':bvorderdate', $formfield['fforderdate']);
					}
					$stmtinsert->bindvalue(':bvorderid', $formfield['fforderid']);
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

	if (in_array($_SESSION['staffloginpermit'], $allowedperms) && $showform = 1)
	{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Order Information</legend>
				<table border>
					<tr>
						<th><label>Staff Member:</label></th>
						<td><select name="staffid" id="staffid">
						<option value = "">Please Select Staff Member</option>
						<?php while ($rowstaff = $resultstaff->fetch() )
							{
							if ($rowstaff['dbstaffid'] == $row['dbstaffid'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowstaff['dbstaffid'] . '" ' . $checker . '>' . $rowstaff['dbstaffusername'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<?php
						$dateholder = date_create($row['dborderdate']);
						$dateholder = date_format($dateholder, 'Y-m-d h:i:s');
						?>
						<th>Date of Order</th>
						<td><input type="datetime-local" name="orderdate" id="orderdate" 
							value="<?php echo $dateholder ?>"></td>
					</tr>
				</table>
				<input type="hidden" name = "orderid" value=<?php echo $formfield['fforderid'] ?>>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	} 
	else if ($showform = 2 && in_array($_SESSION['staffloginpermit'], $allowedperms)) {
	?>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Order Information</legend>
					<table border>
					<tr>
						<th><label>Staff Member:</label></th>
						<td><select name="staffid" id="staffid">
						<option value = "">Please Select a Staff Member</option>
						<?php while ($rowstaff = $resultstaff->fetch() )
							{
							if ($rowstaff['dbpermitid'] == $formfield['fforderid'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowstaff['dbstaffid'] . '" ' . $checker . '>' . $rowstaff['dbstaffusername'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Date of Order</th>
						<td><input type="datetime-local" name="orderdate" id="orderdate" 
							value="<?php echo $formfield['fforderdate'] ?>"></td>
					</tr>
				</table>
				<input type="hidden" name = "orderid" value=<?php echo $formfield['fforderid'] ?>>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
}
include_once 'footer.php';
?>