<?php
//Developer(s): Jeremy McGuire
//Date:
//Purpose: Insert Permits into the database
//Edited by: Joshua Mercer
$pagetitle = "Insert Permit Info";
require_once 'header.php';
require_once "connect.php";
//NECESSARY VARIABLES
$errormsg = "";
$showform = 1;
$allowedperms = array(13); //allowed permissions for this page


		$formfield['fflocation'] = $_SESSION['stafflocid'];

		if( isset($_POST['submit']) )
		{
			
			$formfield['position'] = trim($_POST['position']);
			
			echo '<p>'.$formfield['list'].'</p>';
			if (empty($formfield['position'])) {$errormsg = "<p>Your position is empty</p>";}
			if ($formfield['list'] == "") {$errormsg = "<p>Your list is empty</p>";}
			
			
     		
				
				$options = [
					'cost' => 12,
					'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
				];
				
				
				try 
				{
					$sqlinsert = 'INSERT INTO permit (dbpermitposition, dblocid)
								VALUES (:bvposition, :bvlocation)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindValue(':bvposition', $formfield['position']);
					$stmtinsert->bindValue(':bvlocation', $formfield['fflocation']);
					$stmtinsert->execute();
					
					echo "<div>Position information has been submitted successfully.</div>";
					
				}	
				catch(PDOException $e)
				{
					echo 'ERROR!' . $e->getMessage();
					exit();
				}	
				
			
		}//if isset submit


	$sqlselect = 'SELECT * from permit WHERE dblocid = :bvlocation';
	$result = $db->prepare($sqlselect);
	$result->bindValue(':bvlocation', $formfield['fflocation']);
	$result->execute();
	if(in_array($_SESSION['staffloginpermit'], $allowedperms))
	{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
			<fieldset><legend>Position Information</legend>
				<table border>
					<tr>
						<th><label for="position">Position Name:</label></th>
						<td><input type="text" name="position" id="position" size="10" value="<?php if( isset($formfield['position'])){echo $formfield['position'];}?>"/></td>
					</tr>
					
					<tr>
						<th>Submit:</th>
						<td><input type="submit" class="button" name="submit" value="SUBMIT" /></td>
					</tr>
				</table>
			</fieldset>
		</form>
			<br><br>

	<?php
	}
	include_once 'footer.php';
	?>