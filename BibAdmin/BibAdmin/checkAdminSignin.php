<?php
session_start();
// admin_id, name, email, password, status
    include_once 'dbController.php';
    // include 'dbConnect.php';

    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');

    $password = md5($password);

    $retVar='E';
    $db_handle = new DBController();
    $adminLoginQuery = "SELECT * FROM admin WHERE email= '".$username."' AND password= '".$password."'";  

    $msQuery = $adminLoginQuery;
    $results = $db_handle->runQuery($msQuery);
    if(!empty($results))
    {
        $retVar=$results;
        $_SESSION["adminID"] = $retVar[0]["admin_id"];
        $_SESSION["email"] = $retVar[0]["email"];
        $_SESSION["name"] = $retVar[0]["name"]; 
        $_SESSION["status"] = 'Active'; 
        $retVar="S";
        // $adminStatusUpdateQuery = "UPDATE admin SET status = 'Active'
        //                             WHERE admin_id = ".$retVar[0]["admin_id"]; 
        // if ($conn->query($adminStatusUpdateQuery) === TRUE) {
        //     if (!$conn -> commit()) {
        //       echo "Commit transaction failed";              
        //     }
        //     else{
        //         echo "S";
                
          //   }
          // }
          // else {
          //   echo "Error updting ";
          // }  
    }
    else if(empty($results))
    {
        $retVar="N";
    }
    else
    {
        $retVar="E";
    }
    echo $retVar;
    //echo json_encode($retVar);
?>