<?php
//Developer(s): Blakley Parker
//Date: 3/14/2017
//Purpose: This is the navigation menu for the back end which provides links that are accessible to the staff.
//Edited by: Jeremy McGuire
	//echo '<p> Welcome, ' . $_SESSION['username'] . '<br></p>';
$otherstaff = array(8,9,10,5); 
if(isset($_SESSION['staffloginid'])) {

	echo "<div class='topnav'>";

	if ($_SESSION['staffloginpermit'] == 13){
		echo "<center>";
	
		echo "<div class='logo'>";
	
		echo "<img src='img\logo.png' alt='SweetGrass Logo'>";
	
		echo "</div>";
	
		echo "</center>";
			echo  "<a href ='index.php'>Home</a>";	
			echo	"<a href ='InsertCustomer.php'>Insert Customer</a>";		
			echo 	"<a href ='InsertStaff.php'>Insert Staff</a>";	
			echo 	"<a href ='insertorder.php'>Insert Order</a>";	
			echo 	"<a href ='InsertPermit.php'>Insert Permit</a>";	
			echo 	"<a href ='InsertTable.php'>Insert Table</a>";	
			echo 	"<a href ='insertmenuitem.php'>Insert Menu Item</a>";	
			echo 	"<a href ='insertcategory.php'>Insert Category</a>";	
			echo 	"<a href ='SelectInventory.php'>Select Inventory</a>";	
			echo 	"<a href ='selectlocation.php'>Select Location</a>";
			echo 	"<a href ='selectcategory.php'>Select Category</a>";	
			echo 	"<a href ='SelectTable.php'>Select Table</a>";	
			echo 	"<a href ='SelectPermit.php'>Select Permit</a>";
			echo 	"<a href ='selectmenu.php'>Select Menu</a>";
			echo 	"<a href ='selectstaff.php'>Select Staff</a>";
			echo 	"<a href ='makeorder.php'>Fill Orders</a>";
			echo 	"<a href ='selectorder.php'>View Orders</a>";
			echo 	"<a href ='updateoperationhours.php'>Update Open Hours</a>";
			echo 	"<a href ='changeloc.php'>Change Location</a>";
	}
	if ($_SESSION['staffloginpermit'] == 1){
		echo "<center>";
	
		echo "<div class='logo'>";
	
		echo "<img src='img\logo.png' alt='SweetGrass Logo'>";
	
		echo "</div>";
	
		echo "</center>";
			echo  "<a href ='index.php'>Home</a>";	
		echo	"<a href ='InsertCustomer.php'>Insert Customer</a>";		
		echo 	"<a href ='InsertStaff.php'>Insert Staff</a>";	
		echo 	"<a href ='insertorder.php'>Insert Order</a>";		
		echo 	"<a href ='InsertTable.php'>Insert Table</a>";	
		echo 	"<a href ='insertmenuitem.php'>Insert Menu Item</a>";	
		echo 	"<a href ='insertcategory.php'>Insert Category</a>";	
		echo 	"<a href ='SelectInventory.php'>Select Inventory</a>";	
		echo 	"<a href ='selectcategory.php'>Select Category</a>";	
		echo 	"<a href ='SelectTable.php'>Select Table</a>";	
		echo 	"<a href ='SelectPermit.php'>Select Permit</a>";
		echo 	"<a href ='selectmenu.php'>Select Menu</a>";
		echo 	"<a href ='selectstaff.php'>Select Staff</a>";
		echo 	"<a href ='makeorder.php'>Fill Orders</a>";
		echo 	"<a href ='selectorder.php'>View Orders</a>";
		echo 	"<a href ='updateoperationhours.php'>Update Open Hours</a>";
	}
	if ($_SESSION['staffloginpermit'] == 3 || $_SESSION['staffloginpermit'] == 2){
		echo "<center>";
	
		echo "<div class='logo'>";
	
		echo "<img src='img\logo.png' alt='SweetGrass Logo'>";
	
		echo "</div>";
	
		echo "</center>";
			echo  "<a href ='index.php'>Home</a>";	
		echo 	"<a href ='insertorder.php'>Insert Order</a>";		
		echo 	"<a href ='InsertTable.php'>Insert Table</a>";	
		echo 	"<a href ='insertmenuitem.php'>Insert Menu Item</a>";	
		echo 	"<a href ='insertcategory.php'>Insert Category</a>";	
		echo 	"<a href ='SelectInventory.php'>Select Inventory</a>";	
		echo 	"<a href ='selectcategory.php'>Select Category</a>";	
		echo 	"<a href ='SelectTable.php'>Select Table</a>";	
		echo 	"<a href ='SelectPermit.php'>Select Permit</a>";
		echo 	"<a href ='selectmenu.php'>Select Menu</a>";
		echo 	"<a href ='makeorder.php'>Fill Orders</a>";
		echo 	"<a href ='selectorder.php'>View Orders</a>";
	}
		if (in_array($_SESSION['staffloginpermit'], $otherstaff)){
		echo "<center>";
	
		echo "<div class='logo'>";
	
		echo "<img src='img\logo.png' alt='SweetGrass Logo'>";
	
		echo "</div>";
	
		echo "</center>";
			echo  "<a href ='index.php'>Home</a>";	
		echo 	"<a href ='insertorder.php'>Insert Order</a>";		
		echo 	"<a href ='makeorder.php'>Fill Orders</a>";
		echo 	"<a href ='selectorder.php'>View Orders</a>";
	}
	echo "<a href='logout.php'>Log Out</a>";
	
	echo "</div>";

	$visible = 1;
}	
	else
{
	echo "<div class='topnav'>";
	
		echo "<center>";
	
		echo "<div class='logo'>";
	
		echo "<img src='img\logo.png' alt='SweetGrass Logo'>";
	
		echo "</div>";
	
		echo "</center>";
	
	echo "<a href='login.php'>Log In</a>";
	
	echo "</div>";
	$visible = 0;
}	

?>