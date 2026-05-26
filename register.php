<?php
include 'config/db.php';

if(isset($_POST['register'])) {

    $username = $_POST['username'];

    $password = password_hash(
        $_POST['password'],
        PASSWORD_DEFAULT
    );

    $stmt = $conn->prepare(
        "INSERT INTO users (username, password)
        VALUES (?, ?)"
    );

    $stmt->bind_param(
        "ss",
        $username,
        $password
    );

    $stmt->execute();

    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Register</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<link rel="stylesheet"
href="assets/styles.css">

</head>

<body class="bg-light">

<div class="container mt-5" style="max-width:500px;">

<div class="card p-4">

<h3 class="mb-4">Register</h3>

<form method="POST">

<input
type="text"
name="username"
class="form-control mb-3"
placeholder="Username"
required
>

<input
type="password"
name="password"
class="form-control mb-3"
placeholder="Password"
required
>

<button
class="btn btn-primary w-100"
name="register"
>
Register
</button>

</form>

</div>
</div>

</body>
</html>