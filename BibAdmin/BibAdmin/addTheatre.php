<?php
include 'dbConnect.php';
include 'mysqlQueries.php';

// thID,thName,locID,locName,ltID,seat,layout,status
  $locId = filter_input(INPUT_POST, 'locId');
  $thStatus = filter_input(INPUT_POST, 'thStatus');
  $thName = filter_input(INPUT_POST, 'thName');
  $thSeats = filter_input(INPUT_POST, 'thSeats');
  $thLayout = filter_input(INPUT_POST, 'thLayout');

  $locId = $conn->real_escape_string($locId);
  $thStatus = $conn->real_escape_string($thStatus);
  $thName = $conn->real_escape_string($thName);
  $thSeats = $conn->real_escape_string($thSeats);
  $thLayout = $conn->real_escape_string($thLayout);

  $insertTheatreString = "INSERT into theatre (name, seat, status, layout) 
                values('".$thName."','".$thSeats."','".$thStatus."','".$thLayout."')"; 
  if ($conn->query($insertTheatreString) === TRUE) {
      $last_id = $conn->insert_id;
      $insertLocation_TheatreString = "INSERT into location_theatre (location_id_l, theatre_id_l) 
                                  values('".$locId."','".$last_id."')";

      if ($conn->query($insertLocation_TheatreString) === TRUE) {
        if (!$conn -> commit()) {
            echo "Commit transaction failed";
        }
        else{
            echo "S";
        }
      }
      else {
        echo "Error inserting L_T";
      }
  }
  else {
    echo "Error inserting Th";
  }  
  $conn->close();
?>