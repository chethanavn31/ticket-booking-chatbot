<?php
include 'dbConnect.php';
include 'mysqlQueries.php';

$locId = filter_input(INPUT_POST, 'locId');

$locName = filter_input(INPUT_POST, 'locName');

$locStatus = filter_input(INPUT_POST, 'locStatus');

$locId = $conn->real_escape_string($locId);
$locName = $conn->real_escape_string($locName);
$locStatus = $conn->real_escape_string($locStatus);
$queryString = returnUpdateLocDataQuery($locName,$locStatus,$locId);

if ($conn->query($queryString) === TRUE) {
    echo "S";
  } else {
    echo $conn->error." ".$queryString;
  }
  
  $conn->close();



?>