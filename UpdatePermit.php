<?php
$pagetitle = 'Update Permit';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$allowedperms = array(1,2,13); //allowed permissions for this page
		
			$showform = 1;
			$formfield['ffpermitid'] = $_POST['permitid'];
			$sqlselect = 'SELECT * from permit where dbpermitid = :bvpermitid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvpermitid', $formfield['ffpermitid']);
			$result->execute();
			$row = $result->fetch(); 
		
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			$formfield['ffpermitid'] = $_POST['permitid'];
			$formfield['ffpermitposition'] = trim($_POST['permitposition']);
			echo '<p>The form was submitted.</p>';

		

			if(empty($formfield['ffpermitposition'])){$errormsg .= "<p>Your table choice is empty.</p>";}
			
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
					$sqlinsert = 'update permit set dbpermitposition = :bvpermitposition
								  where dbpermitid = :bvpermitid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvpermitposition', $formfield['ffpermitposition']);
					$stmtinsert->bindvalue(':bvpermitid', $formfield['ffpermitid']);
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
			<fieldset><legend>Permit Information</legend>
				<table border>
					
					<tr>
						<th><label for="permitposition">Position:</label></th>
						<td><input type="text" name="permitposition" id="permitposition" size="10" value="<?php echo $row['dbpermitposition'];?>"/></td>
					</tr>
					
				</table>
				<input type="hidden" name = "permitid" value=<?php echo $formfield['ffpermitid'] ?>>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
}
include_once 'footer.php';
?>