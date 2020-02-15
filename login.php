<?php
//Developer(s): Joshua Mercer
//Date: 3/9/2017
//Purpose: This is a login form for the back end of the system

$pagetitle = "Login Confirmation"; //sets the name of the page
require_once 'header.php'; //requires an instance of the header file
require_once 'connect.php'; //requires a connection from the database

if(isset($_SESSION['staffloginid'])) //if the user is already logged in
{
    echo "<p class='error'>You are already logged in.</p>"; //this will output the user has already logged in
    include_once 'footer.php'; //include the footer file (if not there will not cause errors)
    exit(); //exit
}
//declare variables
$showform = 1; //this allows the input fields to be shown until the user is logged in
$errormsg = ''; //set the error message string to empty

if(isset ($_POST['submit'])) { //when the user hits the submit button
	
	$formfield['ffuname'] = strtolower(trim($_POST['uname'])); //this trims the and converts the users email to lowercase
	$formfield['ffpassword'] = trim($_POST['password']); 
	
	if(empty($formfield['ffuname'])) { $errormsg .= '<p>USERNAME IS MISSING</p>';} //if fields are empty concatenate onto the error message string
	if(empty($formfield['ffpassword'])) { $errormsg .= '<p>PASSWORD IS MISSING</p>';} //if fields are empty concatenate onto the error message string
	
	if($errormsg != '') { //if the error message string isn't empty
		echo "<p>THERE ARE ERRORS</p>" . $errormsg; //output error
	}
	else //if there are no errors
	{
		try 
		{
			$sql = 'SELECT * FROM staff WHERE dbstaffusername = :bvuname'; //connect to database and get all info connected to the email entered
			$s = $db->prepare($sql); //prepare your SQL statement 
			$s->bindValue(':bvuname', $formfield['ffuname']); //bind value for prepared statement to what the user has entered for email
			$s->execute(); //execute the prepared statement 
			$count = $s->rowCount(); //count the returned information
		}
		catch (PDOException $e) //if any errors occur during the above process
		{
			echo "ERROR!!!" . $e->getMessage(); //print error to user
			exit(); //exit
		}
		
		if($count < 1) //if the count of the returned information is less than one, that means there is no info connected to the email entered
		{
			echo '<p>The email or password is incorrect</p>'; //print error
		}
		else 
		{
			$row = $s->fetch(); //fetch all data from the database 

			$confirmedpw = $row['dbstaffpassword']; //get users password
			
			if (password_verify($formfield['ffpassword'], $confirmedpw)) //if users password matches one entered into form
			{
				$_SESSION['staffloginid']= $row['dbstaffid']; //set session variables for the staff id
                $_SESSION['staffloginname'] = $row['dbstafffname']; //set session variables for the staff first name
				$_SESSION['staffloginpermit'] = $row['dbpermitid'];  //set session variables for the staff permissions
				$_SESSION['stafflocid'] = $row['dblocid'];  //set session variables for the staff location
				$_SESSION['staffemployed'] = $row['dbstaffemployed'];  //set session variables for the staff empolyed status
				$showform = 0; //hide the form field information
				echo "<br>"; 
                echo "Logged In Successfully"; //tell user that login was su
				echo "<br><br>";
				echo '<script>window.location = "index.php";</script>'; //redirect to set URL
				echo "<br>";
			} 
			else
			{
				echo '<p>The emails or password is incorrect</p>'; //if password incorrect throw ambiguous incorrect error
			}
		}
	}
}
if($showform == 1) //if  user hasn't logged in
{
//HTML below is the login form
?>

<p>You are not logged in.  Please log in</p>

<form name = "loginForm" id = "loginForm" method = "post" action = "login.php">
	<table>
		<tr>
			<td>Username</td>
			<td><input type="text" name="uname" id = "uname" required></td>
		</tr><tr>	
			<td>Password</td>	
			<td><input type="password" name="password" id = "password" required></td>
		</tr><tr>	
			<td>Submit:</td>
			<td><input type ="submit" class="button" name= "submit" value = "submit"></td>
		</tr>
	</table>
</form>
<?php
} //close if statement
include_once 'footer.php'; //try to include footed
?>