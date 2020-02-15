<?php
//Developer(s): Joshua Mercer
//Date: 3/18/2017
//Purpose: This is order system for the staff
require_once "header.php"; //require the header file
require_once "connect.php"; //require the connection file 

	//cleanse data 
	$formfield['fforderid'] = $_POST['orderid'];
	if ($_POST['uporderitems']!= ''){
		$orderitemsarray = unserialize($_POST['uporderitems']);
	}else{$orderitemsarray = array();
	}
	//SQL string
	$sqlselecto = "SELECT orderitems.*, menu.dbmenuname
			FROM orderitems, menu
			WHERE menu.dbmenuid = orderitems.dbmenuid
			AND orderitems.dborderid = :bvorderid";
	$resulto = $db->prepare($sqlselecto);//prepare statement 
	$resulto->bindValue(':bvorderid', $formfield['fforderid']); //bind value
	$resulto->execute(); //execute prepared statement
	//SQL string
	$sqlinsert = 'UPDATE orders SET dbordercomplete = :bvordercomplete
								  WHERE dborderid = :bvorderid';
	$stmtinsert = $db->prepare($sqlinsert);//prepare statement 
	//bind values
	$stmtinsert->bindValue(':bvordercomplete', 1);
	$stmtinsert->bindValue(':bvorderid', $formfield['fforderid']);
	$stmtinsert->execute(); //execute prepared statement
	$ordertotal = 0; //set order total price to 0
	
	//Inventory controller
	
	//create an SQL string to get the location of the order for inventory control
	$sqllocation = 'SELECT * FROM orders WHERE dborderid = :bvorderid';
	$resultlocation = $db->prepare($sqllocation); //prep statement
	$resultlocation->bindvalue(':bvorderid', $formfield['fforderid']); //bind values
	$resultlocation->execute(); //execute statement
	$rowlocation = $resultlocation->fetch(); //fetch data from result set
	$formfield['fflocation'] = $rowlocation['dblocid']; //set formfield for location
	
	$sqlorder = 'SELECT * from orderitems WHERE dborderid = :bvorderid';
	$resultorder = $db->prepare($sqlorder);//prepare statement 
	$resultorder->bindValue(':bvorderid', $formfield['fforderid']);
	$resultorder->execute();//execute prepared statement
	
	while ($roworder = $resultorder->fetch() ){
		//SQL string to get inventory count from selected menu item from selected location
		$sqlcount = 'SELECT * FROM menu WHERE dbmenuid = :bvmenuid AND dblocid = :bvlocation';
		$resultcount = $db->prepare($sqlcount);//prepare statement 
		$resultcount->bindValue(':bvmenuid', $roworder['dbmenuid']);
		$resultcount->bindValue(':bvlocation', $formfield['fflocation']);
		$resultcount->execute();//execute prepared statement
		$rowinv = $resultcount->fetch();
		$inventory = $rowinv['dbmenuinventory'];
		if (in_array($roworder['dborderitemid'], $orderitemsarray)) {
		}else{$inventory = $inventory - 1;} //subtract one from inventory
		
		$sqlupdate = 'UPDATE menu SET dbmenuinventory = :bvinventory WHERE dbmenuid = :bvmenuid AND dblocid = :bvlocation';
		$resultupdate = $db->prepare($sqlupdate);//prepare statement 
		$resultupdate->bindValue(':bvinventory', $inventory);
		$resultupdate->bindValue(':bvmenuid', $roworder['dbmenuid']);
		$resultupdate->bindValue(':bvlocation', $formfield['fflocation']);
		$resultupdate->execute();//execute prepared statement

	}
?>
<h2>Your Order has been submitted.  Thank you!</h2>
<table border>
		<tr>
			<th>Item</th>
			<th>Price</th>
			<th>Notes</th>
		</tr>
		<?php
		while ($rowo = $resulto->fetch() )//get all order items from this order
			{
			$ordertotal = $ordertotal + $rowo['dborderitemprice'];//total cost of order
			//print order name, price, and any notes in a table
			echo '<tr><td>' . $rowo['dbmenuname'] . '</td><td>' . $rowo['dborderitemprice'] . '</td>';
			echo '<td>' . $rowo['dborderitemnotes'] . '</td></tr>';
			}
		echo '<tr><th>Total</th>';
		echo '<th>' . $ordertotal . '</th><td></td></tr>';
		?>
</table>
	
<?php			
	include_once 'footer.php';//include footer file once
?>