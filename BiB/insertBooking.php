<?php
include 'dbConnect.php';

// {$bdate: , $bcount:, $bseat:, $bamount:, $bmov:, $bth:, $bsh:, $bloc:, $buser: }

$bdate = filter_input(INPUT_POST, 'bdate');
$bcount = filter_input(INPUT_POST, 'bcount');
$bseat = filter_input(INPUT_POST, 'bseat');
$bamount = filter_input(INPUT_POST, 'bamount');
$bmov = filter_input(INPUT_POST, 'bmov');
$bth = filter_input(INPUT_POST, 'bth');
$bsh = filter_input(INPUT_POST, 'bsh');
$bloc = filter_input(INPUT_POST, 'bloc');
$buser = filter_input(INPUT_POST, 'buser');


$bdate = $conn->real_escape_string($bdate);
$bcount = $conn->real_escape_string($bcount);
$bseat = $conn->real_escape_string($bseat);
$bamount = $conn->real_escape_string($bamount);
$bmov = $conn->real_escape_string($bmov);
$bth = $conn->real_escape_string($bth);
$bsh = $conn->real_escape_string($bsh);
$bloc = $conn->real_escape_string($bloc);
$buser = $conn->real_escape_string($buser);

//  date, no_of_seat, seatNumber, amount, 
// movie_id_b, theatre_id_b, show_id, location_id_b, user_id, status
$insertBookingString = "INSERT into booking (date,no_of_seat,seatNumber,amount,movie_id_b,theatre_id_b,show_id,location_id_b,user_id,status) 
                values('".$bdate."',".$bcount.",'".$bseat."',".$bamount.",".$bmov.",".$bth.",".$bsh.",".$bloc.",".$buser.",'Booked')";
   
    if ($conn->query($insertBookingString) === TRUE) {
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