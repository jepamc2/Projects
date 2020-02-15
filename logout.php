<?php
//Developer(s): Joshua Mercer
//Date: 3/9/2017
//Purpose: This is a logout form
session_start(); //create a session just in case there isnt one currently
session_unset(); //unset session
session_destroy(); //destroy session
header("Location: login.php"); //redirect to login
?>