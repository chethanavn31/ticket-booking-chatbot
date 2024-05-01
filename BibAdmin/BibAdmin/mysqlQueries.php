<?php

$getAllMovieNames="SELECT * FROM movie ORDER BY name";

$getAllTheatresQuery= "SELECT t.*,l.location_id,l.name locname,lt.id loc_th_Id
                    FROM theatre t,location_theatre lt,location l
                    WHERE l.location_id = lt.location_id_l
                    AND lt.theatre_id_l = t.theatre_id
                    AND l.status = 'Active'
                    ORDER BY t.name";

$getAllBookingsQuery = "SELECT b.*, m.posterURL movie, t.name theatre, s.type showname, s.fromTime ftime, l.name location, u.username user
                    FROM booking b, movie m, theatre t, showtiming s, location l, user u
                    WHERE b.movie_id_b = m.movie_id
                    AND b.theatre_id_b = t.theatre_id
                    AND b.show_id = s.show_id
                    AND b.location_id_b = l.location_id
                    AND b.user_id = u.user_id";

$getAllLayoutsQuery = "SELECT * from theatre";

$getAllShowtimingQuery= "SELECT sh.*,t.name
                        FROM theatre t,showtiming sh
                        WHERE theatre_id_sh = t.theatre_id
                        AND t.status='Active'
                        ORDER BY t.name";

$getAllSeatsQuery="SELECT st.*,t.name
                    FROM theatre t,seat st
                    WHERE st.theatre_id_s = t.theatre_id
                    AND t.status='Active'
                    ORDER BY t.name";

$getShowtypeQuery = "select distinct type from showtiming";

function returnUpdateLocDataQuery($locName,$locStatus,$locId){
    $updateLocationData = "UPDATE location SET name='".$locName."', status='".$locStatus."' 
                             WHERE location_id=".$locId;
    return $updateLocationData;
}

function insertLocDataQuery($locName,$locStatus){
    $insertLocData = "INSERT into location (name,status)
                     VALUES('".$locName."','".$locStatus."')";
    return $insertLocData;
}

function returnTheatresForMovieQuery($mID) {
    $getTheatresForMovieQuery = "SELECT mt.*
                                 FROM movie_theatre mt, theatre t
                                 WHERE movie_id = ".$mID." AND t.theatre_id = mt.theatre_id_m
                                 AND t.status = 'Active'";
    return $getTheatresForMovieQuery;
}


?>