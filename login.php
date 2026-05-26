<?php
session_start();

include 'config/db.php';

if(isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE username=?"
    );

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])) {

            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");
            exit();
        }
    }

    $error = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Login</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<link rel="stylesheet"
href="assets/styles.css">

</head>

<body class="bg-light">

<div class="container mt-5" style="max-width:500px;">

<div class="card p-4">

<h3 class="mb-4">Login</h3>

<?php if(isset($error)): ?>

<div class="alert alert-danger">
    <?php echo $error; ?>
</div>

<?php endif; ?>

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
name="login"
>
Login
</button>

</form>

</div>
</div>

</body>
</html>