<?php
include 'dbConnect.php';
include 'mysqlQueries.php';

// thId,thName,locId,locThId,thSeats,thLayout,thStatus
  $locId = filter_input(INPUT_POST, 'locId');
  $thId = filter_input(INPUT_POST, 'thId');
  $locThId = filter_input(INPUT_POST, 'locThId');
  $thStatus = filter_input(INPUT_POST, 'thStatus');
  $thName = filter_input(INPUT_POST, 'thName');
  $thSeats = filter_input(INPUT_POST, 'thSeats');
  $thLayout = filter_input(INPUT_POST, 'thLayout');

  $locId = $conn->real_escape_string($locId);
  $thId = $conn->real_escape_string($thId);
  $locThId = $conn->real_escape_string($locThId);
  $thStatus = $conn->real_escape_string($thStatus);
  $thName = $conn->real_escape_string($thName);
  $thSeats = $conn->real_escape_string($thSeats);
  $thLayout = $conn->real_escape_string($thLayout);

  $updateTheatreString = "UPDATE theatre SET name='".$thName."', seat='".$thSeats."', 
                          status='".$thStatus."', layout='".$thLayout."' where theatre_id=".$thId;
  if ($conn->query($updateTheatreString) === TRUE) {
      $updateLocation_TheatreString = "UPDATE location_theatre SET location_id_l='".$locId."', 
                                        theatre_id_l='".$thId."' where id=".$locThId;
      if ($conn->query($updateLocation_TheatreString) === TRUE) {
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