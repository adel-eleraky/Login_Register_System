<?php 
session_start();

include "App/Http/Middlewares/auth.php";



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style/master.css">
    <title>SYSTEM</title>
</head>
<body>
    <div class="header">
        <div class="container">
            <span><a href="index.php">LOGIN & REGISTER SYSTEM</a></span>
            <div class="nav-items">
                <ul>
                    <li><a href="logout.php">LOGOUT</a></li>
                    <li><a href="index.php">ACCOUNT</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="container">
            <div class="card">
                <img src="assets/uploads/images/<?= $_SESSION['userDetails']['image'] ?>" alt="">
                <table class="table table-dark table-striped">
                    <tbody>
                        <tr>
                            <td>ID</td>
                            <td><?= $_SESSION['userDetails']['id'] ?></td>
                        </tr>
                        <tr>
                            <td>NAME</td>
                            <td><?= $_SESSION['userDetails']['name'] ?></td>
                        </tr>
                        <tr>
                            <td>EMAIL</td>
                            <td><?= $_SESSION['userDetails']['email'] ?></td>
                        </tr>
                        <tr>
                            <td>PHONE</td>
                            <td><?= "0".$_SESSION['userDetails']['phone'] ?></td>
                        </tr>
                    </tbody>
                </table>
                <a class="btn btn-primary" href="update.php" role="button">UPDATE DETAILS</a>
            </div>
        </div>
    </div>
</body>
</html>