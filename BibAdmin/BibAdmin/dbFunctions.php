<?php

function getAllMovies() {
    include_once 'dbController.php';
    include_once 'mysqlQueries.php';
    //real_escape_string($varanme);
    $retVar='E';
    $db_handle = new DBController();
    $msQuery = $getAllMovieNames;
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

function getAllLocationsData() {
    include_once 'dbController.php';
    //include_once 'mysqlQueries.php';
    //real_escape_string($varanme);
    $retVar='E';
    $db_handle = new DBController();
    $getAllLocationsQuery = "SELECT * FROM location order by name";
    $msQuery = $getAllLocationsQuery;
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

function getActiveLocations() {
    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $getActiveLocationQuery = "SELECT * FROM location 
                            WHERE status = 'Active' order by name";
    $msQuery = $getActiveLocationQuery;
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


function getUserStatus($userName) {
    include 'dbController.php';
    $retVar = "B3";
    $db_handle = new DBController();

    $query = "select status from admin_tbl  where username = '$userName'";
	$results = $db_handle->runQuery($query);
        if(!empty($results))
        {
            $retVar=$results;
        }
        else
        {
            $retVar="N3";
        }
        return $retVar;  

}

function getAllTheatres() {
    include_once 'dbController.php';
    include_once 'mysqlQueries.php';
    $retVar='E';
    $db_handle = new DBController();
    $msQuery = $getAllTheatresQuery;
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


function getAllLayoutsData() {
    include_once 'dbController.php';

    $retVar='E';
    $db_handle = new DBController();
    $getAllLayoutsQuery = "SELECT DISTINCT layout from theatre";
    $msQuery = $getAllLayoutsQuery;
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

function getAllShowtimings() {
    include_once 'dbController.php';
    include_once 'mysqlQueries.php';
    $retVar='E';
    $db_handle = new DBController();
    $msQuery = $getAllShowtimingQuery;
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

function getShowtype() {
    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $getShowtypeQuery = "select distinct type from showtiming";
    $msQuery = $getShowtypeQuery;
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

function getActiveTheatres() {
    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $getActiveTheatresQuery = "SELECT theatre_id,name 
                            FROM theatre WHERE status = 'Active' order by name";
    $msQuery = $getActiveTheatresQuery;
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

function getAllSeats() {
    // <!-- seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, name -->

    include_once 'dbController.php';
    include_once 'mysqlQueries.php';
    $retVar='E';
    $db_handle = new DBController();
    $msQuery = $getAllSeatsQuery;
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

function getAllBookings() {
    // <!-- booking_id, date, seatNumber, amount, status, movie, theatre, showname, location, user -->

    include_once 'dbController.php';
    include_once 'mysqlQueries.php';
    $retVar='E';
    $db_handle = new DBController();
    $msQuery = $getAllBookingsQuery;
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

function getTheatresForMovie($movId) {
// <!-- id, theatre_id_m, movie_id -->

    include_once 'dbController.php';
    include_once 'mysqlQueries.php';
    $retVar='E';
    $db_handle = new DBController();
    $msQuery = returnTheatresForMovieQuery($movId);
    $results = $db_handle->runQuery($msQuery);
    if(!empty($results))
    {
        $retVar=$results;
    }
    else if(empty($results))
    {
        $retVar=[];
    }
    else
    {
        $retVar="E";
    }
    return $retVar;
}

// ----------------  DASHBOARD -------------

function getUserCount() {
    // <!-- seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, name -->

    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $userCountQuery = "SELECT COUNT(*) Total,
                    SUM(case when status = 'Active' then 1 else 0 end)  Active,
                    SUM(case when status = 'Inactive' then 1 else 0 end)  Inactive
                    FROM user";
    $msQuery = $userCountQuery;
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

function getBookingCount() {
    // <!-- seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, name -->

    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $bookingCountQuery = "SELECT COUNT(*) Total,
                        SUM(case when status = 'Booked' then 1 else 0 end)  Booked,
                        SUM(case when status = 'Confirmed' then 1 else 0 end)  Confirmed,
                        SUM(case when status = 'Cancelled' then 1 else 0 end)  Cancelled
                        FROM booking";
    $msQuery = $bookingCountQuery;
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

function getMovieCount() {
    // <!-- seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, name -->

    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $movieCountQuery = "SELECT COUNT(*) Total,
                    SUM(case when status = 'Active' then 1 else 0 end)  Active,
                    SUM(case when status = 'Inactive' then 1 else 0 end)  Inactive
                    FROM movie";
    $msQuery = $movieCountQuery;
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

function getTheatreCount() {
    // <!-- seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, name -->

    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $theatreCountQuery = "SELECT COUNT(*) Total,
                        SUM(case when status = 'Active' then 1 else 0 end)  Active,
                        SUM(case when status = 'Inactive' then 1 else 0 end)  Inactive
                        FROM theatre";
    $msQuery = $theatreCountQuery;
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

function getLocationCount() {
    // <!-- seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, name -->

    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $locationCountQuery = "SELECT COUNT(*) Total,
                        SUM(case when status = 'Active' then 1 else 0 end)  Active,
                        SUM(case when status = 'Inactive' then 1 else 0 end)  Inactive
                        FROM location";
    $msQuery = $locationCountQuery;
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

function getSalesCount() {
    // <!-- seat_id, seatType, fromSeat, toSeat, totalSeats, amount, theatre_id_s, name -->

    include_once 'dbController.php';
    $retVar='E';
    $db_handle = new DBController();
    $getSalesQuery = "SELECT SUM(amount) Total
                        FROM booking
                        WHERE status = 'Confirmed'";
    $msQuery = $getSalesQuery;
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