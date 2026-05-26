<?php

session_start();

include 'config/db.php';

$result = $conn->query(
    "SELECT * FROM sightings ORDER BY date DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>All Sightings</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<link rel="stylesheet"
href="assets/styles.css">

</head>

<body class="bg-light">

<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">

<h2 class="mb-4 text-center">
All Marine Mammal Sightings
</h2>

<div class="row">

<?php while($row = $result->fetch_assoc()): ?>

<div class="col-md-4 mb-4">

<div class="card h-100">

<?php if($row['image']): ?>

<img
src="<?php echo $row['image']; ?>"
class="card-img-top"
style="height:200px;object-fit:cover;"
>

<?php endif; ?>

<div class="card-body">

<h5>
<?php echo $row['species']; ?>
</h5>

<p>
<strong>Location:</strong>
<?php echo $row['location']; ?>
</p>

<p>
<strong>Date:</strong>
<?php echo $row['date']; ?>
</p>

<p>
<?php echo $row['notes']; ?>
</p>

</div>

</div>

</div>

<?php endwhile; ?>

</div>

</div>

</body>
</html>