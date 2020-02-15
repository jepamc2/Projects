<?php
//Developer(s): Joshua Mercer
//Date: 3/18/2017
//Purpose: This is order system for the customer
$pagetitle = 'Insert Order';  //set page title
require_once "orderheader.php"; //require the header file
require_once "connect.php";//require the connect file
$showform = 1;
$checkorder = 1;

	$sqlloc = 'SELECT * FROM locations';
	$resultloc = $db->prepare($sqlloc); //prepare statement
	$resultloc->execute(); //execute the prepared statement

	echo '<div class="paper">';
	echo '<center>';

	if (isset($_POST['thesubmit']) )	
	{	
		//see form fields to the posted variables
		$formfield['ffordertype'] = $_POST['ordertype'];
		$formfield['fflocation'] = $_POST['loc'];
		
		//ERROR Checking 
		if(empty($formfield['ffordertype'])) {
			$errormsg .= "<br><h4>You Have not Selected a Delivery Option</h4>";
		}
		if(empty($formfield['fflocation'])) {
			$errormsg .= "<br><h4>You Have not Selected a Location</h4>";
		}
		
		//this code below will get the julian day of the week
		$jd = cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));
		$day = (jddayofweek($jd,1));
		
		//create an SQL statement to select location hours
		$sqlhours = 'SELECT * FROM operationhours WHERE dblocid = :bvlocid AND dbopdayofweek = :bvday';
		$resulthours = $db->prepare($sqlhours); //prepare statement
		$resulthours->bindvalue(':bvlocid', $formfield['fflocation']);
		$resulthours->bindvalue(':bvday', $day);
		$resulthours->execute(); //execute the prepared statement
		$rowhours = $resulthours->fetch();
		
		$now = date("H:i:s"); //create a time of now
		//compare now to open hours
		if($now >= $rowhours['dbopopen'] && $now <= $rowhours['dbopclose']){
		}else{$errormsg .= "<h4>Sorry, but this location is currently closed.</h4>";}			
	
		if ($errormsg != "") { //output any errors
			echo '<br><br><h4 class="error">'.$errormsg.'</h4>';
		} 
		else
		{		
			//create an sql string to get maxid from the orders
			$sqlmax = "SELECT MAX(dborderid) AS maxid from orders"; 
			$resultmax = $db->prepare($sqlmax); //prepare the statement
			$resultmax->execute(); //execute the statement 
			$rowmax = $resultmax->fetch(); //fetch the result set
			$maxid = $rowmax["maxid"]; //set var max id to returned value
			$maxid = $maxid + 1; //increment max id
			//create an sql string to create a new order using the new max id
			$sqlinsert = 'INSERT INTO orders  
				(dborderid, dbstaffid, dbcustid, dborderdate, dbordercomplete, dbordertypeid, dbtableid, dbordermade,dblocid) 
				VALUES (:bvorderid, 22, :bvcustid, now(),0,:bvordertype,0,0,:bvlocid)';
			$stmtinsert = $db->prepare($sqlinsert); //prepare statement
			//bind all placeholder values to vars
			$stmtinsert->bindvalue(':bvorderid', $maxid);
			$stmtinsert->bindvalue(':bvcustid', $_SESSION['custloginid']);
			$stmtinsert->bindvalue(':bvordertype', $formfield['ffordertype']);
			$stmtinsert->bindvalue(':bvlocid', $formfield['fflocation']);
			$stmtinsert->execute(); //execute statement
			

			$showform = 3;//set show form to 3
			$checkorder = 2; //don't check order on submit
		}
	}
	
	if ($checkorder == 1){
		//create a SQL statement that will retrieve the current customers open orders
		$sqlselect = 'SELECT orders.dborderid, customers.dbcustfirstname, orders.dborderdate, orders.dbordercomplete
				FROM orders, customers
				WHERE orders.dbcustid = :bvcustid
				AND orders.dbcustid = customers.dbcustid
				AND orders.dbordercomplete = :bvopen';
		$result = $db->prepare($sqlselect); //prepare statement
		//bind all placeholder values to vars
		$result->bindvalue(':bvcustid', $_SESSION['custloginid']);
		$result->bindvalue(':bvopen', 0);
		$result->execute(); //execute the prepared statement
		$countopen = $result->rowCount(); //count returned orders
		
		if ($countopen > 0){ //if they have any existing orders set form var to redirect them
			$showform = 2; //set form to show open order
		}else {$showform = 1;} //else show normal order start
	}
	//check for account email confirmation
	if($_SESSION['custemailconfirm'] = 0){
		$showform = 3;
	}

