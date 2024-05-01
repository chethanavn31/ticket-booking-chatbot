<?php
include 'dbConnect.php';
include 'mysqlQueries.php';

// {thID:thID, shID:shID, shFrom:fromTime, shTo:toTime, shStatus:shStatus },

  // $thID = filter_input(INPUT_POST, 'thID');
  $shID = filter_input(INPUT_POST, 'shID');
  $shFrom = filter_input(INPUT_POST, 'shFrom');
  $shTo = filter_input(INPUT_POST, 'shTo');
  $shStatus = filter_input(INPUT_POST, 'shStatus');

  // $thID = $conn->real_escape_string($thID);
  $shID = $conn->real_escape_string($shID);
  $shFrom = $conn->real_escape_string($shFrom);
  $shTo = $conn->real_escape_string($shTo);
  $shStatus = $conn->real_escape_string($shStatus);

  // $updateShowString = "UPDATE showtiming SET fromTime='".$shFrom."', toTime='".$shTo."', 
  //                         theatre_id_sh='".$thID."', status='".$shStatus."' where show_id=".$shID;
  $updateShowQuery = "UPDATE showtiming SET fromTime='".$shFrom."', toTime='".$shTo."', 
                          status='".$shStatus."' where show_id=".$shID;

  if ($conn->query($updateShowQuery) === TRUE) {
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
  $conn->close();
?>