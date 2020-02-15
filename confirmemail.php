<?php
//Developer(s): Joshua Mercer
//Date: 3/16/2018
//Purpose: to allow user to confirm email
//Edited by: 
$pagetitle = "Email Confirmation";
require_once 'header.php'; //header
require_once "connect.php"; //connection

	if (!isset($_GET['email']) || !isset($_GET['token'])){ //make sure token and email are passed
		echo '<script>window.location = "register.php";</script>'; //redirect url to register if you cant get token and email
		exit(); //exit window
	}else{ //if token and email are passed
		//set variables to passed variables
		$emailaddress = $_GET['email'];
		$token = $_GET['token'];
		//select from database where url values match database information
		$sqlchecktoken = "SELECT * FROM customers WHERE dbcustemail = :bvemail AND dbcusttoken = :bvtoken AND dbcustemailconfirm = 0"; //sql query
		$stmtchecktoken = $db->prepare($sqlchecktoken); //prepare statement
		$stmtchecktoken->bindValue(':bvemail', $emailaddress); //bind email to value
		$stmtchecktoken->bindValue(':bvtoken', $token);//bind email to value
		$stmtchecktoken->execute(); //execute statement
		$count = $stmtchecktoken->rowCount(); //
		
		if ($count > 0){
		$sqlupdateconfirm = "UPDATE customers SET dbcusttoken = '' , dbcustemailconfirm = '1' WHERE dbcustemail = :bvemail";
		$stmtupdateconfirm = $db->prepare($sqlupdateconfirm); //prepare statement
		$stmtupdateconfirm->bindValue(':bvemail', $emailaddress); //bind email to value
		$stmtupdateconfirm->execute();//execute statement
		echo '<h2>Email has been Verified! You can now </h2>'; //tell user email has been verified
		echo "<p> You can now <a href='login.php'>Log In</a></p>";
		echo '';
		}else{
			echo '<script>window.location = "register.php";</script>'; //redirect back to register page
		}
	}
include_once footer.php;
?>