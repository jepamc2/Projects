<?php
//Developer(s): Joshua Mercer
//Date: 4/11/2017
//Purpose: This allows the kitchen staff to retrieve made orders in 
$pagetitle = 'Retrieve Order';
require_once 'header.php';
require_once 'connect.php';
$errormsg = "";
$showform = 1;
	$allowedperms = array(1,2,13,3,8,9,10,5); //allowed permissions for this page
	$formfield['fflocation'] = $_SESSION['stafflocid'];

	$sqlselect = "SELECT * FROM orders WHERE dblocid = :bvlocation AND dbordermade = :bvmade AND dborderdate LIKE CONCAT('%', CURDATE(), '%')";
	$result = $db->prepare($sqlselect);	
	$result->bindValue(':bvmade', 1);
	$result->bindValue(':bvlocation', $formfield['fflocation']);
	$result->execute();
	
	if(isset($_POST['retrieve']))
	{
		$formfield['fforderid'] = $_POST['orderid'];
		$showform = 0;
		$sqlupdate = 'UPDATE orders SET dbordermade = :bvordermade WHERE dborderid = :bvorderid';
		$stmtupdate = $db->prepare($sqlupdate);
		$stmtupdate->bindValue(':bvordermade', 0);
		$stmtupdate->bindValue(':bvorderid', $formfield['fforderid']);
		$stmtupdate->execute();
		echo '<script>window.location = "makeorder.php";</script>'; //redirect to set URL
	}	
	
if ($showform = 1 && in_array($_SESSION['staffloginpermit'], $allowedperms))
{		
		echo'<br><br>
			<table border>
			<tr>
				<th>Order ID</th>
				<th>Date and Time</th>
				<th>Order Complete</th>
				<th>Pickup Or Delivery</th>
				<th>Total</th>
				<th>Make Order</th>
			</tr>';
		while ( $row = $result-> fetch() )
			{
				if($row['dbordercomplete'] == 0) {
					$openorder = "NO";
				} else {
					$openorder= "YES";
				}

				if($row['dbordertypeid'] == 1) {
					$ordtype = "PICKUP";
				}else if($row['dbordertypeid'] == 2){
					$ordtype= "DELIVERY";
				}else if($row['dbordertypeid'] == 3){
					$ordtype= "IN-HOUSE";
				}else if($row['dbordertypeid'] == 4){
					$ordtype= "TAKEOUT";
				}
				
				$sqlselectoi = "SELECT * FROM orderitems WHERE dborderid = :bvorderid";
					$resultoi = $db->prepare($sqlselectoi);
					$resultoi->bindValue(':bvorderid', $row['dborderid']);
					$resultoi->execute();
				
				$ordertotal = 0;
				while ( $rowoi = $resultoi-> fetch() ) {
					$ordertotal = $ordertotal + $rowoi['dborderitemprice'];
				}
				
				echo '<tr><td>' . $row['dborderid'] . '</td><td> '  . $row['dborderdate'] 
				. '</td><td> '  . $openorder . '</td><td> '  . $ordtype . 
				'</td><td> ' . $ordertotal . '</td><td> ' .
				'<form action="oldorder.php" method = "post">
				<input type = "hidden" name ="orderid" value = "'. $row['dborderid'] .'">
				<input type="submit" class="button" name="retrieve" value="Retrieve Order">
				</form>'
				. '</td></tr>';
			}

	echo '</table>';

}
include_once 'footer.php';
?>