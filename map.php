<?php

session_start();

include 'config/db.php';

$result = $conn->query(
    "SELECT * FROM sightings
     WHERE lat IS NOT NULL
     AND lng IS NOT NULL"
);

$markers = [];

while($row = $result->fetch_assoc()) {

    $markers[] = [
        "species" => $row['species'],
        "location" => $row['location'],
        "date" => $row['date'],
        "notes" => $row['notes'],
        "image" => $row['image'],
        "lat" => (float)$row['lat'],
        "lng" => (float)$row['lng']
    ];
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Sightings Map</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<link rel="stylesheet"
href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<link rel="stylesheet"
href="assets/styles.css">

<style>

#map {
    height: 600px;
    border-radius: 12px;
}

</style>

</head>

<body class="bg-light">

<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">

<h2 class="text-center mb-4">
Marine Mammal Sighting Map
</h2>

<div id="map"></div>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

var map = L.map('map').setView([20, 0], 2);

L.tileLayer(
    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
        attribution: '© OpenStreetMap'
    }
).addTo(map);

var markers =
<?php echo json_encode($markers); ?>;

var leafletMarkers = [];

markers.forEach(function(m) {

    var popup = `
        <b>Species:</b> ${m.species}<br>
        <b>Location:</b> ${m.location}<br>
        <b>Date:</b> ${m.date}<br>
        <b>Notes:</b> ${m.notes}<br>
    `;

    if(m.image) {
        popup += `
        <img
        src="${m.image}"
        style="width:100%;margin-top:10px;">
        `;
    }

    var marker = L.marker([m.lat, m.lng])
        .addTo(map)
        .bindPopup(popup);

    leafletMarkers.push(marker);
});

if(leafletMarkers.length > 0) {

    var group =
    new L.featureGroup(leafletMarkers);

    map.fitBounds(
        group.getBounds().pad(0.2)
    );
}

</script>

</body>
</html>