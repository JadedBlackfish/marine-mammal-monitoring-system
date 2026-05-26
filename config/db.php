<?php

$conn = new mysqli(
    "mysql",
    "root",
    "root",
    "marine_mammals"
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>