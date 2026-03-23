<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$label    = $_POST['label'];
$address  = $_POST['address_line'];
$city     = $_POST['city'];
$pincode  = $_POST['pincode'];

$lat = $_POST['latitude'];
$lng = $_POST['longitude'];

if(empty($lat) || empty($lng)){

    $fullAddress = urlencode($address . ", " . $city . ", " . $pincode . ", India");

    $options = [
        "http" => [
            "header" => "User-Agent: FoodFleetApp\r\n"
        ]
    ];

    $context = stream_context_create($options);

    $url = "https://nominatim.openstreetmap.org/search?format=json&q=$fullAddress";

    $response = file_get_contents($url, false, $context);

    $data = json_decode($response, true);

    if(isset($data[0])){
        $lat = $data[0]['lat'];
        $lng = $data[0]['lon'];
    } else {
        die("Unable to detect coordinates. Please enter a more specific address.");
    }
}

mysqli_query($conn,"INSERT INTO addresses
(user_id,label,address_line,city,pincode,latitude,longitude)
VALUES
('$user_id','$label','$address','$city','$pincode','$lat','$lng')");

header("Location: profile.php");
exit();
?>