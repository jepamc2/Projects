<?php
//Developer(s): Joshua Mercer
//Date: 4/11/2017
//Purpose: This allows the kitchen staff to prepare the orders
$pagetitle = 'Make Order';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;

	$allowedperms = array(1,2,13,3,8,9,10,5); //allowed permissions for this page
	$formfield['fflocation'] = $_SESSION['stafflocid'];
	
	$sqlselect = 'SELECT * FROM orders WHERE dbordermade = :bvcartmade AND dblocid = :bvlocation';
	$result = $db->prepare($sqlselect);	
	$result->bindValue(':bvcartmade', 0);
	$result->bindValue(':bvlocation', $formfield['fflocation']);
	$result->execute();
	
	if(isset($_POST['makecomplete']))
	{
		$formfield['fforderid'] = $_POST['orderid'];
		$showform = 0;
		$sqlupdate = 'UPDATE orders SET dbordermade = :bvordermade WHERE dborderid = :bvorderid';
		$stmtupdate = $db->prepare($sqlupdate);
		$stmtupdate->bindValue(':bvordermade', 1);
		$stmtupdate->bindValue(':bvorderid', $formfield['fforderid']);
		$stmtupdate->execute();
		
		echo '<h2>Order has been made<h2>';
		echo '<script>window.location = "makeorder.php";</script>'; //redirect to set URL
	}
	
if ($showform = 1 && in_array($_SESSION['staffloginpermit'], $allowedperms))	
{		
		echo '<form action="oldorder.php" method="post">
			  <input type="submit" class="button" name="themake" value="Retrieve Old Order">
			  </form><br><br>';
		
		echo'<center><table class="ordertable">
			 <tr>';
		$counter = 0;
		while ( $row = $result-> fetch() )
			{
						$sqlselecto = "SELECT orderitems.*, menu.dbmenuname
						FROM orderitems, menu
						WHERE menu.dbmenuid = orderitems.dbmenuid
						AND orderitems.dborderid = :bvorderid";
		$resulto = $db->prepare($sqlselecto);
		$resulto->bindValue(':bvorderid', $row['dborderid']);
		$resulto->execute();
		
		echo '<td><center><table class="insideordertable">
			  <caption>Order: '.$row['dborderid'].'</caption>
			  <tr>
			  <th>Item</th>
			  <th>Prices</th>
			  <th>Notes</th>
			  </tr>';
			  $ordertotal = 0;
			  while ($rowo = $resulto->fetch() )
			  {
				  $ordertotal = $ordertotal + $rowo['dborderitemprice'];
				  
				  echo '<tr><td>' . $rowo['dbmenuname'] . '</td><td>'
				  . $rowo['dborderitemprice'] . '</td>';
				  echo '<td>' .$rowo['dborderitemnotes'].'</td></tr>';
			  }
				echo '<tr><th>Total</th>';
				echo '<th>' . $ordertotal . '</th><td></td></tr>';
				echo '<td colspan="3"><form action="makeorder.php" method="post">
					  <input type="hidden" name="orderid" 
					  value="'. $row['dborderid'] .'">
					  <input type="submit" class="button" name="makecomplete" value="Mark Order as Made">
					  </form></td>';	     
				echo '</table></center></td>';
				$counter = $counter +1;
				if ($counter >= 3){
					$counter = 0;
					echo '</tr><tr>';
				}	
			
			}
		echo '</table></center>';

}
include_once 'footer.php';
?>