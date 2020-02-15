<?php
$pagetitle = 'Select Permit';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
	$allowedperms = array(13,1); //allowed permissions for this page
	$formfield['fflocation'] = $_SESSION['stafflocid'];
	
	if( isset($_POST['thesubmit']) )
		{
			$formfield['ffpermitposition'] = $_POST['permitposition'];
			
			$sqlselect = "select permit.* FROM permit
							WHERE permit.dbpermitposition like CONCAT('%', :bvpermitposition, '%') AND dblocid = :bvlocation";
							
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvpermitposition', $formfield['ffpermitposition']);
			$result->bindValue(':bvlocation', $formfield['fflocation']);
			$result->execute();
		}
	else
		{
			$sqlselect = "select * from permit WHERE  dblocid = :bvlocation";

			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvlocation', $formfield['fflocation']);
			$result->execute();
			
		}

if ( $showform = 1 && in_array($_SESSION['staffloginpermit'], $allowedperms))
{		
?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Position Information</legend>
				<table border>
					<tr>
						<th><label for="permitposition">Position Name:</label></th>
						<td><input type="text" name="permitposition" id="permitposition" size="10" value="<?php if( isset($formfield['ffpermitposition'])){echo $formfield['ffpermitposition'];}?>"/></td>
					</tr>
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Position Name</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				
				echo '<tr><td>' . $row['dbpermitposition'] . '</td><td> ' .
				'<form action = "UpdatePermit.php" method = "post">
						<input type = "hidden" name = "permitid" value = "'
						. $row['dbpermitid'] . 
						'"><input type="submit" class="button" name = "theedit" value="Edit">
				</form>' . '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>