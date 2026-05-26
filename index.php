<?php
session_start();

include 'config/db.php';

if (
    isset($_POST['add_sighting']) &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'admin'
) {

    $species = $_POST['species'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $notes = $_POST['notes'];

    $image = "";

    if (!empty($_FILES['image']['name'])) {

        $targetDir = "uploads/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $image = $targetDir . basename($_FILES['image']['name']);

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $image
        );
    }
    $lat = null;
    $lng = null;

    $location_encoded = urlencode($location);

    $geo_url =
    "https://nominatim.openstreetmap.org/search?format=json&q=$location_encoded";

    $options = [
        "http" => [
           "header" => "User-Agent: MarineMammalApp/1.0\r\n"
        ]
    ];

    $context = stream_context_create($options);

    $response = file_get_contents(
        $geo_url,
        false,
        $context
    );

    $data = json_decode($response, true);

    if(!empty($data)) {
        $lat = $data[0]['lat'];
        $lng = $data[0]['lon'];
    }
    $stmt = $conn->prepare(
        "INSERT INTO sightings
        (species, location, date, notes, image, lat, lng)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssssdd",
        $species,
        $location,
        $date,
        $notes,
        $image,
        $lat,
        $lng
    );

    $stmt->execute();
}

$result = $conn->query(
    "SELECT * FROM sightings ORDER BY date DESC"
);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Marine Mammal Monitoring</title>

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
    href="assets/styles.css">

</head>

<body class="bg-light">

<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">

    <h2 class="mb-4 text-center">
        Marine Mammal Monitoring Portal
    </h2>

    <?php if (
        isset($_SESSION['role']) &&
        $_SESSION['role'] === 'admin'
    ): ?>

    <div class="card p-4 mb-4">

        <h4>Add Sighting</h4>

        <form method="POST" enctype="multipart/form-data">

            <input
                type="text"
                name="species"
                class="form-control mb-3"
                placeholder="Species"
                required
            >

            <input
                type="text"
                name="location"
                class="form-control mb-3"
                placeholder="Location"
                required
            >

            <input
                type="date"
                name="date"
                class="form-control mb-3"
                required
            >

            <textarea
                name="notes"
                class="form-control mb-3"
                placeholder="Notes"
            ></textarea>

            <input
                type="file"
                name="image"
                class="form-control mb-3"
            >

            <button
                class="btn btn-primary"
                name="add_sighting"
            >
                Add Sighting
            </button>

        </form>

    </div>

    <?php endif; ?>

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