<?php
//Developer(s): Jeremy McGuire
//Date: 4/11/2017
//Purpose: This allows the user to update Menu
//Edited by: Joshua Mercer
$pagetitle = "Operation Hours"; //page title
require_once "header.php"; //require the header file
require_once "connect.php"; //require the connection file
	//declare variables 
	$errormsg = "";
	$showform = 1; 
	$allowedperms = array(13,1); //allowed permissions for this page
	
			$formfield['fflocation'] = $_SESSION['stafflocid'];
			
			
			$sqlselectm = 'SELECT * from operationhours where dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';
			$resultm = $db->prepare($sqlselectm);
			$resultm->bindValue(':bvdayofweek', "Monday");
			$resultm->bindValue(':bvlocation', $formfield['fflocation']);
			$resultm->execute(); 
			$rowm = $resultm->fetch(); //Binds vslues for Monday and Location
			
			$sqlselectt = 'SELECT * from operationhours where dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';
			$resultt = $db->prepare($sqlselectm);
			$resultt->bindValue(':bvdayofweek', "Tuesday");
			$resultt->bindValue(':bvlocation', $formfield['fflocation']);
			$resultt->execute();
			$rowt = $resultt->fetch(); //Binds vslues for Tuesday and Location
			
			$sqlselectw = 'SELECT * from operationhours where dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';
			$resultw = $db->prepare($sqlselectm);
			$resultw->bindValue(':bvdayofweek', "Wednesday");
			$resultw->bindValue(':bvlocation', $formfield['fflocation']);
			$resultw->execute();
			$roww = $resultw->fetch(); //Binds vslues for Wednesday and Location
			
			$sqlselectr = 'SELECT * from operationhours where dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';
			$resultr = $db->prepare($sqlselectm);
			$resultr->bindValue(':bvdayofweek', "Thursday");
			$resultr->bindValue(':bvlocation', $formfield['fflocation']);
			$resultr->execute();
			$rowr = $resultr->fetch(); //Binds vslues for Thursday and Location
			
			$sqlselectf = 'SELECT * from operationhours where dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';
			$resultf = $db->prepare($sqlselectm);
			$resultf->bindValue(':bvdayofweek', "Friday");
			$resultf->bindValue(':bvlocation', $formfield['fflocation']);
			$resultf->execute();
			$rowf = $resultf->fetch(); //Binds vslues for Friday and Location
			
			$sqlselectsa = 'SELECT * from operationhours where dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';
			$resultsa = $db->prepare($sqlselectm);
			$resultsa->bindValue(':bvdayofweek', "Saturday");
			$resultsa->bindValue(':bvlocation', $formfield['fflocation']);
			$resultsa->execute();
			$rowsa = $resultsa->fetch(); //Binds vslues for Saturday and Location
			
			$sqlselectsu = 'SELECT * from operationhours where dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';
			$resultsu = $db->prepare($sqlselectm);
			$resultsu->bindValue(':bvdayofweek', "Sunday");
			$resultsu->bindValue(':bvlocation', $formfield['fflocation']);
			$resultsu->execute();
			$rowsu = $resultsu->fetch(); //Binds vslues for Sunday and Location
	
		if( isset($_POST['thesubmit']) ) //if the user submits
		{	
			$showform = 2; //show 2nd form
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffdayofweek'] = "Monday";
			$formfield['ffstarttime'] = $_POST['starttime1'];
			$formfield['ffstoptime'] = $_POST['stoptime1'];

			if(empty($formfield['ffstarttime'])){$errormsg .= "<p>Start time is empty.</p>";}
			if(empty($formfield['ffstoptime'])){$errormsg .= "<p>Stop time is empty.</p>";}
	
			
			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				try
				{
					$sqlupdatem = 'UPDATE operationhours SET dbopopen = :bvstarttime, dbopclose = :bvstoptime WHERE dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';//UPDATE sql string
					$stmtupdatem = $db->prepare($sqlupdatem); //prepare statement
					$stmtupdatem->bindvalue(':bvdayofweek', $formfield['ffdayofweek']);
					$stmtupdatem->bindvalue(':bvstarttime', $formfield['ffstarttime']);
					$stmtupdatem->bindvalue(':bvstoptime', $formfield['ffstoptime']);
					$stmtupdatem->bindvalue(':bvlocation', $formfield['fflocation']);
					$stmtupdatem->execute(); //execute query
					//Updates the start and stop times in the operationhours table for Monday at select location
				}
				catch(PDOException $e) //if exception
				{
					echo $e->getMessage(); //print exception
					exit(); //exit
				}
			}
			
			//
			//
			//Data Cleansing
			$formfield['ffdayofweek'] = "Tuesday";
			$formfield['ffstarttime'] = $_POST['starttime2'];
			$formfield['ffstoptime'] = $_POST['stoptime2'];

			if(empty($formfield['ffstarttime'])){$errormsg .= "<p>Start time is empty.</p>";}
			if(empty($formfield['ffstoptime'])){$errormsg .= "<p>Stop time is empty.</p>";}
	
			
			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				try
				{
					$sqlupdatet = 'UPDATE operationhours SET dbopopen = :bvstarttime, dbopclose = :bvstoptime WHERE dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';//UPDATE sql string
					$stmtupdatet = $db->prepare($sqlupdatet); //prepare statement
					$stmtupdatet->bindvalue(':bvdayofweek', $formfield['ffdayofweek']);
					$stmtupdatet->bindvalue(':bvstarttime', $formfield['ffstarttime']);
					$stmtupdatet->bindvalue(':bvstoptime', $formfield['ffstoptime']);
					$stmtupdatet->bindvalue(':bvlocation', $formfield['fflocation']);
					$stmtupdatet->execute(); //execute query
					//Updates the start and stop times in the operationhours table for Tuesday at select location
				}
				catch(PDOException $e) //if exception
				{
					echo $e->getMessage(); //print exception
					exit(); //exit
				}
			}
			
			//
			//
			//Data Cleansing
			$formfield['ffdayofweek'] = "Wednesday";
			$formfield['ffstarttime'] = $_POST['starttime3'];
			$formfield['ffstoptime'] = $_POST['stoptime3'];

			if(empty($formfield['ffstarttime'])){$errormsg .= "<p>Start time is empty.</p>";}
			if(empty($formfield['ffstoptime'])){$errormsg .= "<p>Stop time is empty.</p>";}
	
			
			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				try
				{
					$sqlupdatew = 'UPDATE operationhours SET dbopopen = :bvstarttime, dbopclose = :bvstoptime WHERE dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';//UPDATE sql string
					$stmtupdatew = $db->prepare($sqlupdatew); //prepare statement
					$stmtupdatew->bindvalue(':bvdayofweek', $formfield['ffdayofweek']);
					$stmtupdatew->bindvalue(':bvstarttime', $formfield['ffstarttime']);
					$stmtupdatew->bindvalue(':bvstoptime', $formfield['ffstoptime']);
					$stmtupdatew->bindvalue(':bvlocation', $formfield['fflocation']);
					$stmtupdatew->execute(); //execute query
					//Updates the start and stop times in the operationhours table for Wednesday at select location
				}
				catch(PDOException $e) //if exception
				{
					echo $e->getMessage(); //print exception
					exit(); //exit
				}
			}
			
			//
			//
			//Data Cleansing
			$formfield['ffdayofweek'] = "Thursday";
			$formfield['ffstarttime'] = $_POST['starttime4'];
			$formfield['ffstoptime'] = $_POST['stoptime4'];

			if(empty($formfield['ffstarttime'])){$errormsg .= "<p>Start time is empty.</p>";}
			if(empty($formfield['ffstoptime'])){$errormsg .= "<p>Stop time is empty.</p>";}
	
			
			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				try
				{
					$sqlupdater = 'UPDATE operationhours SET dbopopen = :bvstarttime, dbopclose = :bvstoptime WHERE dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';//UPDATE sql string
					$stmtupdater = $db->prepare($sqlupdater); //prepare statement
					$stmtupdater->bindvalue(':bvdayofweek', $formfield['ffdayofweek']);
					$stmtupdater->bindvalue(':bvstarttime', $formfield['ffstarttime']);
					$stmtupdater->bindvalue(':bvstoptime', $formfield['ffstoptime']);
					$stmtupdater->bindvalue(':bvlocation', $formfield['fflocation']);
					$stmtupdater->execute(); //execute query
					//Updates the start and stop times in the operationhours table for Thursday at select location
				}
				catch(PDOException $e) //if exception
				{
					echo $e->getMessage(); //print exception
					exit(); //exit
				}
			}
			
			//
			//
			//Data Cleansing
			$formfield['ffdayofweek'] = "Friday";
			$formfield['ffstarttime'] = $_POST['starttime5'];
			$formfield['ffstoptime'] = $_POST['stoptime5'];

			if(empty($formfield['ffstarttime'])){$errormsg .= "<p>Start time is empty.</p>";}
			if(empty($formfield['ffstoptime'])){$errormsg .= "<p>Stop time is empty.</p>";}
	
			
			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				try
				{
					$sqlupdatef = 'UPDATE operationhours SET dbopopen = :bvstarttime, dbopclose = :bvstoptime WHERE dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';//UPDATE sql string
					$stmtupdatef = $db->prepare($sqlupdatef); //prepare statement
					$stmtupdatef->bindvalue(':bvdayofweek', $formfield['ffdayofweek']);
					$stmtupdatef->bindvalue(':bvstarttime', $formfield['ffstarttime']);
					$stmtupdatef->bindvalue(':bvstoptime', $formfield['ffstoptime']);
					$stmtupdatef->bindvalue(':bvlocation', $formfield['fflocation']);
					$stmtupdatef->execute(); //execute query
					//Updates the start and stop times in the operationhours table for Friday at select location
				}
				catch(PDOException $e) //if exception
				{
					echo $e->getMessage(); //print exception
					exit(); //exit
				}
			}
			
			//
			//
			//Data Cleansing
			$formfield['ffdayofweek'] = "Saturday";
			$formfield['ffstarttime'] = $_POST['starttime6'];
			$formfield['ffstoptime'] = $_POST['stoptime6'];

			if(empty($formfield['ffstarttime'])){$errormsg .= "<p>Start time is empty.</p>";}
			if(empty($formfield['ffstoptime'])){$errormsg .= "<p>Stop time is empty.</p>";}
	
			
			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				try
				{
					$sqlupdatesa = 'UPDATE operationhours SET dbopopen = :bvstarttime, dbopclose = :bvstoptime WHERE dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';//UPDATE sql string
					$stmtupdatesa = $db->prepare($sqlupdatesa); //prepare statement
					$stmtupdatesa->bindvalue(':bvdayofweek', $formfield['ffdayofweek']);
					$stmtupdatesa->bindvalue(':bvstarttime', $formfield['ffstarttime']);
					$stmtupdatesa->bindvalue(':bvstoptime', $formfield['ffstoptime']);
					$stmtupdatesa->bindvalue(':bvlocation', $formfield['fflocation']);
					$stmtupdatesa->execute(); //execute query
					//Updates the start and stop times in the operationhours table for Saturday at select location
				}
				catch(PDOException $e) //if exception
				{
					echo $e->getMessage(); //print exception
					exit(); //exit
				}
			}
			
			//
			//
			//Data Cleansing
			$formfield['ffdayofweek'] = "Sunday";
			$formfield['ffstarttime'] = $_POST['starttime7'];
			$formfield['ffstoptime'] = $_POST['stoptime7'];

			if(empty($formfield['ffstarttime'])){$errormsg .= "<p>Start time is empty.</p>";}
			if(empty($formfield['ffstoptime'])){$errormsg .= "<p>Stop time is empty.</p>";}
	
			
			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				try
				{
					$sqlupdatesu = 'UPDATE operationhours SET dbopopen = :bvstarttime, dbopclose = :bvstoptime WHERE dbopdayofweek = :bvdayofweek AND dblocid = :bvlocation';//UPDATE sql string
					$stmtupdatesu = $db->prepare($sqlupdatesu); //prepare statement
					$stmtupdatesu->bindvalue(':bvdayofweek', $formfield['ffdayofweek']);
					$stmtupdatesu->bindvalue(':bvstarttime', $formfield['ffstarttime']);
					$stmtupdatesu->bindvalue(':bvstoptime', $formfield['ffstoptime']);
					$stmtupdatesu->bindvalue(':bvlocation', $formfield['fflocation']);
					$stmtupdatesu->execute(); //execute query
					//Updates the start and stop times in the operationhours table for Sunday at select location
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
			<fieldset><legend>Opening and Closing </legend>
				<table border>
					<tr>
						<th>Monday:</th>
						<td><input type="time" name="starttime1" id="starttime1"
						value = "<?php echo $rowm['dbopopen']; ?>">
						<input type="time" name="stoptime1" id="stoptime1"
						value = "<?php echo $rowm['dbopclose']; ?>"></td>
					</tr>
					<tr>
						<th>Tuesday:</th>
						<td><input type="time" name="starttime2" id="starttime2"
						value = "<?php echo $rowt['dbopopen']; ?>">
						<input type="time" name="stoptime2" id="stoptime2"
						value = "<?php echo $rowt['dbopclose']; ?>"></td>
					</tr>
					<tr>
						<th>Wednesday: </th>
						<td><input type="time" name="starttime3" id="starttime3"
						value = "<?php echo $roww['dbopopen']; ?>">
						<input type="time" name="stoptime3" id="stoptime3"
						value = "<?php echo $roww['dbopclose']; ?>"></td>
					</tr>
					<tr>
						<th>Thursday: </th>
						<td><input type="time" name="starttime4" id="starttime4"
						value = "<?php echo $rowr['dbopopen']; ?>">
						<input type="time" name="stoptime4" id="stoptime4"
						value = "<?php echo $rowr['dbopclose']; ?>"></td>
					</tr>
					<tr>
						<th>Friday: </th>
						<td><input type="time" name="starttime5" id="starttime5"
						value = "<?php echo $rowf['dbopopen']; ?>">
						<input type="time" name="stoptime5" id="stoptime5"
						value = "<?php echo $rowf['dbopclose']; ?>"></td>
					</tr>
					<tr>
						<th>Saturday: </th>
						<td><input type="time" name="starttime6" id="starttime6"
						value = "<?php echo $rowsa['dbopopen']; ?>">
						<input type="time" name="stoptime6" id="stoptime6"
						value = "<?php echo $rowsa['dbopclose']; ?>"></td>
					</tr>
					<tr>
						<th>Sunday: </th>
						<td><input type="time" name="starttime7" id="starttime7"
						value = "<?php echo $rowsu['dbopopen']; ?>">
						<input type="time" name="stoptime7" id="stoptime7"
						value = "<?php echo $rowsu['dbopclose']; ?>"></td>
					</tr>				
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
		<table border>
	<tr>
		<th>Day of Week</th>
		<th>Start Time</th>
		<th>End Time</th>
	</tr>
	<?php 
	
		$sqlselectc = 'SELECT * FROM operationhours WHERE dblocid = :bvlocation '; //create an SQL string to select locations
		$resultc = $db->prepare($sqlselectc); //prepare statement
		$resultc->bindValue(':bvlocation', $formfield['fflocation']);
		$resultc->execute(); //execute query
		
		while ( $rowc = $resultc-> fetch() )
			{
								
				
			
				echo '<tr><td> ' . $rowc['dbopdayofweek'] .
				'</td><td> ' . date('h:i a',strtotime($rowc['dbopopen'])) . 
				'</td><td> ' . date('h:i a',strtotime($rowc['dbopclose'])). '</td></tr>';
			}
		?>
	</table>
	<?php
		}
		
include_once 'footer.php'; //attempt to include footer file
?>