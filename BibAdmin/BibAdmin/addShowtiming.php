<?php
include 'dbConnect.php';


// show_id, type, fromTime, toTime, theatre_id_sh, name, status
  $thId = filter_input(INPUT_POST, 'thId');
  $showTimeData = filter_input(INPUT_POST, 'showTimeData');
  $showTimeData = json_decode($showTimeData,true);
  
  $thId = $conn->real_escape_string($thId);

  $qStart = "insert into showtiming (type,fromTime,toTime,theatre_id_sh,status) values ";
  $qMiddle="";
  foreach ($showTimeData as $key => $value) {
    $qMiddle = $qMiddle."('".$conn->real_escape_string($value['showType']).
    "','".$conn->real_escape_string($value['showTimeF']).
    "','".$conn->real_escape_string($value['showTimeT']).
    "',".$thId.",'".$conn->real_escape_string($value['showStatus'])."'),";
  }
  $qMiddle = substr($qMiddle, 0, -1);
  $batchinsertQuery = $qStart.$qMiddle;


  if ($conn->query($batchinsertQuery) === TRUE) {
      // $last_id = $conn->insert_id;
      if (!$conn -> commit()) {
        echo "Commit transaction failed";
    }
    else{
        echo "S";
    }
  }
  else {
    echo "Error inserting showtimes ".$batchinsertQuery." ".$conn -> error." is the error";
  }  
  $conn->close();
?>