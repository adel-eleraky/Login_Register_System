<?php 
session_start();
unset($_SESSION['userDetails']);
header('location:login.php');

?>