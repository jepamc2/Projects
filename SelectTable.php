<?php
$pagetitle = 'Select Table';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
$allowedperms = array(1,2,13,3); //allowed permissions for this page

	$formfield['fflocation'] = $_SESSION['stafflocid'];
	
	if( isset($_POST['thesubmit']) )
		{
			$formfield['fftablename'] = $_POST['tablename'];
		
			$sqlselect = "select tables.* FROM tables
							WHERE tables.dbtablename like CONCAT('%', :bvtablename, '%') AND dblocid = :bvlocation";				
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvtablename', $formfield['fftablename']);
			$result->bindValue(':bvlocation', $formfield['fflocation']);
			$result->execute();
			
		}
	else
		{
			$sqlselect = "select *from tables WHERE dblocid = :bvlocation";
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvlocation', $formfield['fflocation']);
			$result->execute();
			
		}

if ( $showform = 1 && in_array($_SESSION['staffloginpermit'], $allowedperms))
{		
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Table Information</legend>
				<table border>
					<tr>
						<th><label for="tablename">Table Name:</label></th>
						<td><input type="text" name="tablename" id="tablename" size="10" value="<?php if( isset($formfield['fftablename'])){echo $formfield['fftablename'];}?>"/></td>
					</tr>
				</table>
				<input type="submit" class="button" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Table Name</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				
				$sqlselectci = "SELECT tables.*
					FROM tables
					WHERE tables.dbtablename = :bvtablename";
					$resultci = $db->prepare($sqlselectci);
					$resultci->bindValue(':bvtablename', $row['dbtablename']);
					$resultci->execute();
				
				echo '<tr><td>' . $row['dbtablename'] . '</td><td> ' .
				'<form action = "UpdateTable.php" method = "post">
						<input type = "hidden" name = "tableid" value = "'
						. $row['dbtableid'] . 
						'"><input type="submit" class="button" name = "theedit" value="Edit">
				</form>' . '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>