<?php
//Developer(s): Joshua Mercer
//Date: 3/27/2017
//Purpose: This allows the user to update locations
$pagetitle = "Update Category"; //page title
require_once "header.php"; //require the header file
require_once "connect.php"; //require the connection file
	//declare variables 
	$errormsg = "";
	$showform = 1; 
	$allowedperms = array(1,2,13,3); //allowed permissions for this page
	
			$formfield['ffcatid'] = $_POST['catid']; //set Category id
			$sqlselect = 'SELECT * from category where dbcatid = :bvcatid'; //create an SQL string to select locations
			$result = $db->prepare($sqlselect); //prepare statement
			$result->bindValue(':bvcatid', $formfield['ffcatid']); //bind parameters
			$result->execute(); //execute query
			$row = $result->fetch(); //fetch result set
	
		if( isset($_POST['thesubmit']) ) //if the user submits
		{	
			$showform = 2; //show 2nd form
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffcatid'] = $_POST['catid'];
			$formfield['ffname'] = trim($_POST['name']);
			$formfield['ffdescr'] = trim($_POST['description']);

			if(empty($formfield['ffname'])){$errormsg .= "<p>Your category name field is empty.</p>";} //error message
			if(empty($formfield['ffdescr'])){$errormsg .= "<p>Your description field is empty.</p>";} //error message
			
			if($errormsg != "") //if there are errors
			{
				echo '<p>' . $errormsg . '</p>'; //print error 
			}
			else
			{
				try
				{
					$sqlinsert = 'UPDATE category SET dbcatname = :bvname, dbcatdescr = :bvdescr WHERE dbcatid = :bvcatid'; //UPDATE sql string
					$stmtinsert = $db->prepare($sqlinsert); //prepare statement
					$stmtinsert->bindvalue(':bvname', $formfield['ffname']); //bind parameters
					$stmtinsert->bindvalue(':bvdescr', $formfield['ffdescr']);
					$stmtinsert->bindvalue(':bvcatid', $formfield['ffcatid']);
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
			<fieldset><legend>Category Info</legend>
				<table border>
					<tr>
						<th>Category Name: </th>
						<td><input type="text" name="name" id="name"
						value = "<?php echo $row['dbcatname']; ?>"	></td>
					</tr>
					<tr>
						<th>Category Description: </th>
						<td><input type="text" name="description" id="description"
						value = "<?php echo $row['dbcatdescr']; ?>"	></td>
					</tr>
				</table>
				<input type="hidden" name = "catid" value=<?php echo $formfield['ffcatid'] ?>>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2 && in_array($_SESSION['staffloginpermit'], $allowedperms)) { //form 2
	?>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Category Info</legend>
				<table border>
					<tr>
						<th>Category Name: </th>
						<td><input type="text" name="name" id="name"
						value = "<?php echo $row['dbcatname']; ?>"	></td>
					</tr>
					<tr>
						<th>Category Description: </th>
						<td><input type="text" name="description" id="description"
						value = "<?php echo $row['dbcatdescr']; ?>"	></td>
					</tr>
				</table>
				<input type="hidden" name = "catid" value=<?php echo $formfield['ffcatid'] ?>>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<br><br>
	<table border>
	<tr>
		<th>Category Name</th> 
		<th>Category Description</th> 
	</tr>
	<?php 
			$sqlselectc = 'SELECT * from category where dbcatid = :bvcatid'; //create an SQL string to select locations
			$resultc = $db->prepare($sqlselectc); //prepare statement
			$resultc->bindValue(':bvcatid', $formfield['ffcatid']); //bind parameters
			$resultc->execute(); //execute query
		
		while ( $rowc = $resultc-> fetch() ) //print result set
			{
				echo '<tr><td>' . $rowc['dbcatname'] . '</td><td>'. $rowc['dbcatdescr'] . '</td></tr>'; //print Category name
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