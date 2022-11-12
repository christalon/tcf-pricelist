<?php

include('dbconfig.php');

// Check connection
if (!$conn) {
 die("Connection failed: " . mysqli_connect_error());
}

$result = $conn->query("SELECT * FROM pricelist");

if($result->num_rows > 0){
	$rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    echo json_encode($rows);
}

