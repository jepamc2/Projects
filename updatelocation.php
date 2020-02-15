<?php
//Developer(s): Joshua Mercer
//Date: 3/27/2017
//Purpose: This allows the user to update locations
$pagetitle = "Update Locations"; //page title
require_once "header.php"; //require the header file
require_once "connect.php"; //require the connection file
	//declare variables 
	$errormsg = "";
	$showform = 1; 
	$allowedperms = array(13); //allowed permissions for this page

			$formfield['fflocid'] = $_POST['locid']; //set location id
			$sqlselect = 'SELECT * from locations where dblocid = :bvlocid'; //create an SQL string to select locations
			$result = $db->prepare($sqlselect); //prepare statement
			$result->bindValue(':bvlocid', $formfield['fflocid']); //bind parameters
			$result->execute(); //execute query
			$row = $result->fetch(); //fetch result set
			
			$sqlselectl = 'SELECT * from locations where dblocid = :bvlocid'; //create an SQL string to select locations
			$resultl = $db->prepare($sqlselectl); //prepare statement
			$resultl->bindValue(':bvlocid', $formfield['fflocid']); //bind parameters
			$resultl->execute(); //execute query

		if( isset($_POST['thesubmit']) ) //if the user submits
		{	
			$showform = 2; //show 2nd form
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['fflocid'] = $_POST['locid'];
			$formfield['ffname'] = trim($_POST['name']);

			if(empty($formfield['ffname'])){$errormsg .= "<p>Your customer name field is empty.</p>";} //error message

			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				try
				{
					$sqlinsert = 'UPDATE locations SET dblocname = :bvname WHERE dblocid = :bvlocid'; //UPDATE sql string
					$stmtinsert = $db->prepare($sqlinsert); //prepare statement
					$stmtinsert->bindvalue(':bvname', $formfield['ffname']); //bind parameters
					$stmtinsert->bindvalue(':bvlocid', $formfield['fflocid']);
					$stmtinsert->execute(); //execute query
					echo "<p>There are no errors.  Thank you.</p>";
				}
				catch(PDOException $e) //if exception
				{
					echo $e->getMessage(); //print exception
					exit(); //exit
				}
			}
		}

	if ($showform == 1 && in_array($_SESSION['staffloginpermit'], $allowedperms)) //form 1
	{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Location Info</legend>
				<table border>
					<tr>
						<th>Location Name</th>
						<td><input type="text" name="name" id="name"
						value = "<?php echo $row['dblocname']; ?>"	></td>
					</tr>
				</table>
				<input type="hidden" name = "locid" value=<?php echo $formfield['fflocid'] ?>>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2 && ($_SESSION['staffloginpermit'] == 13)) { //form 2
	?>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Location Info</legend>
				<table border>
					<tr>
						<th>Location Name</th>
						<td><input type="text" name="name" id="name"
						value = "<?php echo $formfield['ffname']; ?>"	></td>
					</tr>
				</table>
				<input type="hidden" name = "locid" value=<?php echo $formfield['fflocid'] ?>>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
					<br><br>
	<table border>
	<tr>
		<th>Location Name</th> 
	</tr>
	<?php 
		while ( $rowl = $resultl-> fetch() ) //print result set
			{
				echo '<tr><td>' . $rowl['dblocname'] . '</td></tr> '; //print location name
			}
		?>
	</table>
	<?php
		}
		else {
		echo "You do not have permission to update"; //let user know they don't have perms
		}
include_once 'footer.php'; //attempt to include footer file
?>