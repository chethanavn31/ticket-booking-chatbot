<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//$servername = "192.168.1.233";
$servername = "localhost";
$serverusername = "root";
$serverpassword = "Nitya@123";
$serverdbname = "bib_DB";

// Create connection
$conn = new mysqli($servername, $serverusername, $serverpassword, $serverdbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else
{
   // echo 'connected';
} 

?>