if ($showform == 1 && $_SESSION['custloginpermit'] == 12) //check form and session vars to confirm logged in
{
	//code below in html is to allow the user to choose the delvery type and location
?>
<center>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		<br><br><br><br><br>
		<table border>
					<tr>
						<!-- Allow the user to choose a delivery type-->
						<th>Pick a Delivery Type:</th>
						<td><input type="radio" name="ordertype" id="ordertype" 
									value=1 <?php echo ' checked';?> />
							<label for="pickup">Pickup</label>
							<input type="radio" name="ordertype" id="ordertype" 
									value=2 />
									<label for="delivery">Delivery</label>
							</td>
					</tr>
					<tr>
					<!-- Allow the user to choose a location-->
						<th><label>Location:</label></th>
						<td><select name="loc" id="loc">
						<option value="">Please Select a Location</option>
						<?php while ($rowloc = $resultloc-> fetch()){
							if ($rowloc['dblocid'] == $formfield['fflocation']){ 
								$checker ='selected';
							}else {$checker = '';}
							echo '<option value="' . $rowloc['dblocid'] . '" ' .$checker . '>' . $rowloc['dblocname'] . '</option>';
 						}
						?>	
						</select>
						</td>
					</tr>
		</table>
		<br><br>
		<input type="submit" name="thesubmit" value="Enter" class="button">
	</form>
			<br><br>
	</center>
	<br><br><br><br><br>
<?php
}else if ($showform == 2 && $_SESSION['custloginpermit'] == 12){ //if the user has existing orders
	?>
	<br><br><br><br><br>
	<h4>Incomplete Orders Exist, Please Complete Before Starting a New Order</h4>
	<br><br><br>
	<table border>
	<tr>
		<th>Order ID</th>
		<th>Location</th>
		<th>Date and Time</th>
		<th>Order Open</th>
		<th>Total</th>
		<th></th>
	</tr>
	<?php 
	//loop thought the result set to output all order 
		while ( $row = $result-> fetch() )
			{
				//this will help the user easily understand order open by denoting it with words instead of 1s and 0s
				if($row['dbordercomplete'] == 1) { //1 is yes
					$open = "YES"; //set corresponding word
				} else { //0 is no
					$open = "NO"; //set corresponding word
				}
				
				//create an sql string to get all the order items from the selected order
				$sqlselectoi = "SELECT * FROM orderitems
					WHERE dborderid = :bvorderid";
					$resultoi = $db->prepare($sqlselectoi); //prepare the statement
					$resultoi->bindValue(':bvorderid', $row['dborderid']); //bind the placeholder values to var
					$resultoi->execute(); //execute prepared statement
				
				$ordertotal = 0; //set current orders total to 0
				while ( $rowoi = $resultoi-> fetch() ) { //loop through the order and add up its total price
					$ordertotal = $ordertotal + $rowoi['dborderitemprice'];
				}
				//create an SQL string to get the location that the order belongs to
				$sqllocation = 'SELECT * FROM locations WHERE dblocid = :bvlocid';
				$resultlocation = $db->prepare($sqllocation); //prepare the statement
				$resultlocation->bindValue(':bvlocid', $row['dblocid']); //bind the placeholder values to var
				$resultlocation->execute(); //execute prepared statement
				$rowlocation = $resultlocation-> fetch(); //fetch data from result set
				
				
				//fill table with open order information
				echo '<tr><td>' . $row['dborderid'] . '</td><td> ' . $rowlocation['dblocname'] . '</td><td> ' . $row['dborderdate'] . '</td><td> '  . $open . 
				'</td><td> ' . $ordertotal . '</td><td> ' .
				'<form action="insertorderitem.php" method = "post">
				<input type = "hidden" name = "orderid" value = "'
						. $row['dborderid'] .
				'"><input type="submit" name="thesubmit" value="Finalize Order" class="button">
				</form>'. '</td></tr>';
			}
				echo '</table>';

}else if ($showform == 3 && $_SESSION['custloginpermit'] == 12){ //if the user has existing orders
			
			echo "<br><br><br><br><br><br><h5>Order Number: " . $maxid; //output order number
			echo '<br><br></h5><form action="insertorderitem.php" method = "post" id="nextpage">'; //set form action to redirect to insert order item page
			echo '<input type = "hidden" name = "orderid" value = "'. $maxid .'">'; //pass the max id as a hidden values
			echo '<input type="submit" name="thesubmit" value="Enter Order Items" class="button">'; //submit button action
			echo "</form>";
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
			?>
			<script type="text/javascript">document.getElementById('nextpage').submit();</script>
			<?php

}else{
		echo '<h4>Please confirm your email before ordering.</h4>'; //output if the user tries to order outside the order hours
	}
echo '</center>';
echo '</div>';
include_once 'footer.php';
?>