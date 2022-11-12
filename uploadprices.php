<?php

include('dbconfig.php');

$pricelist = $_POST["sPricelist"];

// Check connection
if (!$conn) {
 die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

$result1 = $conn->query("DELETE FROM pricelist");

for ($x = 0; $x < count($pricelist); $x++) {
	$result = $conn->query("INSERT INTO pricelist (brand, description, size, unit, price)
							VALUES ('".$pricelist[$x][0]."', '".$pricelist[$x][1]."', '".$pricelist[$x][2]."', '".$pricelist[$x][3]."', ".$pricelist[$x][4].")");
}

	




