<?php 

// check if the user is authenticated
if(! isset($_SESSION['userDetails'])){
    header('location:login.php');die;
}



?>