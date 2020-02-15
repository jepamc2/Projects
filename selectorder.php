<?php
//Developer(s): Joshua Mercer
//Date: 4/11/2017
//Purpose: This allows the user to select orders
$pagetitle = 'Select Order';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
	$allowedperms = array(1,2,13,3,8,9,10,5); //allowed permissions for this page
	$formfield['fflocation'] = $_SESSION['stafflocid'];
	
	if( isset($_POST['thesubmit']) )
		{
			$stringclause = "";
		
			if ($_POST['orderdate'] != '') {
				$formfield['fforderdate'] = date_create(trim($_POST['orderdate']));
				$formfield['fforderdate']  = date_format($formfield['fforderdate'], 'Y-m-d');
				$stringclause = " AND dborderdate like CONCAT('%', :bvorderdate, '%')";
			}
			if ($_POST['orderid'] != '') {
				$formfield['fforderid'] = $_POST['orderid'];
				$stringclause = " AND dborderid = :bvorderid";
			}			
			$sqlselect = "SELECT * FROM orders WHERE dblocid = :bvlocid AND dbordercomplete = :bvordercomplete"
							. $stringclause;
							
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvlocid', $formfield['fflocation']);
			$result->bindValue(':bvordercomplete', 1);
			if ($formfield['fforderdate'] != '') {
				$result->bindValue(':bvorderdate', $formfield['fforderdate']);
			}
			if ($formfield['fforderid'] != '') {
				$result->bindValue(':bvorderid', $formfield['fforderid']);
			}
			$result->execute();
			
		}
	else
		{
			$sqlselect = "SELECT * FROM orders WHERE dblocid = :bvlocid AND dbordercomplete = :bvordercomplete";
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvlocid', $formfield['fflocation']);
			$result->bindValue(':bvordercomplete', 1);
			$result->execute();
			
		}

if ($visible == 1 && in_array($_SESSION['staffloginpermit'], $allowedperms))
{		
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Order Information</legend>
				<table border>
					<tr>
						<th>Order ID</th>
						<td><input type="text" name="orderid" id="orderid"></td>
					</tr>
					<tr>
						<th>Date of Order</th>
						<td><input type="date" name="orderdate" id="orderdate"></td>
					</tr>
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Order ID</th>
		<th>Staff Member</th>
		<th>Date and Time</th>
		<th>Order Type</th>
		<th>Total</th>
		<th>Edit</th>
		<th>Edit Order Items</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{

				if($row['dbordertypeid'] == 1) {
					$type = "PICKUP";
				}else if($row['dbordertype'] == 2){
					$type= "DELIVERY";
				}else if($row['dbordertype'] == 3){
					$type= "IN-HOUSE";
				}else if($row['dbordertype'] == 4){
					$type= "TAKEOUT";
				}
				
				$sqlselectoi = "SELECT * FROM orderitems WHERE dborderid = :bvorderid";
					$resultoi = $db->prepare($sqlselectoi);
					$resultoi->bindValue(':bvorderid', $row['dborderid']);
					$resultoi->execute();
				
				$ordertotal = 0;
				while ( $rowoi = $resultoi-> fetch() ) {
					$ordertotal = $ordertotal + $rowoi['dborderitemprice'];
				}
				
				$sqlselects = "SELECT * FROM staff WHERE dbstaffid = :bvstaffid";
				$results = $db->prepare($sqlselects);
				$results->bindValue(':bvstaffid', $row['dbstaffid']);
				$results->execute();
				$rows = $results->fetch();
				
				echo '<tr><td>' . $row['dborderid'] . '</td><td> '  . $rows['dbstaffusername'] . '</td><td> '  . $row['dborderdate'] 
				. '</td><td> '  . $type . '</td><td> ' . $ordertotal . '</td><td> ' .
				'<form action = "updateorder.php" method = "post">
						<input type = "hidden" name = "orderid" value = "'
						. $row['dborderid'] . 
						'"><input type="submit" class="button" name = "theedit" value="Edit">
				</form>' .
				'</td><td> ' .
				'<form action="insertorderitem.php" method = "post">
				<input type = "hidden" name = "orderid" value = "'. $row['dborderid'] .'">
				<input type = "hidden" name = "orderupdate" value = "1">
				<input type="submit" class="button" name="thesubmit" value="Edit Order Items">
				</form>'
				. '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>