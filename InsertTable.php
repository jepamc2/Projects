<?php
//Developer(s): Jeremy McGuire
//Date:
//Purpose: Insert Tables into the database
//Edited by: Joshua Mercer
$pagetitle = "Insert Table Info";
require_once 'header.php';

//NECESSARY VARIABLES
$errormsg = "";
$showform = 1;
$allowedperms = array(1,2,13,3); //allowed permissions for this page

//DATABASE CONNECTION
require_once "connect.php";

		$formfield['fflocation'] = $_SESSION['stafflocid'];

		if( isset($_POST['submit']) )
		{
			
			$formfield['name'] = trim($_POST['name']);
			
			echo '<p>'.$formfield['list'].'</p>';
			if (empty($formfield['name'])) {$errormsg = "<p>Your name is empty</p>";}
			if ($formfield['list'] == "") {$errormsg = "<p>Your list is empty</p>";}
			
			
     		
				
				$options = [
					'cost' => 12,
					'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
				];
				
				
				try 
				{
					$sqlinsert = 'INSERT INTO tables (dbtablename, dblocid)
								VALUES (:bvname, :bvlocation)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindValue(':bvlocation', $formfield['fflocation']);
					$stmtinsert->bindValue(':bvname', $formfield['name']);
					$stmtinsert->execute();
					
					echo "<div>Table information has been submitted successfully.</div>";
					
				}	
				catch(PDOException $e)
				{
					echo 'ERROR!' . $e->getMessage();
					exit();
				}	
				
			
		}//if isset submit


	$sqlselect = 'SELECT * from tables WHERE dblocid = :bvlocation';
	$result = $db->prepare($sqlselect);
	$result->bindValue(':bvlocation', $formfield['fflocation']);
	$result->execute();
	if(in_array($_SESSION['staffloginpermit'], $allowedperms))
	{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
			<fieldset><legend>Table Information</legend>
				<table border>
					<tr>
						<th><label for="name">Table Name:</label></th>
						<td><input type="text" name="name" id="name" size="10" value="<?php if( isset($formfield['name'])){echo $formfield['name'];}?>"/></td>
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