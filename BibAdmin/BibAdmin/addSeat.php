<?php
include 'dbConnect.php';
include 'mysqlQueries.php';

//  { thID: selTheatre, seatID:stId, seatType:stType, fromSeat:stFrom, toSeat:stTo, totalSeats:stCount ,amount:stAmount, status:selStatus },
$seatType = filter_input(INPUT_POST, 'seatType');
$fromSeat = filter_input(INPUT_POST, 'fromSeat');
$toSeat = filter_input(INPUT_POST, 'toSeat');
$totalSeats = filter_input(INPUT_POST, 'totalSeats');
$amount = filter_input(INPUT_POST, 'amount');
$status = filter_input(INPUT_POST, 'status');
$thID = filter_input(INPUT_POST, 'thID');

$seatType = $conn->real_escape_string($seatType);
$fromSeat = $conn->real_escape_string($fromSeat);
$toSeat = $conn->real_escape_string($toSeat);
$totalSeats = $conn->real_escape_string($totalSeats);
$amount = $conn->real_escape_string($amount);
$status = $conn->real_escape_string($status);
$thID = $conn->real_escape_string($thID);

  //   seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, status
//   $insertTheatreString = "INSERT into theatre (name, seat, status, layout) 
//                 values('".$thName."','".$thSeats."','".$thStatus."','".$thLayout."')";
$insertSeatString = "INSERT into seat (seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, status) 
                values('".$seatType."','".$fromSeat."','".$toSeat."','".$totalSeats."','".$amount."','".$thID."','".$status."')";
   
    if ($conn->query($insertSeatString) === TRUE) {
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