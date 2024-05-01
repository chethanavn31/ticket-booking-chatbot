
<?php
include 'dbConnect.php';
include 'mysqlQueries.php';

// {adminID : adId, password : pwd}

  $adminID = filter_input(INPUT_POST, 'adminID');
  $password = filter_input(INPUT_POST, 'password');
  
  $adminID = $conn->real_escape_string($adminID);
  $password = $conn->real_escape_string($password);
  $password = md5($password);

  $updateAdminQuery = "UPDATE admin
                        SET password = '".$password."' WHERE admin_id = ".$adminID;
  
  if ($conn->query($updateAdminQuery) === TRUE) {
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