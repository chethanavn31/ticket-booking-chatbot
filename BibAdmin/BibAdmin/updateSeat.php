<?php
include 'dbConnect.php';
include 'mysqlQueries.php';

// { seatID:stId, seatType:stType, fromSeat:stFrom, toSeat:stTo, totalSeats:stCount ,amount:stAmount, 
    // status:selStatus },

  $seatID = filter_input(INPUT_POST, 'seatID');
  $seatType = filter_input(INPUT_POST, 'seatType');
  $fromSeat = filter_input(INPUT_POST, 'fromSeat');
  $toSeat = filter_input(INPUT_POST, 'toSeat');
  $totalSeats = filter_input(INPUT_POST, 'totalSeats');
  $amount = filter_input(INPUT_POST, 'amount');
  $status = filter_input(INPUT_POST, 'status');

  $seatID = $conn->real_escape_string($seatID);
  $seatType = $conn->real_escape_string($seatType);
  $fromSeat = $conn->real_escape_string($fromSeat);
  $toSeat = $conn->real_escape_string($toSeat);
  $totalSeats = $conn->real_escape_string($totalSeats);
  $amount = $conn->real_escape_string($amount);
  $status = $conn->real_escape_string($status);

  // $updateShowString = "UPDATE showtiming SET fromTime='".$shFrom."', toTime='".$shTo."', 
  //                         theatre_id_sh='".$thID."', status='".$shStatus."' where show_id=".$shID;
//   seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, status
  $updateSeatString = "UPDATE seat SET seatType='".$seatType."', fromSeat='".$fromSeat."', toSeat='".$toSeat."', 
                         totalSeats='".$totalSeats."', amount='".$amount."', status='".$status."' where seat_id=".$seatID;

  if ($conn->query($updateSeatString) === TRUE) {
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