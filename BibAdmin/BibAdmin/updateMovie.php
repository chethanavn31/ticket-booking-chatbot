<?php
include 'dbConnect.php';
include 'mysqlQueries.php';

//  {mID, mName, mDir, mGenre, mLang, mCert, mDescrp, mCast, mPoster, mFrom, mTo, status}
  $mID = filter_input(INPUT_POST, 'mID');
  $mName = filter_input(INPUT_POST, 'mName');
  $mDir = filter_input(INPUT_POST, 'mDir');
  $mGenre = filter_input(INPUT_POST, 'mGenre');
  $mLang = filter_input(INPUT_POST, 'mLang');
  $mCert = filter_input(INPUT_POST, 'mCert');
  $mDescrp = filter_input(INPUT_POST, 'mDescrp');
  $mCast = filter_input(INPUT_POST, 'mCast');
  $mPoster = filter_input(INPUT_POST, 'mPoster');
  $mDate = filter_input(INPUT_POST, 'mDate');
  $mTime = filter_input(INPUT_POST, 'mTime');
  $mFrom = filter_input(INPUT_POST, 'mFrom');
  $mTo = filter_input(INPUT_POST, 'mTo');
  $status = filter_input(INPUT_POST, 'status');
  $thMvIdData = filter_input(INPUT_POST, 'thMvIdData');
  $thMvIdData = json_decode($thMvIdData,true);

  $mID = $conn->real_escape_string($mID);
  $mName = $conn->real_escape_string($mName);
  $mDir = $conn->real_escape_string($mDir);
  $mGenre = $conn->real_escape_string($mGenre);
  $mLang = $conn->real_escape_string($mLang);
  $mCert = $conn->real_escape_string($mCert);
  $mDescrp = $conn->real_escape_string($mDescrp);
  $mCast = $conn->real_escape_string($mCast);
  $mPoster = $conn->real_escape_string($mPoster);
  $mDate = $conn->real_escape_string($mDate);
  $mTime = $conn->real_escape_string($mTime);
  $mFrom = $conn->real_escape_string($mFrom);
  $mTo = $conn->real_escape_string($mTo);
  $status = $conn->real_escape_string($status);

  $qStart = "INSERT into movie_theatre (theatre_id_m, movie_id) values ";
  $qMiddle="";
  foreach ($thMvIdData as $key => $value) {
    $qMiddle = $qMiddle."(".$conn->real_escape_string($value['mthId']).",".$conn->real_escape_string($value['movID'])."),";
  }
  $qMiddle = substr($qMiddle, 0, -1);
  $insertThForMovieQuery = $qStart.$qMiddle;

  $updateMovieQuery = "UPDATE movie
                      SET name='".$mName."', director='".$mDir."', genre='".$mGenre."', language='".$mLang."',
                      certificate='".$mCert."', description='".$mDescrp."', cast='".$mCast."', posterURL='".$mPoster."',
                      releaseDate='".$mDate."', duration='".$mTime."', fromDate='".$mFrom."', toDate='".$mTo."', status ='".$status."'
                      WHERE movie_id = ".$mID;

if ($conn->query($updateMovieQuery) === TRUE) {
  $deleteThForMovQuery = "DELETE FROM movie_theatre WHERE movie_id = ".$mID;

  if ($conn->query($deleteThForMovQuery) === TRUE) {

    if ($conn->query($insertThForMovieQuery) === TRUE) {
      if (!$conn -> commit()) {
        echo "Commit transaction failed";
        
      }
      else{
          echo "S";
      }
    }
    else {
      echo "Error inserting th for mov ".json_encode($thMvIdData);
    }
  }
  else {
    echo "Error deletng th for mov";
  }
}
else {
  echo "Error updating mov";
}  
$conn->close();
?>