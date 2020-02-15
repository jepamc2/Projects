<?php
//Developer(s): Blakley Parker
//Date: 3/14/2017
//Purpose: This is the navigation menu for the back end which provides links that are accessible to the staff.
//Edited by: Jeremy McGuire, Joshua Mercer

if(isset($_SESSION['custloginid'])) {
	
	echo "<div class='nav'>";
	
	echo "<a href ='index.php' class='navlinks'>Home</a>";
	
	echo "<a href ='updatecustomer.php' class='navlinks'>My Account</a>";
	
	echo "<a href='aboutus.php'><img src='img/logo1.png' alt='Logo'></a>";
	
	echo "<a href ='insertorder.php' class='navlinks'>Order</a>";

	echo "<a href='logout.php' class='navlinks'>Log Out</a>";
	
	echo "</div>";

	$visible = 1;
}	
	else
{
	echo "<div class='nav' class='navlinks'>";
	
	echo "<a href='login.php' class='navlinks'>Log In</a>";
	
	echo "</div>";
	$visible = 0;
}	

?>