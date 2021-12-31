<?php
// $conn = mysqli_connect('localhost', 'root', '');
// $database = mysqli_select_db($conn, 'testing');

include ('config.php');

// TODO maybe move this to signin.php?
$encodedData = file_get_contents('php://input');  // take data from react native fetch API
$decodedData = json_decode($encodedData, true);
