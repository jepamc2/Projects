<?php
//Developer(s): Joshua Mercer
//Date: 3/18/2017
//Purpose: This is order system for the customer
	require_once "orderheader.php"; //require header file
	require_once "connect.php"; //require connection file 
	//cleanse all data
	$formfield['fforderid'] = $_POST['orderid'];
	$formfield['fforderitemid'] = $_POST['orderitemid'];
	$formfield['ffmenuid'] = $_POST['menuid'];
	$formfield['fforderitemprice'] = $_POST['orderitemprice'];
	
	echo '<div class="bigpaper">';
	echo '<center>';
	
	//create a Result set for categories
	$sqlselectc = "SELECT * from category"; //SQL string
	$resultc = $db->prepare($sqlselectc); //prep statement
	$resultc->execute(); //execute statement
	
	//create an SQL string to get the location of the order for inventory control
	$sqllocation = 'SELECT * FROM orders WHERE dborderid = :bvorderid';
	$resultlocation = $db->prepare($sqllocation); //prep statement
	$resultlocation->bindvalue(':bvorderid', $formfield['fforderid']); //bind values
	$resultlocation->execute(); //execute statement
	$rowlocation = $resultlocation->fetch(); //fetch data from result set
	$formfield['fflocation'] = $rowlocation['dblocid']; //set formfield for location
	
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
		$sqlic = "SELECT * FROM menu where dbmenuid = :bvmenuid AND dblocid = :bvlocid";
		$resultic = $db->prepare($sqlic); //prepare SQL statement
		$resultic->bindvalue(':bvmenuid', $formfield['ffmenuid']);//bind values
		$resultic->bindvalue(':bvlocid', $formfield['fflocation']);
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
			echo' <h4>There are no more of this item in stock</h4>';
		}
		
	}

	if (isset($_POST['DeleteItem']))//when the delete item button is pressed
	{
		//SQL string to delete selected option
		$sqldelete = 'DELETE FROM orderitems  
					  WHERE dborderitemid = :bvorderitemid';
		$stmtdelete = $db->prepare($sqldelete); //prepare statement
		$stmtdelete->bindvalue(':bvorderitemid', $formfield['fforderitemid']); //bind vlaue
		$stmtdelete->execute(); //execute prepared statement
	}
	
	if (isset($_POST['UpdateItem'])) //when an item is selected to update
	{
		//cleanse data
		$formfield['fforderitemnotes'] = trim($_POST['newnote']);
		//SQL string to update item selected
		$sqlupdateoi = 'Update orderitems 
					set dborderitemnotes = :bvorderitemnotes
					WHERE dborderitemid = :bvorderitemid';
		$stmtupdateoi = $db->prepare($sqlupdateoi); //prepare statement
		//bind values
		$stmtupdateoi->bindvalue(':bvorderitemid', $formfield['fforderitemid']);
		//$stmtupdateoi->bindvalue(':bvorderitemprice', $formfield['fforderitemprice']);
		$stmtupdateoi->bindvalue(':bvorderitemnotes', $formfield['fforderitemnotes']);
		$stmtupdateoi->execute(); //execute prepared statement
	}
	//SQL string to select all order items
	$sqlselecto = "SELECT orderitems.*, menu.dbmenuname
			FROM orderitems, menu
			WHERE menu.dbmenuid = orderitems.dbmenuid
			AND orderitems.dborderid = :bvorderid";
	$resulto = $db->prepare($sqlselecto); //prepared statement
	$resulto->bindValue(':bvorderid', $formfield['fforderid']); //bind values
	$resulto->execute(); //execute prepared statement 
	
	if ($_SESSION['custloginpermit'] == 12) //if has customer permissions
	{
?>
<br><br>
<h6>Enter Items for Order Number: <?php echo $formfield['fforderid'] ;?></h6>
		<br><br><br><br>
	
		<br><br>
	<table class="totaltable">
		<tr>
		<td>
		<table>
			<tr>
				<th>Item</th>
				<th>Price</th>
				<th>Notes</th>
				<th></th>
				<th></th>
			</tr>
<?php
	while ($rowo = $resulto->fetch() ) //this adds buttons to each item in order to update and delete
	{
	echo '<tr><td>' . $rowo['dbmenuname'] . '</td><td>' . $rowo['dborderitemprice'] . '</td>';
	echo '<td>' . $rowo['dborderitemnotes'] . '</td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
	echo '<input type = "hidden" name = "orderitemid" value = "'. $rowo['dborderitemid'] .'">';
	echo '<input type="submit" name="NoteEntry" value="Add Note" class="button">';
	echo '</form></td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
	echo '<input type = "hidden" name = "orderitemid" value = "'. $rowo['dborderitemid'] .'">';
	echo '<input type="submit" name="DeleteItem" value="Delete" class="button">';
	echo '</form></td></tr>';
	}
?>
</table>
		<?php
			if (isset($_POST['NoteEntry'])) //this is for simple note entry
			{
			//SQL string 
			$sqlselectoi = "SELECT orderitems.*, menu.dbmenuname 
				from orderitems, menu
				WHERE menu.dbmenuid = orderitems.dbmenuid
				AND orderitems.dborderitemid = :bvorderitemid";
			$resultoi = $db->prepare($sqlselectoi); //prepare statement  
			$resultoi->bindvalue(':bvorderitemid', $_POST['orderitemid']); //bindvalues
			$resultoi->execute(); //execute prepared statement
			$rowoi = $resultoi->fetch(); //retrieve all data returned from statement
		
		//the following block of code is to allow the user to add notes to their items
	echo '</td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<table class="totaltable">';
	echo '<tr><td>Note: <input type = "text" name = "newnote" value = "'. $rowoi['dborderitemnotes'] . '"></td></tr>';
	echo '<tr><td>';
	echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
	echo '<input type = "hidden" name = "orderitemid" value = "'. $rowoi['dborderitemid'] .'">';
	echo '<input type="submit" name="UpdateItem" value="Confirm Note" class="button"></form></td></tr></table>';
	}
	?>
		
		</td></tr>
	</table>
	<br><br>
<?php
	echo '<form action = "completeorder.php" method = "post">';
	echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
	echo '<input type="submit" name="CompletOrder" value="Complete Order" class="button">';
	echo '<br><br>';
	echo '</form>';

}//visible
?>
	
		<table border class="ordertable"> <!-- table 1 -->
			<?php

				while ($rowc = $resultc->fetch() ) //gets all categories
				{
				echo '<tr>';
				echo '<th valign = "top" align = "center">' . $rowc['dbcatname'] . '<br>';
				echo '<table border class="insideordertable">';

				$sqlselectp = "SELECT * from menu where dbcatid = :bvcatid AND dbmenuactive = 1"; //SQL string
				$resultp = $db->prepare($sqlselectp); //prepare statment
				$resultp->bindValue(':bvcatid', $rowc['dbcatid']); //bind value
				$resultp->execute(); //execute prepared statement
				
				while ($rowp = $resultp->fetch() ) //gets all items in the category
					{
						//form below are added values to each button
						echo '<tr><td>' . $rowp['dbmenuname'] . '</td><td>' . $rowp['dbmenudescr'] . ' - $' . $rowp['dbmenuprice'] . '</td><td>';
						echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
						echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
						echo '<input type = "hidden" name = "menuid" value = "'. $rowp['dbmenuid'] .'">';
						echo '<input type = "hidden" name = "orderitemprice" value = "'. $rowp['dbmenuprice'] .'">';
						echo '<input type="submit" name="OIEnter" class="orderbutton" value="'. ''.'">';
						echo '</form>';
						echo '</td></tr>';
					}
				echo '</table></th>';	
				echo '</tr>';
		}


echo '</table><br><br>';

echo "</div>";
echo "</center>";
include_once 'footer.php'; //include to footer once
?>