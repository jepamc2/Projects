<?php
$pagetitle = 'Update Table';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$assignedperm = $_SESSION['staffloginpermit']; //set assigned perm	
$allowedperms = array(13,1,2,3); //allowed permissions for this page
		
			$showform = 1;
			$formfield['fftableid'] = $_POST['tableid'];
			$sqlselect = 'SELECT * from tables where dbtableid = :bvtableid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvtableid', $formfield['fftableid']);
			$result->execute();
			$row = $result->fetch(); 
		
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			$formfield['fftableid'] = $_POST['tableid'];
			$formfield['fftablename'] = trim($_POST['tablename']);
			echo '<p>The form was submitted.</p>';

		

			if(empty($formfield['fftablename'])){$errormsg .= "<p>Your table choice is empty.</p>";}
			
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
					$sqlinsert = 'update tables set dbtablename = :bvtablename
								  where dbtableid = :bvtableid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvtablename', $formfield['fftablename']);
					$stmtinsert->bindvalue(':bvtableid', $formfield['fftableid']);
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

	if (($showform == 1 || $showform == 2) && in_array($_SESSION['staffloginpermit'], $allowedperms))
	{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Table Information</legend>
				<table border>
					
					<tr>
						<th><label for="tablename">Table Name:</label></th>
						<td><input type="text" name="tablename" id="tablename" size="10" value="<?php echo $row['dbtablename'];?>"/></td>
					</tr>
					
				</table>
				<input type="hidden" name = "tableid" value=<?php echo $formfield['fftableid'] ?>>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
}
include_once 'footer.php';
?>