<?php

function getBookingData($mId,$thId,$shId,$locId,$userID,$mDate) {
    // booking_id, date, no_of_seat, seatNumber, amount, movie_id_b,
    //  theatre_id_b, show_id, location_id_b, user_id, status
  
    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $msQuery = "SELECT * FROM booking WHERE movie_id_b=".$mId." AND theatre_id_b=".$thId.
            " AND show_id=".$shId." AND location_id_b=".$locId." AND user_id=".$userID.
            " AND date(date)='".$mDate."' AND status<>'Cancelled'";

    $results = $db_handle->runQuery($msQuery);
    if(!empty($results))
    {
        $retVar=$results;
    }
    else if(empty($results))
    {
        $retVar="N";
    }
    else
    {
        $retVar="E";
    }
    return $retVar;
}

function getTicketDetails($mId,$thId,$shId,$locId) {
    // movie, theatre, showtype, timeFrom, location

    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $msQuery = "SELECT m.name movie, m.posterURL poster, t.name theatre,s.type showtype,s.fromTime timeFrom,l.name location 
                FROM movie m,theatre t,showtiming s,location l 
                WHERE m.movie_id=".$mId." AND t.theatre_id=".$thId." AND s.show_id=".$shId.
                " AND l.location_id=".$locId;

    $results = $db_handle->runQuery($msQuery);
    if(!empty($results))
    {
        $retVar=$results;
    }
    else if(empty($results))
    {
        $retVar="N";
    }
    else
    {
        $retVar="E";
    }
    return $retVar;
}



function getSeatDetails($thId) {
    // seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, status

    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $msQuery = "SELECT * FROM seat WHERE theatre_id_s=".$thId;

    $results = $db_handle->runQuery($msQuery);
    if(!empty($results))
    {
        $retVar=$results;
    }
    else if(empty($results))
    {
        $retVar="N";
    }
    else
    {
        $retVar="E";
    }
    return $retVar;
}

function getUserBookings($senderID) {
    // seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, status

    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $msQuery = "SELECT * FROM booking WHERE user_id=".$senderID;

    $results = $db_handle->runQuery($msQuery);
    if(!empty($results))
    {
        $retVar=$results;
    }
    else if(empty($results))
    {
        $retVar="N";
    }
    else
    {
        $retVar="E";
    }
    return $retVar;
}

?>