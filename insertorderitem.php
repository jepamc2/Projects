<?php
//Developer(s): Joshua Mercer
//Date: 3/18/2017
//Purpose: This is order system for the staff
	require_once "header.php"; //require header file
	require_once "connect.php";  //require connection file 
	$assignedperm = $_SESSION['staffloginpermit']; //set assigned perm
	$allowedperms = array(1,2,13,3,8,9,10,5); //allowed permissions for this page
	//cleanse all data
	$formfield['fforderid'] = $_POST['orderid'];
	$formfield['fforderitemid'] = $_POST['orderitemid'];
	$formfield['ffmenuid'] = $_POST['menuid'];
	$formfield['fforderitemprice'] = $_POST['orderitemprice'];
	$formfield['fflocation'] = $_SESSION['stafflocid'];
	if ($_POST['orderupdate'] != ''){
		$formfield['ffupdate'] = $_POST['orderupdate'];
		$orderitems = array();
		$sqlselectcount = "SELECT * from orderitems WHERE dborderid = :bvorderid";//SQL string
		$resultcount = $db->prepare($sqlselectcount); //prep statement
		$resultcount->bindvalue(':bvorderid', $formfield['orderid']);
		$resultcount->execute();//execute statement
		$i = 0;
		while($rowcounter = $resultcount->fetch()){
			$orderitems[$i] = $rowcounter['orderitemid'];
			$i += 1;
		}
	}
	//create a Result set for categories
	$sqlselectc = "SELECT * from category";//SQL string
	$resultc = $db->prepare($sqlselectc); //prep statement
	$resultc->execute();//execute statement
	
	if (isset($_POST['OIEnter'])) //when a menu item is chosen
	{
			//create an SQL statement to count current amount of item in order
			$sqlcc = "SELECT * FROM orderitems where dbmenuid = :bvmenuid AND dborderid = :bvorderid";
			$resultcc = $db->prepare($sqlcc); //prepare SQL statement
			//bind values
			$resultcc->bindvalue(':bvmenuid', $formfield['ffmenuid']);
			$resultcc->bindvalue(':bvorderid', $formfield['fforderid']); 
			$resultcc->execute(); //execute prepared statement
			$countinorder = $resultcc->rowCount(); //count returned orders
			
			//create an SQL statement to count current amount of item in inventory
			$sqlic = "SELECT * FROM menu where dbmenuid = :bvmenuid";
			$resultic = $db->prepare($sqlic); //prepare SQL statement
			$resultic->bindvalue(':bvmenuid', $formfield['ffmenuid']);//bind value
			$resultic->execute(); //execute prepared statement
			$rowic = $resultic->fetch();
			$countininventory = $rowic['dbmenuinventory']; //count returned orders
		
			if ($countinorder < $countininventory){
						
				//create an SQL string to insert selected item
				$sqlinsert = 'INSERT INTO orderitems (dborderid, dbmenuid,
					dborderitemprice) VALUES (:bvorderid, :bvmenuid, :bvorderitemprice)';
				$stmtinsert = $db->prepare($sqlinsert); //prepare SQL statement
				//bind values
				$stmtinsert->bindvalue(':bvorderid', $formfield['fforderid']); 
				$stmtinsert->bindvalue(':bvmenuid', $formfield['ffmenuid']);
				$stmtinsert->bindvalue(':bvorderitemprice', $formfield['fforderitemprice']);
				$stmtinsert->execute(); //execute prepared statement
			}else{
				echo' <p>There are no more of this item in stock</p>';
			}
	}

	if (isset($_POST['DeleteItem']))//when the delete item button is pressed
	{
		//SQL string to delete selected option
		$sqldelete = 'DELETE FROM orderitems WHERE dborderitemid = :bvorderitemid';
		$stmtdelete = $db->prepare($sqldelete);//prepare statement
		$stmtdelete->bindvalue(':bvorderitemid', $formfield['fforderitemid']);//bind value
		$stmtdelete->execute();//execute prepared statement
		if ($formfield['ffupdate'] != ''){
			if (in_array($formfield['fforderitemid'], $orderitemsarray)) {
				$sqlupdate = 'UPDATE menu SET dbmenuinventory + 1 WHERE dbmenuid = :bvmenuid AND dblocid = :bvlocation';
				$resultupdate = $db->prepare($sqlupdate);//prepare statement 
				$resultupdate->bindValue(':bvmenuid', $formfield['ffmenuid']);
				$resultupdate->bindValue(':bvlocation', $formfield['fflocation']);
				$resultupdate->execute();//execute prepared statement	
			}
		}
	}
	
	if (isset($_POST['UpdateItem']))//when an item is selected to update
	{
			//cleanse data
		$formfield['fforderitemprice'] = $_POST['newprice'];
		$formfield['fforderitemnotes'] = trim($_POST['newnote']);
		//SQL string to update item selected
		$sqlupdateoi = 'Update orderitems 
					set dborderitemprice = :bvorderitemprice,
						dborderitemnotes = :bvorderitemnotes
					WHERE dborderitemid = :bvorderitemid';
		$stmtupdateoi = $db->prepare($sqlupdateoi);//prepare statement
		//bind values
		$stmtupdateoi->bindvalue(':bvorderitemid', $formfield['fforderitemid']);
		$stmtupdateoi->bindvalue(':bvorderitemprice', $formfield['fforderitemprice']);
		$stmtupdateoi->bindvalue(':bvorderitemnotes', $formfield['fforderitemnotes']);
		$stmtupdateoi->execute();//execute prepared statement
	}
		//SQL string to select all order items
	$sqlselecto = "SELECT orderitems.*, menu.dbmenuname
			FROM orderitems, menu
			WHERE menu.dbmenuid = orderitems.dbmenuid
			AND orderitems.dborderid = :bvorderid";
	$resulto = $db->prepare($sqlselecto);//prepare statement
	$resulto->bindValue(':bvorderid', $formfield['fforderid']);//bind value
	$resulto->execute();//execute prepared statement
	//if has staff permissions
	if ($visible == 1 && in_array($_SESSION['staffloginpermit'], $allowedperms))
	{
?>

<fieldset><legend>Enter Items for Order Number 
		<?php echo $formfield['fforderid'] ;?> </legend>
		
		<table class="ordertable">
			<?php
				echo '<tr>';
				while ($rowc = $resultc->fetch() )//gets all categories
					{
					echo '<th valign = "top" align = "center">' . $rowc['dbcatname'] . '<br>';
					echo '<table class="insideordertable">';
					$sqlselectp = "SELECT * from menu where dbcatid = :bvcatid AND dbmenuactive = 1";//SQL string
					$resultp = $db->prepare($sqlselectp);//prepare statment
					$resultp->bindValue(':bvcatid', $rowc['dbcatid']); //bind value
					$resultp->execute();//execute prepared statement
					while ($rowp = $resultp->fetch() )//gets all items in the category
						{
							//form below are added values to each button
						echo '<tr><td>';
						echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
						echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
						echo '<input type = "hidden" name = "menuid" value = "'. $rowp['dbmenuid'] .'">';
						echo '<input type = "hidden" name = "orderitemprice" value = "'. $rowp['dbmenuprice'] .'">';
						echo '<input type="submit" class="button" name="OIEnter" value="'. $rowp['dbmenuname'] . ' - $' 
							. $rowp['dbmenuprice'] .'">';
						echo '</form>';
						echo '</td></tr>';
						}
					echo '</table></th>';	
						$counter = $counter +1;
					if ($counter >= 3){
						$counter = 0;
						echo '</tr><tr>';
					}	
				}	
echo '</tr>';
?>
</table>
</fieldset>
<br><br>
	<table>
		<tr>
		<td>
		<table border>
			<tr>
				<th>Item</th>
				<th>Price</th>
				<th>Notes</th>
				<th></th>
				<th></th>
			</tr>
<?php
	while ($rowo = $resulto->fetch() )  //this adds buttons to each item in order to update and delete
	{
	echo '<tr><td>' . $rowo['dbmenuname'] . '</td><td>' . $rowo['dborderitemprice'] . '</td>';
	echo '<td>' . $rowo['dborderitemnotes'] . '</td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
	echo '<input type = "hidden" name = "orderitemid" value = "'. $rowo['dborderitemid'] .'">';
	echo '<input type="submit" class="button" name="NoteEntry" value="Update">';
	echo '</form></td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
	echo '<input type = "hidden" name = "orderitemid" value = "'. $rowo['dborderitemid'] .'">';
	echo '<input type = "hidden" name = "menuid" value = "'. $rowo['dbmenuid'] .'">';
	echo '<input type="submit" class="button" name="DeleteItem" value="Delete">';
	echo '</form></td></tr>';
	}
?>
</table>
		<?php
			if (isset($_POST['NoteEntry']))  //this is for simple note entry
			{
			$sqlselectoi = "SELECT orderitems.*, menu.dbmenuname 
				from orderitems, menu
				WHERE menu.dbmenuid = orderitems.dbmenuid
				AND orderitems.dborderitemid = :bvorderitemid";
			$resultoi = $db->prepare($sqlselectoi);  //prepare statement  
			$resultoi->bindvalue(':bvorderitemid', $_POST['orderitemid']);//bindvalues
			$resultoi->execute(); //execute prepared statement
			$rowoi = $resultoi->fetch();  //retrieve all data returned from statement
			
	echo '</td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<table>';
	echo '<tr><td>Price: <input type = "text" name = "newprice" value = "'. $rowoi['dborderitemprice'] . '"></td></tr>';
	echo '<tr><td>Note: <input type = "text" name = "newnote" value = "'. $rowoi['dborderitemnotes'] . '"></td></tr>';
	echo '<tr><td>';
	echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
	echo '<input type = "hidden" name = "orderitemid" value = "'. $rowoi['dborderitemid'] .'">';
	echo '<input type="submit" class="button" name="UpdateItem" value="Update Item"></form></td></tr></table>';
	}
	?>
		
		</td></tr>
	</table>
	<br><br>
<?php
	echo '<form action = "completeorder.php" method = "post">';
	echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
	if($formfield['orderupdate'] != ''){
		echo '<input type = "hidden" name = "uporderitems" value = "'. serialize($orderitems) .'">';
	}
	echo '<input type="submit" class="button" name="CompleteOrder" value="Complete Order">';
	echo '</form>';

}//visible
include_once 'footer.php';//include to footer once
?>