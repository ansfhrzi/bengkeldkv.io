<?php
session_start();
include 'koneksi.php';

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $data = mysqli_query($conn,"SELECT * FROM admin 
    WHERE username='$username' AND password='$password'");

    if(mysqli_num_rows($data) > 0){

        $_SESSION['admin'] = $username;
        header("Location: index.php");

    } else {

        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Admin - Bengkel DKV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5" style="max-width:400px;">

<div class="card shadow">
<div class="card-body">

<h4 class="text-center mb-4">LOGIN ADMIN</h4>

<?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="POST">

<input type="text" name="username" 
class="form-control mb-3" 
placeholder="Username" required>

<input type="password" name="password" 
class="form-control mb-3" 
placeholder="Password" required>

<button type="submit" name="login" 
class="btn btn-primary w-100">
Login
</button>

</form>

</div>
</div>

</div>

</body>
</html>
