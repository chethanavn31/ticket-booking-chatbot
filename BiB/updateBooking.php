
<?php
include 'dbConnect.php';
include 'mysqlQueries.php';

// {bkID:bkID, bkStatus:bkStatus}

  $bkID = filter_input(INPUT_POST, 'bkID');
  $bkStatus = filter_input(INPUT_POST, 'bkStatus');
  
  $bkID = $conn->real_escape_string($bkID);
  $bkStatus = $conn->real_escape_string($bkStatus);

  $updateBookingQuery = "UPDATE booking
                        SET status = '".$bkStatus."' WHERE booking_id = ".$bkID;
  
  if ($conn->query($updateBookingQuery) === TRUE) {
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