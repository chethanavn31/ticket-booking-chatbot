<?php
include 'dbConnect.php';
include 'mysqlQueries.php';


$locName = filter_input(INPUT_POST, 'locName');

$locStatus = filter_input(INPUT_POST, 'locStatus');

$locName = $conn->real_escape_string($locName);
$locStatus = $conn->real_escape_string($locStatus);
$queryString = insertLocDataQuery($locName,$locStatus);

if ($conn->query($queryString) === TRUE) {
  $last_id = $conn->insert_id;
  $myObj->status = "S";
  $myObj->id = $last_id;
  $myObj->message = "Success";
  $myObj->query = $queryString;
  $myObj->locStatus=$locStatus;
    echo json_encode($myObj);

  } else {
    $myObj->status = "E";
  $myObj->id = 0;
  $myObj->message = $conn->error;
  $myObj->query = $queryString;
    echo json_encode($myObj);
  }
  
  $conn->close();



?>