<?php 

// check if the user is not  authenticated
if(isset($_SESSION['userDetails'])){
    header('location:index.php');
}



?>