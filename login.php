<?php
//Developer(s): Joshua Mercer
//Date: 3/9/2017
//Purpose: This is a login form for the front end of the system

$pagetitle = "Login Confirmation"; //sets the name of the page
require_once 'header.php'; //requires an instance of the header file
require_once 'connect.php'; //requires a connection from the database

echo '<div class=paper>';
echo '<center>';
echo '<br><br><br><br><br><br><br>';
if(isset($_SESSION['custloginid'])) //if the user is already logged in
{
    echo "<h4 class='error'>You are already logged in.</h4>"; //this will output the user has already logged in
    include_once 'footer.php'; //include the footer file (if not there will not cause errors)
    exit(); //exit 
}
//declare variables
$showform = 1; //this allows the input fields to be shown until the user is logged in
$errormsg = ''; //set the error message string to empty

if(isset ($_POST['submit'])) { //when the user hits the submit button
	
	$formfield['ffemail'] = strtolower(trim($_POST['email'])); //this trims the and converts the users email to lowercase
	$formfield['ffpassword'] = trim($_POST['password']); 
	
	if(empty($formfield['ffemail'])) { $errormsg .= '<h4>EMAIL IS MISSING</h4>';} //if fields are empty concatenate onto the error message string
	if(empty($formfield['ffpassword'])) { $errormsg .= '<h4>PASSWORD IS MISSING</h4>';} //if fields are empty concatenate onto the error message string
	
	if($errormsg != '') { //if the error message string isn't empty
		echo "<h4>THERE ARE ERRORS</h4>" . $errormsg; //output error
	}
	else //if there are no errors
	{
		try 
		{
			$sql = 'SELECT * FROM customers WHERE dbcustemail = :bvemail'; //connect to database and get all info connected to the email entered
			$s = $db->prepare($sql); //prepare your SQL statement 
			$s->bindValue(':bvemail', $formfield['ffemail']); //bind value for prepared statement to what the user has entered for email
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
			echo '<h4>The email or password is incorrect</h4>'; //print error
		}
		else 
		{
			$row = $s->fetch(); //fetch all data from the database 
			$confirmeduname = $row['dbcustemail']; //get users email
			$confirmedpw = $row['dbcustpassword']; //get users password
			
			if (password_verify($formfield['ffpassword'], $confirmedpw)) //if users password matches one entered into form
			{
				$_SESSION['custloginid']= $row['dbcustid']; //set session variables for the customers id
                $_SESSION['custloginname'] = $row['dbcustfirstname'];  //set session variables for the customers first name
				$_SESSION['custloginpermit'] = $row['dbpermitid'];  //set session variables for the customers permissions
				$_SESSION['custemailconfirm'] = $row['dbcustemailconfirm'];
				$showform = 0; //hide the form field information
				echo "<br>"; 
                echo "Logged In Successfully"; //tell user that login was su
				echo "<br><br>";
				//echo '<a href = "index.php">Continue</a>'; //allow user to continue to homepage
				//header("Location: index.php"); //redirect to set URL
				echo '<script>window.location = "index.php";</script>'; //redirect to set URL
				echo "<br>";
			} 
			else
			{
				echo '<h4>The emails or password is incorrect</h4>'; //if password incorrect throw ambiguous incorrect error
			}
		}
	}
}
if($showform == 1) //if  user hasnt logged in
{
//HTML below is the login form
?>	

<center>
<h4>You are not logged in.  Please log in</h4>
<br><br>
<form name = "loginForm" id = "loginForm" method = "post" action = "login.php">
	<table>
		<tr>
			<td  class="logintable">Email</td>
			<td class="logintable"><input type="text" class="logintable" name="email" id = "email" required></td>
		</tr><tr>	
			<td  class="logintable">Password</td>	
			<td class="logintable"><input type="password" class="logintable" name="password" id = "password" required></td>
		</tr><tr>
			<td colspan="2"  class="logintable"><input type ="submit" name= "submit" value = "Log In" class="button"></td>
		</tr>
	</table>
</form><br><br>
<?php
echo "<h5><a href='register.php'>Sign Up</a><h5>";
echo '<br>';
echo "<h5><a href='passwordreset.php'>Forgot Password?</a><h5>";
echo '</div>';
} //close if statement
include_once 'footer.php'; //try to include footed
?>