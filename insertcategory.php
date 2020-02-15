<?php
//Developer(s): Maggie Hussey
//Date: 3/13/2018
//Purpose: to allow the user to enter new category info
//Edited by: Joshua Mercer
$pagetitle = 'Insert Category';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
$allowedperms = array(13,1,2,3); //allowed permissions for this page
		$formfield['fflocation'] = $_SESSION['stafflocid'];

		if( isset($_POST['thesubmit']) )
		{
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffcatname'] = trim($_POST['catname']);
			$formfield['ffcatdescr'] = trim($_POST['catdescr']);
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffcatname'])){$errormsg .= "<p>Your category is empty.</p>";}
			if(empty($formfield['ffcatdescr'])){$errormsg .= "<p>Your category description is empty.</p>";}
			/*  ****************************************************************************
			DISPLAY ERRORS
			If we have concatenated the error message with details, then let the user know
			**************************************************************************** */
			if($errormsg != "")
			{
				echo "<div class='error'><p>THERE ARE ERRORS!</p>";
				echo $errormsg;
				echo "</div>";
			}
			else
			{
				try
				{
					//enter data into database
					$sqlinsert = 'INSERT INTO category (dbcatname, dbcatdescr, dblocid)
								  VALUES (:bvcatname, :bvcatdescr, :bvlocation)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvcatname', $formfield['ffcatname']);
					$stmtinsert->bindvalue(':bvcatdescr', $formfield['ffcatdescr']);
					$stmtinsert->bindValue(':bvlocation', $formfield['fflocation']);
					$stmtinsert->execute();
					echo "<div class='success'><p>There are no errors.  Thank you.</p></div>";
				}//try
				catch(PDOException $e)
				{
					echo 'ERROR!!!' .$e->getMessage();
					exit();
				}
			}//else statement end
		}//if isset submit


	$sqlselect = 'SELECT * from category WHERE dblocid = :bvlocation';
	$result = $db->prepare($sqlselect);
	$result->bindValue(':bvlocation', $formfield['fflocation']);
	$result->execute();
	
if(in_array($_SESSION['staffloginpermit'], $allowedperms))
	{

	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Category Information</legend>
				<table border>
					<tr>
						<th>Category</th>
						<td><input type="text" name="catname" id="catname"
						value = <?php echo $formfield['ffcatname']; ?>></td>
					</tr>
					<tr>
						<th>Category Description</th>
						<td><input type="text" name="catdescr" id="catdescr"
						value = <?php echo $formfield['ffcatdescr']; ?>></td>
					</tr>
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>

<?php
	}
include_once 'footer.php';
?>